<?php

class TopicController extends Controller {
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
    $description = $body['description'];
    $country_code = $body['country_code'];
    $image_url = $this->Upload();
    error_log($image_url);
    echo $this->createTopic($name, $description, $country_code, $image_url);
  }
  public function edit() {
    if ($this -> Secret() !== true) {
      http_response_code(401);
      echo $this->convert_json(['message' => 'Failed to Authorized']);
      return ;
    }
    $body = $this->getFormData();

    
    error_log("Form data: ".print_r($body, true)); // Added debug line

    $fields = [];
    $values = [];
    $types = "";

    foreach ($body as $key => $val) {
      // Handle fileAudio upload
      // Handle file upload (cover image)
      if ($key === 'file' && !empty($_FILES['file']['name'])) {
          $key = 'image_url';
          $val = $this->Upload();
          if ($val === false) {
              return; // Error already sent by Upload
          }
      }

      // Skip unwanted fields (e.g., id, timestamps)
      if ($key === 'id' || $key === 'create_at' || $val === null) {
          continue;
      }

      // Prepare fields for SQL update
      $fields[] = "$key = ?";
      $values[] = $val;
      $types .= 's';
    } 

  // Append song id for the WHERE clause
  $values[] = $body['id'];
  $types .= 'i';
    echo $this->editTopic($fields, $values, $types);
  }
  public function get() {
    $body = $this->getBody();
    $country_code = $body['country_code'];
    $page = max(1, (int) ($this->getQueryParam('page') ?? 1));
    $limit = max(1, (int) ($this->getQueryParam('limit') ?? 10));
    $search = $query['search'] ?? null;
    if ($search) {
      echo $this->getSearchTopic($search, $country_code, $page, $limit);
    } else {
      echo $this->getTopic($country_code, $page, $limit);
    }
  }
  public function detailTopic() {
    $id = (int) $this->getQueryParam('id');
    if($id) {
      echo $this->getDetailTopic($id);
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
    echo $this->deleteTopic($id);
  }
}