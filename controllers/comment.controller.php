<?php

class CommentController extends Controller {
  private function getId() {
    $token = $this->getBearerToken();
    if (isset($token)) {
      $data = (array) $this->JWTdecode($token);
      return $data['id'];
    }
    else {
      http_response_code(400);
      return false;
    }
  }
  public function create() {
    $user_id = $this->getId();
    if ($user_id === false) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return ;
    }
    $body = $this->getBody();
    $song_id = $body['song_id'];
    $content = $body['content'];
    echo $this->createComment($user_id, $song_id, $content);
  }
  public function get() {
    $user_id = $this->getId();
    if ($user_id === false) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return ;
    }
    $body = $this->getBody();
    $song_id = $body['song_id'];
    echo $this->getComment($song_id);
  }
  public function delete() {
    $user_id = $this->getId();
    if ($user_id === false) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return ;
    }
    $body = $this->getBody();
    $id = $body['id'];
    echo $this->deleteFavorite($id);
  }
}