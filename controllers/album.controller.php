<?php

class AlbumController extends Controller {
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
    $title = $body['title'];
    $singer_id = $body['singer_id'];
    $release_year = $body['release_year'];
    $cover_url = $this->Upload();
    echo $this->createAlbum($title, $singer_id, $release_year, $cover_url);
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
      if ($key === "id" || $key === 'singer_id' || $key === 'created_at' || $val === null) {
          continue;
      }
      $fields[] = "$key = ?";
      $values[] = $val;
      $types .= "s"; // Giả sử tất cả đều là string, sửa nếu cần
    }
    $values[] = $body['id'];
    $types .= "i"; // Giả sử id là số nguyên
    echo $this->editAlbum($fields, $values, $types);
  }
  public function get() {
    $body = $this->getBody();
    $page = max(1, (int) ($this->getQueryParam('page') ?? 1));
    $limit = max(1, (int) ($this->getQueryParam('limit') ?? 10));
    if (isset($body['singer_id'])) {
      echo $this->getSingerAlbum($body['singer_id'], $page, $limit);
    }else {
      echo $this->getAlbum($page, $limit);
    }
    
  }
  public function detailAlbum() {
    $id = (int) $this->getQueryParam('id');
    if ($id) {
      echo $this->getDetailAlbum($id);
    }else {
      echo $this->convert_json(['message' => 'Failed to get detail album']);
    }
  }
  public function delete() {
    if ($this -> Secret() !== true) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return ;
    }
    $body = $this->getBody();
    $id = $body['id'];
    echo $this->deleteAlbum($id);
  }
}