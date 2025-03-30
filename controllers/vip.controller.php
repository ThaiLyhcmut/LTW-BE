<?php

class VipController extends Controller {
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
    if ($this -> Secret() !== true) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return ;
    }
    $body = $this->getBody();
    $text = $body['text'];
    $description = $body['description'];
    $discountPercent = $body['discountPercent'];
    $price = $body['price'];
    $time= $body['time'];
    echo $this->createVip($text, $description, $discountPercent, $price, $time);
  }
  public function edit() {
    if ($this -> Secret() !== true) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return ;
    }
    $body = $this->getBody();
    $fields = [];
    $values = [];
    $types = "";
    foreach ($body as $key => $val) {
      if ($key === "id") {
          continue;
      }
      $fields[] = "$key = ?";
      $values[] = $val;
      $types .= "s"; // Giả sử tất cả đều là string, sửa nếu cần
    }
    $values[] = $body['id'];
    $types .= "i"; // Giả sử id là số nguyên
    echo $this->ediVip($fields, $values, $types);
  }
  public function get() {
    echo $this->getVip();
  }
  public function delete() {
    if ($this -> Secret() !== true) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return ;
    }
    $body = $this->getBody();
    $id = $body['id'];
    echo $this->deleteVip($id);
  }
}