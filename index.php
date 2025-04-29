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
require './controllers/comment.controller.php';
require './controllers/favorite.controller.php';
require './controllers/vip.controller.php';
require './controllers/history.controller.php';
require './controllers/post.controller.php';

$router = new Router();
// auth
$router->add('POST', '/otp', 'AuthController', 'otp');
$router->add('GET', '/info', 'AuthController', 'info');
$router->add('GET', '/about', 'AuthController', 'getAbout');
$router->add('POST', '/login', 'AuthController', 'login');
$router->add('POST', '/register', 'AuthController', 'register');
// singer
$router->add('POST', '/singer', 'SingerController', 'create');
$router->add('GET', '/singer/detail', 'SingerController', 'detailSinger');
$router->add('POST', '/singer/data', 'SingerController', 'get');
$router->add('POST', '/singer/edit', 'SingerController', 'edit');
$router->add('DELETE', '/singer', 'SingerController', 'delete');
// album
$router->add('POST', '/album', 'AlbumController', 'create');
$router->add('GET', '/album/detail', 'AlbumController', 'detailAlbum');
$router->add('POST', '/album/data', 'AlbumController', 'get');
$router->add('POST', '/album/edit', 'AlbumController', 'edit');
$router->add('DELETE', '/album', 'AlbumController', 'delete');
// topic
$router->add('POST', '/topic', 'TopicController', 'create');
$router->add('GET', '/topic/detail', 'TopicController', 'detailTopic');
$router->add('POST', '/topic/data', 'TopicController', 'get');
$router->add('POST', '/topic/edit', 'TopicController', 'edit');
$router->add('DELETE', '/topic', 'TopicController', 'delete');
// song
$router->add('POST', '/song', 'SongController', 'create');
$router->add('GET', '/song/detail', 'SongController', 'detailSong');
$router->add('POST', '/song/data', 'SongController', 'get');
$router->add('POST', '/song/edit', 'SongController', 'edit');
$router->add('DELETE', '/song', 'SongController', 'delete');
// favorite
$router->add('POST', '/favorite', 'FavoriteController', 'create');
$router->add('GET', '/favorite', 'FavoriteController', 'get');
$router->add('DELETE', '/favorite', 'FavoriteController', 'delete');
// comment
$router->add('POST', '/comment', 'CommentController', 'create');
$router->add('POST', '/comment/data', 'CommentController', 'get');
$router->add('DELETE', '/comment', 'CommentController', 'delete');
// vip
$router->add('POST', '/vip', 'VipController', 'create');
$router->add('GET', '/vip', 'VipController', 'get');
$router->add('PATCH', '/vip', 'VipController', 'edit');
$router->add('DELETE', '/vip', 'VipController', 'delete');
// history
$router->add('POST', '/history', 'HistoryController', 'create');
$router->add('GET', '/history', 'HistoryController', 'get');


$router->add('POST', '/post', 'PostController', 'create');
$router->add('GET', '/post/detail', 'PostController', 'detailSong');
$router->add('GET', '/post/data', 'PostController', 'get');
$router->add('POST', '/post/edit', 'PostController', 'edit');
$router->add('DELETE', '/post', 'PostController', 'delete');


$router->add("GET", '/admin/login', 'AuthController', 'loginAdmin');
$router->add("GET", '/admin/index', 'AuthController', 'index');
$router->add("GET", '/admin/songs', 'AuthController', 'song');
$router->add("GET", '/admin/topics', 'AuthController', 'topic');
$router->add("GET", '/admin/albums', 'AuthController', 'album');
$router->add("GET", '/admin/singers', 'AuthController', 'singer');
$router->dispatch();