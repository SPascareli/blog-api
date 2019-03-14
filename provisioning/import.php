<?php
/**
 * Script that populates the mongo db with predefined data, called like:
 *  php -f import.php COLLECTION_NAME JSON_DATA_FILE
 * 
 * Where the COLLECTION_NAME is the name of the collection to be populated and JSON_DATA_FILE is the file with the
 * data.
 */

require 'vendor/autoload.php';

$client = new MongoDB\Client($_ENV['MONGO_HOST']);

// OPTIONS:
//  posts
//  comments
//  users
$collection = $argv[1];
$file = $argv[2];

// parse the file data
$data = json_decode(file_get_contents($file));

$db = $client->blog;

$collExists = false;
foreach ($db->listCollections() as $coll) {
    $collExists = $coll['name'] == $collection;
    if ($collExists) break;
}

if ($collExists) {
    echo $collection . " already exists, not provisioning again.";
    exit;
}

echo "Provisioning $collection collection...";

// insert the data in the db
$db->{$collection}->insertMany($data);