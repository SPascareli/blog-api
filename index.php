<?
require_once "setup.php";

Flight::route('GET /posts(/@id)', function(){
    echo 'hello world!';
});

Flight::route('POST /posts', function(){
    echo 'hello world!';
});

Flight::start();
