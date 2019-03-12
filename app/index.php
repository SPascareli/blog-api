<?
require_once "setup.php";

Flight::route('GET /posts(/@id)', function($id = null){
    $result = null;
    $posts = Mongo::instance()->chat->posts;

    if ($id) {
        $result = $posts->findOne(['_id' => $id]);
    } else {
        $result = $posts->find()->toArray();
    }

    if (is_null($result)) {
        Flight::halt(404, "The post was not found");
    }
    Flight::json($result);
});

Flight::route('POST /posts', function(){
    $result = null;
    $posts = Mongo::instance()->chat->posts;
    $rawData = Flight::request()->data;

    try {
        $insertResult = $posts->insertOne([
            "_id"=>$rawData['id'],
            "userId"=>$rawData['userId'],
            "title"=>$rawData['title'],
            "body"=>$rawData['body'],
        ]);
        $result = $insertResult->getInsertedId();
    } catch (Exception $ex) {
        Flight::halt(500, "There was an error in your request");
    }
    
    if (is_null($result)) {
        Flight::halt(500, "There was an error in your request");
    }
    Flight::json($result);
});

Flight::start();
