<?php


class SingerController extends Controller{
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
    $body = $this->getFormData();
    $name = $body['name'];
    $country_code = $body['country_code'];
    $avatar_url = $this->Upload();
    echo $this->createSinger($name, $country_code, $avatar_url);
  }
  public function edit() {
    if ($this -> Secret() !== true) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return ;
    }
    $body = $this->getFormData();
    $fields = [];
    $values = [];
    $types = "";
    foreach ($body as $key => $val) {
      if ($key === "id" || $key==="create_at" || $val === null) {
        continue;
      }
      $fields[] = "$key = ?";
      $values[] = $val;
      $types .= "s"; // Giả sử tất cả đều là string, sửa nếu cần
    }
    $values[] = $body['id'];
    $types .= "i"; // Giả sử id là số nguyên
    echo $this->editSinger($fields, $values, $types);
  }
  public function get() {
    $body = $this->getBody();
    $country_code = $body['country_code'];
    $page = max(1, (int) ($this->getQueryParam('page') ?? 1));
    $limit = max(1, (int) ($this->getQueryParam('limit') ?? 10));

    echo $this->getSinger($country_code, $page, $limit);
  }
  public function delete() {
    if ($this -> Secret() !== true) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return ;
    }
    $body = $this->getBody();
    $id = $body['id'];
    echo $this->deleteSinger($id);
  }
}