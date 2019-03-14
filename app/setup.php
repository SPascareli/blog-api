<?
require 'vendor/autoload.php';

// autoload classses
Flight::path([
    'class/',
    'controller/',
    'model/',
    'exception/'
]);

Mongo::$host = "mongodb://mongodb";
