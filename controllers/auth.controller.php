<?php

class AuthController extends Controller
{
  private function Secret()
  {
    $token = $_COOKIE['auth_token'] ?? null;
    if ($token) {
      $data = (array) $this->JWTdecode($token);
      return $data['role'] === 'admin';
    } else {
      http_response_code(400);
      return false;
    }
  }
  public function country()
  {
    echo $this->getCountry();
  }
  public function login()
  {
    $body = $this->getBody();
    $email = $body['email'];
    $password = $body['password'];
    if ($email && $password) {
      $auth = $this->loginAuth($email, $password);
      echo $auth;
    } else {
      http_response_code(400);
      echo $this->convert_json(['message' => 'Email and password are required']);
    }
  }
  public function info()
  {
    $token = $this->getBearerToken();
    if (isset($token)) {
      echo $this->convert_json($this->JWTdecode($token));
    } else {
      http_response_code(400);
      echo $this->convert_json(['message' => 'Token invalid']);
    }
  }
  public function otp()
  {
    $body = $this->getBody();
    $email = $body['email'];
    if ($email) {
      echo $this->otpAuth($email);
    } else {
      http_response_code(400);
      echo $this->convert_json(['message' => 'Email invalid']);
    }
  }
  public function register()
  {
    $body = $this->getFormData();
    $username = $body['username'];
    $email = $body['email'];
    $password = $body['password'];
    $otp = $body['otp'];
    $country_code = $body['country_code'];
    $avatar_url = $this->Upload() ?? "";
    if ($username && $email && $password && $otp) {
      echo $this->registerAuth($username, $email, $password, $country_code, $avatar_url, $otp);
    } else {
      http_response_code(400);
      echo $this->convert_json(['message' => 'Body invalid']);
    }
  }
  public function getAbout()
  {
    echo $this->getAboutPage();
  }
  public function loginAdmin()
  {
    require "./views/admin/auth-login.php";
  }
  public function index()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    require "./views/admin/index.php";
  }
  public function song()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Mặc định là 10 nếu không có tham số 'limit'
    $data = $this->getSong($page, $limit);
    require "./views/admin/song.php";
  }
  public function songEdit()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $data = $this->getDetailSong($id);
    require "./views/admin/song.edit.php";
  }
  public function songCreate()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true);
    require "./views/admin/song.create.php";
  }
  public function topic()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Mặc định là 10 nếu không có tham số 'limit'
    $country_code = !empty($_GET['country']) ? $_GET['country'] : "VN";
    $data = $this->getTopic($country_code, $page, $limit);
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true); // true để trả về mảng thay vì đối tượng
    // echo $data;
    require "./views/admin/topic.php";
  }
  public function topicEdit()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $data = $this->getDetailTopic($id);
    require "./views/admin/topic.edit.php";
  }
  public function topicCreate() {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true);
    require "./views/admin/topic.create.php";
  }
  public function album()
  {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Mặc định là 10 nếu không có tham số 'limit'
    $data = $this->getAlbum($page, $limit);
    require "./views/admin/album.php";
  }

  public function albumEdit() //DONE
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $data = $this->getDetailAlbum($id);
    require "./views/admin/album.edit.php";
  }
  
  public function albumCreate() {//NOT DONE
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true);
    require "./views/admin/album.create.php";
  }

  public function albumSongs() {//NOT DONE
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Mặc định là 10 nếu không có tham số 'limit'
    $data = $this->getAlbumSong($id, $page, $limit);
    $data2 = $this->getDetailAlbum($id);
    require "./views/admin/album.songs.php";
  }


  public function singer()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Mặc định là 10 nếu không có tham số 'limit'
    $country_code = !empty($_GET['country']) ? $_GET['country'] : "VN";
    $data = $this->getSinger($country_code, $page, $limit);
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true); // true để trả về mảng thay vì đối tượng
    require "./views/admin/singer.php";
  }
  public function post()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Mặc định là 10 nếu không có tham số 'limit'
    $data = $this->getPost($page, $limit);
    require "./views/admin/post.php";
  }
  public function postEdit() //DONE
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $data = $this->getDetailPost($id);
    require "./views/admin/post.edit.php";
  }
  
  public function postCreate() {//NOT DONE
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true);
    require "./views/admin/post.create.php";
  }



}

