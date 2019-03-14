<?
class Mongo {
    public static $host;

    private static $instance;

    public static function instance() {
        if (!self::$instance) {
            self::$instance = new MongoDB\Client(self::$host);
        }
        return self::$instance;
    }
}