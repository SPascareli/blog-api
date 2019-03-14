<?
require_once "setup.php";

Flight::route('GET /posts(/@id)', function($id = null){
    $result = null;
    $posts = Mongo::instance()->blog->posts;
    
    try {
        if ($id) {
            $result = $posts->findOne(['_id' => intval($id)]);
            if ($result) {
                $users = Mongo::instance()->blog->users;
                $comments = Mongo::instance()->blog->comments;
    
                $result['author'] = $users->findOne(['_id' => intval($result['userId'])]);
                $result['comments'] = $comments->find(['postId' => intval($result['_id'])])->toArray();
            }
        } else {
            $result = $posts->find()->toArray();
        }
    } catch (Exception $ex) {
        Flight::halt(500, "There was an error while processing your request.");
        Flight::stop();
    }

    if (is_null($result)) {
        Flight::halt(404, "The post $id was not found");
    }
    Flight::json($result);
});

Flight::route('POST /posts', function(){
    $result = null;
    
    try {
        $posts = Mongo::instance()->blog->posts;
        $rawData = Flight::request()->data;

        // if the post already exist, don't create it again
        if (isset($rawData['_id']) && !empty($rawData['_id'])) {
            Flight::json($rawData['_id']);
            Flight::stop();
        }

        // insert the user ("author") if it is not set
        if (!isset($rawData['userId']) && empty($rawData['userId'])) {
            $users = Mongo::instance()->blog->users;

            if (!isset($rawData['author'])) {
                Flight::halt(400, "The \"author\" object is required if you do not set the \"userId\" key.");
                Flight::stop();
            }
            
            $rawData['userId'] = $users->insertOne(array_merge(
                [
                    "_id"=>$users->countDocuments() + 1
                ],
                $rawData['author']
            ));
            $rawData['userId'] = $rawData['userId'] ? $rawData['userId']->getInsertedId() : $rawData['author']['_id'];
        }
        $insertResult = $posts->insertOne([
            "_id"=>$posts->countDocuments() + 1,
            "userId"=>$rawData['userId'],
            "title"=>$rawData['title'],
            "body"=>$rawData['body'],
        ]);
        $result = $insertResult->getInsertedId();
    } catch (Exception $ex) {
        Flight::halt(500, "There was an error while processing your request.");
        Flight::stop();
    }
    
    if (is_null($result)) {
        Flight::halt(500, "There was an error while processing your request.");
        Flight::stop();
    }
    Flight::json($result);
});

Flight::start();
