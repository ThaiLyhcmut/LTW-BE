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
    if ($username && $email && $password && $otp) {
      echo $this->registerAuth($username, $email, $password, $otp);
    }
    else echo $this->convert_json(['message' => 'Body invalid']);
  }
}