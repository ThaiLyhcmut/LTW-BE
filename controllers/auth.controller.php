<?php 

class AuthController extends Controller {
  public function country() {
    echo $this->country();
  }
  public function login() {
    $body = $this->getBody();
    $email = $body['email'];
    $password = $body['password'];
    if ($email && $password) {
      $auth = $this -> loginAuth($email, $password);
      echo $auth;
    }
    else {
      http_response_code(400);
      echo $this -> convert_json(['message' => 'Email and password are required']);
    }
  }
  public function info() {
    $token = $this->getBearerToken();
    if (isset($token)) {
      echo $this->convert_json($this->JWTdecode($token));
    }
    else {
      http_response_code(400);
      echo $this -> convert_json(['message' => 'Token invalid']);
    } 
  }
  public function otp() {
    $body = $this->getBody();
    $email = $body['email'];
    if ($email) {
      echo $this->otpAuth($email);
    }
    else {
      http_response_code(400);
      echo $this->convert_json(['message' => 'Email invalid']);
    }
  }
  public function register() {
    $body = $this->getFormData();
    $username = $body['username'];
    $email = $body['email'];
    $password = $body['password'];
    $otp = $body['otp'];
    $country_code = $body['country_code'];
    $avatar_url = $this->Upload()??"";
    if ($username && $email && $password && $otp) {
      echo $this->registerAuth($username, $email, $password, $country_code, $avatar_url, $otp);
    }
    else {
      http_response_code(400);
      echo $this->convert_json(['message' => 'Body invalid']);
    }
  }
  public function getAbout() {
    echo $this->getAboutPage();
  }
  public function loginAdmin() {
    require "./views/admin/auth-login.php";
  }
  public function index() {
    require "./views/admin/index.php";
  }
  public function song() {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Mặc định là 10 nếu không có tham số 'limit'
    $data = $this->getSong($page, $limit);
    require "./views/admin/song.php";
  }
}