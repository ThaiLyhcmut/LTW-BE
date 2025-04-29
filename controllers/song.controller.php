<?php


class SongController extends Controller{
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
    $duration = $body['duration'];
    $lyric = $body['lyric'];
    $file_url = $this->UploadAudio();
    $cover_url = $this->Upload();
    echo $this->createSong($title, $duration, $lyric, $file_url, $cover_url);
  }
  public function edit()
{
    // Uncomment for production
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
        if ($key === 'fileAudio' && !empty($_FILES['fileAudio']['name'])) {
            $key = 'file_url';
            $val = $this->UploadAudio();
            if ($val === false) {
                return; // Error already sent by UploadAudio
            }
        }
        
        // Handle file upload (cover image)
        if ($key === 'file' && !empty($_FILES['file']['name'])) {
            $key = 'cover_url';
            $val = $this->Upload();
            if ($val === false) {
                return; // Error already sent by Upload
            }
        }

        // Skip unwanted fields (e.g., id, timestamps)
        if ($key === 'id' || $key === 'create_at' || $key === 'singer_id' || $key === 'album_id' || $val === null) {
            continue;
        }

        // Prepare fields for SQL update
        $fields[] = "$key = ?";
        $values[] = $val;
        $types .= ($key === 'duration') ? 'i' : 's';
    }

    // Append song id for the WHERE clause
    $values[] = $body['id'];
    $types .= 'i';

    // Execute edit query
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
      echo $this->getSong($page, $limit);
    }
    
  }
  public function detailSong() {
    $id = (int) $this->getQueryParam('id');
    if ($id) {
      echo $this->getDetailSong($id);
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
    echo $this->deleteSong($id);
  }
  
}