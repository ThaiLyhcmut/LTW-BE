<?php

class AuthController extends Controller
{
  private function Secret()
  {
    $token = $_COOKIE['auth_token'] ?? null;
    if ($token) {
      $data = (array) $this->JWTdecode($token);
      return $data['role'] === 'admin';
    } else {
      http_response_code(400);
      return false;
    }
  }
  public function country()
  {
    echo $this->getCountry();
  }
  public function login()
  {
    $body = $this->getBody();
    $email = $body['email'];
    $password = $body['password'];
    if ($email && $password) {
      $auth = $this->loginAuth($email, $password);
      echo $auth;
    } else {
      http_response_code(400);
      echo $this->convert_json(['message' => 'Email and password are required']);
    }
  }
  public function info()
  {
    $token = $this->getBearerToken();
    if (isset($token)) {
      echo $this->convert_json($this->JWTdecode($token));
    } else {
      http_response_code(400);
      echo $this->convert_json(['message' => 'Token invalid']);
    }
  }
  public function otp()
  {
    $body = $this->getBody();
    $email = $body['email'];
    if ($email) {
      echo $this->otpAuth($email);
    } else {
      http_response_code(400);
      echo $this->convert_json(['message' => 'Email invalid']);
    }
  }
  public function register()
  {
    $body = $this->getFormData();
    $username = $body['username'];
    $email = $body['email'];
    $password = $body['password'];
    $otp = $body['otp'];
    $country_code = $body['country_code'];
    $avatar_url = $this->Upload() ?? "";
    if ($username && $email && $password && $otp) {
      echo $this->registerAuth($username, $email, $password, $country_code, $avatar_url, $otp);
    } else {
      http_response_code(400);
      echo $this->convert_json(['message' => 'Body invalid']);
    }
  }
  public function getAbout()
  {
    echo $this->getAboutPage();
  }
  public function loginAdmin()
  {
    require "./views/admin/auth-login.php";
  }
  public function index()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    require "./views/admin/index.php";
  }
  public function song()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Mặc định là 10 nếu không có tham số 'limit'
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    if ($search) {
      $data = $this->getSearchSong($search, $page, $limit);
    } else {
        $data = $this->getSong($page, $limit);
    }
    // error_log("Song data: " . print_r($data, true));
    require "./views/admin/song.php";
  }
  public function songEdit()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $data = $this->getDetailSong($id);
    require "./views/admin/song.edit.php";
  }
  public function songCreate()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true);
    require "./views/admin/song.create.php";
  }
  public function topic()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Mặc định là 10 nếu không có tham số 'limit'
    $country_code = !empty($_GET['country']) ? $_GET['country'] : NULL;
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    if ($search) {
      $data = $this->getSearchTopic($search, $country_code, $page, $limit);
    } else {
      $data = $this->getTopic($country_code, $page, $limit);
    }

    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true); // true để trả về mảng thay vì đối tượng
    // echo $data;
    // error_log("Song data: " . print_r($data, true));
    require "./views/admin/topic.php";
  }
  public function topicEdit()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $data = $this->getDetailTopic($id);
    require "./views/admin/topic.edit.php";
  }
  public function topicCreate() {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true);
    require "./views/admin/topic.create.php";
  }
  public function album()
  {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Mặc định là 10 nếu không có tham số 'limit'
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    if ($search) {
      $data = $this->getSearchAlbum($search, $page, $limit);
    } else {
      $data = $this->getAlbum($page, $limit);
    }
    require "./views/admin/album.php";
  }

  public function albumEdit() //DONE
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $data = $this->getDetailAlbum($id);
    require "./views/admin/album.edit.php";
  }
  
  public function albumCreate() {//NOT DONE
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true);
    require "./views/admin/album.create.php";
  }

  public function albumSongs() {//NOT DONE
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Mặc định là 10 nếu không có tham số 'limit'
    $data = $this->getAlbumSong($id, $page, $limit);
    $data2 = $this->getDetailAlbum($id);
    require "./views/admin/album.songs.php";
  }


  public function singer()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Mặc định là 10 nếu không có tham số 'limit'
    $country_code = !empty($_GET['country']) ? $_GET['country'] : "VN";
    $data = $this->getSinger($country_code, $page, $limit);
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true); // true để trả về mảng thay vì đối tượng
    require "./views/admin/singer.php";
  }
  public function singerCreate() {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true);
    require "./views/admin/singer.create.php";
  }
  public function singerEdit() {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $data = $this->getDetailSinger($id);
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true);
    require "./views/admin/singer.edit.php";
  }
  public function help()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    
    // Get about page information as string
    $aboutPage = $this->getAboutPage();
    
    // Giải mã json từ response của getAboutPage (là một chuỗi JSON)
    $aboutInfo = json_decode($aboutPage, true);
    if (!is_array($aboutInfo)) {
      $aboutInfo = [];
    }
    
    // Xử lý các section chứa mảng URL ảnh
    $sections = ['section1', 'section2', 'section3'];
    foreach ($sections as $section) {
      if (isset($aboutInfo[$section]) && !empty($aboutInfo[$section]) && is_string($aboutInfo[$section])) {
        // Giải mã chuỗi JSON từ database
        try {
          $sectionUrls = json_decode($aboutInfo[$section], true);
          
          // Kiểm tra xem có phải là mảng URL hợp lệ không
          if (json_last_error() === JSON_ERROR_NONE && is_array($sectionUrls)) {
            $aboutInfo[$section] = [
              'urls' => $sectionUrls,  // Mảng URLs
              'content' => isset($aboutInfo[$section . '_content']) ? $aboutInfo[$section . '_content'] : ''
            ];
          } else {
            // Trường hợp JSON không hợp lệ
            $aboutInfo[$section] = [
              'urls' => [],
              'content' => '',
              'error' => 'Invalid JSON format: ' . json_last_error_msg(),
              'original' => $aboutInfo[$section]
            ];
          }
        } catch(Exception $e) {
          $aboutInfo[$section] = [
            'urls' => [],
            'content' => '',
            'error' => 'Exception: ' . $e->getMessage()
          ];
        }
      } else if (isset($aboutInfo[$section]) && is_array($aboutInfo[$section])) {
        // Trường hợp đã là array (có thể do getAboutPage đã tự giải mã JSON)
        if (!isset($aboutInfo[$section]['urls'])) {
          $aboutInfo[$section] = [
            'urls' => $aboutInfo[$section], // Giả định rằng mảng này chính là URLs
            'content' => isset($aboutInfo[$section . '_content']) ? $aboutInfo[$section . '_content'] : ''
          ];
        }
      } else {
        // Trường hợp không có dữ liệu
        $aboutInfo[$section] = [
          'urls' => [],
          'content' => ''
        ];
      }
    }
    
    require "./views/admin/help.php";
  }
  public function post()
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Mặc định là 1 nếu không có tham số 'page'
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 2; // Mặc định là 10 nếu không có tham số 'limit'
    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    if ($search) {
      $data = $this->getSearchPost($search, $page, $limit);
    } else {
      $data = $this->getPost($page, $limit);
    }
    require "./views/admin/post.php";
  }
  public function postEdit() //DONE
  {
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
    $data = $this->getDetailSinger($id);
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true);
    require "./views/admin/help.edit.php";

    $data = $this->getDetailPost($id);
    require "./views/admin/post.edit.php";
  }
  public function postCreate() {//DONE
    if (!$this->Secret()) {
      return $this->loginAdmin();
    }
    $countryJson = $this->getCountry();
    $country = json_decode($countryJson, true);
    require "./views/admin/post.create.php";
  }
}

