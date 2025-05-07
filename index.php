<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function my_require($file) {
    error_log("Including file: $file");
    require $file;
    error_log("Successfully included: $file");
}

error_log("Starting index.php execution for request: {$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']}");
my_require('./conf/setResponHandler.php');
my_require('vendor/autoload.php');
my_require('./conf/header.php');
my_require('./conf/database.php');
my_require('./core/app.php');
my_require('./controllers/controller.php');
my_require('./controllers/song.controller.php');
my_require('./controllers/singer.controller.php');
my_require('./controllers/auth.controller.php');
my_require('./controllers/album.controller.php');
my_require('./controllers/topic.controller.php');
my_require('./controllers/comment.controller.php');
my_require('./controllers/favorite.controller.php');
my_require('./controllers/vip.controller.php');
my_require('./controllers/history.controller.php');
my_require('./controllers/post.controller.php');
my_require('./controllers/info.controller.php');
my_require('./controllers/user.controller.php');
my_require('./controllers/public.controller.php');

error_log("Initializing Router");
$router = new Router();

// auth
$router->add('POST', '/otp', 'AuthController', 'otp');
$router->add('GET', '/admin/info', 'InfoController', 'getInfo');
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
$router->add('GET', '/singer/search', 'SingerController', 'singerSearch');
// album
$router->add('POST', '/album', 'AlbumController', 'create');
$router->add('GET', '/album/detail', 'AlbumController', 'detailAlbum');
$router->add('GET', '/album/songs', 'AlbumController', 'songsAlbum');
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
// post
$router->add('POST', '/post', 'PostController', 'create');
$router->add('GET', '/post/detail', 'PostController', 'detailSong');
$router->add('GET', '/post/data', 'PostController', 'get');
$router->add('POST', '/post/edit', 'PostController', 'edit');
$router->add('DELETE', '/post', 'PostController', 'delete');

// for frontend
$router->add("GET", '/admin/login', 'AuthController', 'loginAdmin');
$router->add("GET", '/admin/index', 'AuthController', 'index');
$router->add("GET", '/admin/songs', 'AuthController', 'song');
$router->add("GET", '/admin/song/edit', 'AuthController', 'songEdit');
$router->add("GET", '/admin/song/create', 'AuthController', 'songCreate');
$router->add("GET", '/admin/topics', 'AuthController', 'topic');
$router->add("GET", '/admin/topic/create', 'AuthController', 'topicCreate');
$router->add("GET", '/admin/topic/edit', 'AuthController', 'topicEdit');
$router->add("GET", '/admin/albums', 'AuthController', 'album');
$router->add("GET", '/admin/album/create', 'AuthController', 'albumCreate');
$router->add("GET", '/admin/album/edit', 'AuthController', 'albumEdit');
$router->add("GET", '/admin/album/songs', 'AuthController', 'albumSongs');
$router->add("GET", '/admin/posts', 'AuthController', 'post');
$router->add("GET", '/admin/post/create', 'AuthController', 'postCreate');
$router->add("GET", '/admin/post/edit', 'AuthController', 'postEdit');
$router->add("GET", '/admin/singers', 'AuthController', 'singer');
$router->add("GET", '/admin/singer/edit', 'AuthController', 'singerEdit');
$router->add("GET", '/admin/singer/create', 'AuthController', 'singerCreate');
$router->add("GET", '/admin/help', 'AuthController', 'help');
$router->add("GET", '/admin/help/edit', 'AuthController', 'helpEdit');
// Quản lý thành viên
$router->add('GET', '/admin/users', 'UserController', 'index');
$router->add('GET', '/admin/users/create', 'UserController', 'create');
$router->add('POST', '/admin/users/store', 'UserController', 'store');
$router->add('GET', '/admin/users/edit/{id}', 'UserController', 'edit');
$router->add('POST', '/admin/users/update/{id}', 'UserController', 'update');
$router->add('POST', '/admin/users/ban/{id}', 'UserController', 'ban');
$router->add('POST', '/admin/users/delete/{id}', 'UserController', 'delete');
// Quản lý trang public
$router->add('GET', '/admin/public', 'PublicController', 'getPublicPages');
$router->add('GET', '/admin/public/edit/{id}', 'PublicController', 'editPublicPage');
$router->add('POST', '/admin/public/update/{id}', 'PublicController', 'updatePublicPage');
$router->add('GET', '/admin/public/contact', 'PublicController', 'getContactInfo');
$router->add('POST', '/admin/public/contact/update', 'PublicController', 'updateContactInfo');

error_log("Dispatching request");
$router->dispatch();
error_log("Request dispatched");
error_log("Finished index.php execution for request: {$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']}");
?>