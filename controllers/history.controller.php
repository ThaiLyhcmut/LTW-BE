<?php

class HistoryController extends Controller {
  private function Secret() {
    $token = $this->getBearerToken();
    if (isset($token)) {
      $data = (array) $this->JWTdecode($token);
      return $data['role'] === 'admin';
    }
    else {
      http_response_code(400);
      return false;
    }
  }
  public function create() {
    $token = $this->getBearerToken();
    $body = $this->getBody();
    $txhash = $body['txhash'];
    $time = $body['time'];
    if (isset($token)) {
      $user = (array) $this->JWTdecode($token);
      echo $this->createHistory($user['id'], $txhash, $time);
    }else {
      http_response_code(401);
      echo $this -> convert_json(['message' => 'Token invalid']);
    }
    
    
    
  }
  public function get() {
    $token = $this->getBearerToken();
    if (isset($token)) {
      $user = (array) $this->JWTdecode($token);
      echo $this->getHistory($user['id']);
    }else {
      http_response_code(401);
      echo $this -> convert_json(['message' => 'Token invalid']);
    }
  }

}