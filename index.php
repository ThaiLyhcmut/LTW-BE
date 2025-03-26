<?php
require 'vendor/autoload.php';
require './conf/setResponHandler.php';
require './conf/header.php';
require './core/app.php';
require './controllers/controller.php';
require './controllers/song.controller.php';
require './controllers/artist.controller.php';
require './controllers/no.controller.php';
require './controllers/auth.controller.php';

$router = new Router();
// auth
$router->add('POST', '/otp', 'AuthController', 'otp');
$router->add('GET', '/info', 'AuthController', 'info');
$router->add('POST', '/login', 'AuthController', 'login');
$router->add('POST', '/register', 'AuthController', 'register');
// song
$router->add('GET', '/songs', 'SongController', 'index'); // /songs?page=''&limit=''&start=''&albumId=''&artistId=''&favoris=''

$router->add('POST', '/upload', 'AuthController', 'image');
$router->dispatch();