<?php
require __DIR__ . '/../vendor/autoload.php';

use Framework\Router;
use Framework\Session;

Session::start();

require '../helpers.php';
// Instantiate router
$router = new Router();
// Get route
$routes = require basePath('routes.php');

// Get the uri and http method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// inspect($uri);
// inspect($method);

// Route the request
$router->route($uri);
