<?php


class SongController extends Controller{
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
    $body = $this->getFormData();
    $title = $body['title'];
    $duration = $body['duration'];
    $lyric = $body['lyric'];
    $file_url = $this->UploadAudio();
    $cover_url = $this->Upload();
    echo $this->createSong($title, $duration, $lyric, $file_url, $cover_url);
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
      if ($key === "id" || $key==="create_at" || $key==="singer_id" || $key==="album_id" || $key==="file_url" || $val === null) {
          continue;
      }
      $fields[] = "$key = ?";
      $values[] = $val;
      $types .= "s"; // Giả sử tất cả đều là string, sửa nếu cần
    }
    $values[] = $body['id'];
    $types .= "i"; // Giả sử id là số nguyên
    echo $this->editSong($fields, $values, $types);
  }
  public function get() {
    $body = $this->getBody();
    $page = max(1, (int) ($this->getQueryParam('page') ?? 1));
    $limit = max(1, (int) ($this->getQueryParam('limit') ?? 10));
    if (isset($body['singer_id'])){
      echo $this->getSingerSong($body['singer_id'], $page, $limit);
    }else if(isset($body['album_id'])){
      echo $this->getAlbumSong($body['album_id'], $page, $limit);
    }else if(isset($body['topic_id'])) {
      echo $this->getTopicSong($body['topic_id'], $page, $limit);
    }else {
      http_response_code(400);
      echo $this->convert_json(['message' => 'Failed to get song']);
      return ;
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
    echo $this->deleteSong($id);
  }
}