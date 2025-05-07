<?php


class PostController extends Controller{
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
    $desc = $body['desc'];
    $img = $this->Upload();
    echo $this->createPost($title, $img, $desc);
  }
  public function edit() {
    if ($this->Secret() !== true) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return;
  }

  $body = $this->getFormData();
  $fields = [];
  $values = [];
  $types = "";
  

  // Loop through form data
  foreach ($body as $key => $val) {
      // Handle fileAudio upload
      
      // Handle file upload (cover image)
      if ($key === 'file' && !empty($_FILES['file']['name'])) {
          $key = 'img';
          $val = $this->Upload();
          if ($val === false) {
              return; // Error already sent by Upload
          }
      }

      // Skip unwanted fields (e.g., id, timestamps)
      if ($key === 'id' || $val === null) {
          continue;
      }

      $escapedKey = ($key === 'desc') ? '`desc`' : $key;

      // Prepare fields for SQL update
      $fields[] = "$escapedKey = ?";
      $values[] = $val;
      $types .= 's';
  }

  // Append song id for the WHERE clause
  $values[] = $body['id'];
  $types .= 'i';

  // Execute edit query
  echo $this->editPost($fields, $values, $types);
  }
  public function get() {
    $page = max(1, (int) ($this->getQueryParam('page') ?? 1));
    $limit = max(1, (int) ($this->getQueryParam('limit') ?? 10));
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    if ($search) {
      echo $this->getSearchPost($search, $page, $limit);
    } else {
      echo $this->getPost($page, $limit);
    }
    
  }
  public function detailSong() {
    $id = (int) $this->getQueryParam('id');
    if ($id) {
      echo $this->getDetailPost($id);
    }else {
      echo $this->convert_json(['message' => 'Failed to get detail song']);
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
    echo $this->deletePost($id);
  }
}