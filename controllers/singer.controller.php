<?php


class SingerController extends Controller{
  public function create() {
    $body = $this->getBody();
    $name = $body['name'];
    $country_code = $body['country_code'];
    $avatar_url = $this->Upload();
    echo $this->createSinger($name, $country_code, $avatar_url);
  }
  public function edit() {
    $body = $this->getBody();
    $fields = [];
    $values = [];
    $types = "";
    foreach ($body as $key => $val) {
      if ($key === "id" || $val === null) {
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
    $page = ((int) ($this->getQueryParam('page') ?? 1)) ?: 1;
    $limit = ((int) ($this->getQueryParam('limit') ?? 10)) ?: 10;
    echo $this->getSinger($country_code, $page, $limit);
  }
  public function delete() {
    $body = $this->getBody();
    $id = $body['id'];
    echo $this->deleteSinger($id);
  }
}