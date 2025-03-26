<?php

class FavoriteController extends Controller {
  private function getId() {
    $token = $this->getBearerToken();
    if (isset($token)) {
      $data = $this->JWTdecode($token);
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
    echo $this->createFavorite($user_id, $song_id);
  }
  public function get() {
    $user_id = $this->getId();
    if ($user_id === false) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return ;
    }
    $page = max(1, (int) ($this->getQueryParam('page') ?? 1));
    $limit = max(1, (int) ($this->getQueryParam('limit') ?? 10));

    echo $this->getFavorite($user_id, $page, $limit);
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