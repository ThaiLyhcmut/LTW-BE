<?php
require 'vendor/autoload.php';
require './conf/setResponHandler.php';
require './conf/header.php';
require './core/app.php';
require './controllers/controller.php';
require './controllers/song.controller.php';
require './controllers/singer.controller.php';
require './controllers/auth.controller.php';
require './controllers/album.controller.php';
require './controllers/topic.controller.php';

$router = new Router();
// auth
$router->add('POST', '/otp', 'AuthController', 'otp');
$router->add('GET', '/info', 'AuthController', 'info');
$router->add('POST', '/login', 'AuthController', 'login');
$router->add('POST', '/register', 'AuthController', 'register');
// singer
$router->add('POST', '/singer', 'SingerController', 'create');
$router->add('GET', '/singer', 'SingerController', 'get');
$router->add('POST', '/singer/edit', 'SingerController', 'edit');
$router->add('DELETE', '/singer', 'SingerController', 'delete');
// album
$router->add('POST', '/album', 'AlbumController', 'create');
$router->add('GET', '/album', 'AlbumController', 'get');
$router->add('POST', '/album/edit', 'AlbumController', 'edit');
$router->add('DELETE', '/album', 'AlbumController', 'delete');
// topic
$router->add('POST', '/topic', 'TopicController', 'create');
$router->add('GET', '/topic', 'TopicController', 'get');
$router->add('POST', '/topic/edit', 'TopicController', 'edit');
$router->add('DELETE', '/topic', 'TopicController', 'delete');
// song
$router->add('POST', '/song', 'SongController', 'create');
$router->add('GET', '/song', 'SongController', 'get');
$router->add('POST', '/song/edit', 'SongController', 'edit');
$router->add('DELETE', '/song', 'SongController', 'delete');
// favorite
$router->add('POST', '/favorite', 'FavoriteController', 'create');
$router->add('GET', '/favorite', 'FavoriteController', 'get');
$router->add('DELETE', '/favorite', 'FavoriteController', 'delete');
// comment
$router->add('POST', '/comment', 'CommentController', 'create');
$router->add('GET', '/comment', 'CommentController', 'get');
$router->add('DELETE', '/comment', 'CommentController', 'delete');

$router->dispatch();