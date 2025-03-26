<?php 

class AuthController extends Controller {
  public function login() {
    $body = $this->getBody();
    $email = $body['email'];
    $password = $body['password'];
    if ($email && $password) {
      $auth = $this -> loginAuth($email, $password);
      echo $auth;
    }
    else echo $this -> convert_json(['message' => 'Email and password are required']);
  }
  public function info() {
    $token = $this->getBearerToken();
    if (isset($token)) {
      echo $this->convert_json($this->JWTdecode($token));
    }
    else echo $this -> convert_json(['message' => 'Token invalid']); 
  }
  public function otp() {
    $body = $this->getBody();
    $email = $body['email'];
    if ($email) {
      echo $this->otpAuth($email);
    }
    else echo $this->convert_json(['message' => 'Email invalid']);
  }
  public function register() {
    $body = $this->getBody();
    $username = $body['username'];
    $email = $body['email'];
    $password = $body['password'];
    $otp = $body['otp'];
    $country_code = $body['country_code'];
    $avatar_url = $body['avatar_url'];
    if ($username && $email && $password && $otp) {
      echo $this->registerAuth($username, $email, $password, $country_code, $avatar_url, $otp);
    }
    else echo $this->convert_json(['message' => 'Body invalid']);
  }
  public function image() {
    echo $this->Upload();
  }
}