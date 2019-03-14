<?
class PostController {

    public function Get($id) {
        $result = null;
        $posts = Mongo::instance()->blog->posts;
        
        try {
            $result = Post::Get($id);
        } catch (Exception $ex) {
            Flight::halt(500, "There was an error while processing your request.");
            Flight::stop();
        }
    
        if (empty($result)) {
            Flight::halt(404, "The post $id was not found");
        }
        
        $result = count($result) > 1 ? $result : reset($result);
        Flight::json($result);
    }
    
    public static function Create() {
        $result = null;
        $rawData = Flight::request()->data;
        
        try {
            // if the post already exist, don't create it again
            if (isset($rawData['_id']) && !empty($rawData['_id'])) {
                Flight::json($rawData['_id']);
                Flight::stop();
            }
            $result = Post::Create($rawData);
        } catch (AuthorNotDefinedException $ex) {
            Flight::halt(400, "The \"author\" object is required if you do not set the \"userId\" key.");
            Flight::stop();
        } catch (Exception $ex) {
            Flight::halt(500, "There was an error while processing your request.");
            Flight::stop();
        }
        
        if (is_null($result)) {
            Flight::halt(500, "There was an error while processing your request.");
            Flight::stop();
        }
        Flight::json($result);
    }
}