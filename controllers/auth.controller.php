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
}