<?
require_once "setup.php";

/**
 * API ROUTES
 * 
 * Define all API routes here.
 * The routes are defined using FlightPHP, please check the link bellow for the documentation: 
 * http://flightphp.com/learn/
 */

/// Gets one or all Posts by id
Flight::route('GET /posts(/@id)', 
                            ['PostController', 'Get']);

/// Creates a new Post with it's respective author
Flight::route('POST /posts', 
                            ['PostController', 'Create']);

Flight::start();
