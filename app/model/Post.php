<?
class Post {

    public static function Get($id = null) {
        $result = null;
        $posts =    Mongo::instance()->blog->posts;
        $users =    Mongo::instance()->blog->users;
        $comments = Mongo::instance()->blog->comments;
        
        if ($id) {
            $result = [
                $posts->findOne(['_id' => intval($id)])
            ];
        } else {
            $result = $posts->find()->toArray();
        }
        $result = array_map(function($result) use ($users, $comments) {
            $result['author'] = $users->findOne(['_id' => intval($result['userId'])]);
            $result['comments'] = $comments->find(['postId' => intval($result['_id'])])->toArray();
            
            return $result;
        }, $result);

        return $result;
    }
    
    public static function Create($data) {
        $posts = Mongo::instance()->blog->posts;
        
        if (!isset($data['userId']) && empty($data['userId'])) {
            $users = Mongo::instance()->blog->users;

            if (!isset($data['author'])) {
                throw AuthorNotDefinedException();
            }
            
            $data['userId'] = $users->insertOne(array_merge(
                [
                    "_id"=>$users->countDocuments() + 1
                ],
                $data['author']
            ));
            
            $data['userId'] = $data['userId'] ? $data['userId']->getInsertedId() : $data['author']['_id'];
        }

        $insertResult = $posts->insertOne([
            "_id"=>$posts->countDocuments() + 1,
            "userId"=>$data['userId'],
            "title"=>$data['title'],
            "body"=>$data['body'],
        ]);

        return $insertResult->getInsertedId();
    }
}