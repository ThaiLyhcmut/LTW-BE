<?php

class TopicController extends Controller {
  private function Secret() {
    $token = $this->getBearerToken();
    if (isset($token)) {
      $data = $this->convert_json($this->JWTdecode($token));
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
    $name = $body['name'];
    $description = $body['description'];
    $country_code = $body['country_code'];
    $image_url = $this->Upload();
    echo $this->createTopic($name, $description, $country_code, $image_url);
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
      if ($key === "id" || $key === 'created_at' || $val === null) {
          continue;
      }
      $fields[] = "$key = ?";
      $values[] = $val;
      $types .= "s"; // Giả sử tất cả đều là string, sửa nếu cần
    }
    $values[] = $body['id'];
    $types .= "i"; // Giả sử id là số nguyên
    echo $this->editTopic($fields, $values, $types);
  }
  public function get() {
    $body = $this->getBody();
    $country_code = $body['country_code'];
    $page = max(1, (int) ($this->getQueryParam('page') ?? 1));
    $limit = max(1, (int) ($this->getQueryParam('limit') ?? 10));

    echo $this->getTopic($country_code, $page, $limit);
  }
  public function delete() {
    if ($this -> Secret() !== true) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return ;
    }
    $body = $this->getBody();
    $id = $body['id'];
    echo $this->deleteTopic($id);
  }
}