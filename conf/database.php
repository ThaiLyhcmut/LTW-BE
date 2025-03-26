<?php
require_once 'envLoaderService.php';


class Database
{
  private static $instance = null;
  private $conn;

  private $servername;
  private $username;
  private $password;
  private $dbname;

  // Constructor riêng tư để ngăn chặn việc khởi tạo trực tiếp
  private function __construct()
  {
    envLoaderService::loadEnv();
    $this->servername = envLoaderService::getEnv('DB_SERVERNAME');
    $this->username = envLoaderService::getEnv('DB_USERNAME');
    $this->password = envLoaderService::getEnv('DB_PASSWORD');
    $this->dbname = envLoaderService::getEnv('DB_NAME');

    try {
      if (!extension_loaded('mysqli')) {
        throw new Exception('The MySQLi extension is not available.');
      }
      $this->conn = new mysqli();
      $this->conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
      $this->conn->real_connect($this->servername, $this->username, $this->password, $this->dbname);

      if (!$this->conn->set_charset("utf8mb4")) {
        throw new Exception("Không thể thiết lập mã hóa UTF-8: " . $this->conn->error);
      }
    } catch (Exception $e) {
      throw new Exception("Không thể kết nối DB: " . $e->getMessage());
    }

    if ($this->conn->connect_error) {
      die("Kết nối thất bại: " . $this->conn->connect_error);
    }
  }

  // Phương thức Singleton để lấy thể hiện của Database
  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new Database();
    }
    return self::$instance;
  }

  public function getConnection()
  {
    return $this->conn;
  }

  private function __clone() {}
  public function __wakeup() {}
  // auth
  public function DB_GET_AUTH($email, $password) {
    $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    unset($data['password']);
    return $data;
  }
  public function DB_CHECK_EMAIL_AUTH($email) {
    $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
  }
  public function DB_CHECK_DELETE_OTP($email, $otp) {
    $stmt = $this->conn->prepare("DELETE FROM otps WHERE email = ? AND otp = ?");
    $stmt->bind_param('ss', $email, $otp);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    }else {
      $stmt->close();
      return false;
    }
  }
  public function DB_INSERT_OTP($email, $otp) {
    $stmt = $this->conn->prepare("INSERT INTO otps (email, otp) VALUE (?, ?)");
    $stmt->bind_param("ss", $email, $otp);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  }
  public function DB_INSERT_AUTH($username, $email, $password, $country_code, $avatar_url) {
    $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, country_code, avatar_url) VALUE (?, ?, ?, ?, ?)");
      $stmt->bind_param("sssss", $username, $email, $password, $country_code, $avatar_url);
      if ($stmt->execute()) {
        $stmt->close();
        return true;
      }
      else{
        $stmt->close();
        return true;
      }
  }
  public function DB_GET_COUNTRY() {
    $stmt = $this->conn->prepare("SELECT * FROM countries");
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
  }

  // singer
  public function DB_INSERT_SINGER($name, $country_code, $avatar_url){
    $stmt = $this->conn->prepare("INSERT INTO singers (name, country_code, avatar_url) VALUE (?, ?, ?)");
    $stmt->bind_param('sss', $name, $country_code, $avatar_url);
    if($stmt->execute()) {
      $stmt->close();
      return true;
    }
    else {
      $stmt->close();
      return false;
    }
  }
  public function DB_GET_TOTAL_PAGE_SINGER($country_code, $limit) {
    $stmt_total = $this->conn->prepare("SELECT COUNT(*) FROM singers WHERE country_code = ?");
    $stmt_total->bind_param("s", $country_code);
    $stmt_total->execute();
    $stmt_total->bind_result($total_rows);
    $stmt_total->fetch();
    $stmt_total->close();
    return ceil($total_rows / $limit);
  }
  // page = ?, limit = ?
  public function DB_GET_SINGER($country_code, $offset, $limit) {
    $stmt = $this->conn->prepare("SELECT * FROM singers WHERE country_code = ? LIMIT $offset, $limit");
    $stmt->bind_param("s", $country_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC); // Lấy tất cả dữ liệu
    $stmt->close();
    return ["data"=> $data, "total_page"=> $this->DB_GET_TOTAL_PAGE_SINGER($country_code, $limit)];
  }

  public function DB_DELETE_SINGER($id) {
    $stmt = $this->conn->prepare("DELETE FROM singers WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()){
      $stmt->close();
      return true;
    }
    else{
      return false;
    }
  }
  // fields = ["name", "image"]
  public function DB_UPDATE_SINGER($fields, $values, $types) {
    if (empty($fields)) {
      return false;
    }
    $sql = "UPDATE singers SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param($types, ...$values);

    return $stmt->execute();
  }

  // albums
  public function DB_INSERT_ALBUM($title, $singer_id, $release_year, $cover_url) {
    $stmt = $this->conn->prepare("INSERT INTO albums (title, singer_id, release_year, cover_url) VALUE (?, ?, ?, ?)");
    $stmt->bind_param("siis", $title, $singer_id, $release_year, $cover_url);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    }else {
      $stmt->close();
      return false;
    }
  }
  public function DB_GET_TOTAL_PAGE_ALBUM($singer_id, $limit) {
    $stmt_total = $this->conn->prepare("SELECT COUNT(*) FROM albums WHERE singer_id = ?");
    $stmt_total->bind_param("s", $singer_id);
    $stmt_total->execute();
    $stmt_total->bind_result($total_rows);
    $stmt_total->fetch();
    $stmt_total->close();
    return ceil($total_rows / $limit);
  }
  // page = ?, limit = ?
  public function DB_GET_ALBUM($singer_id, $offset, $limit) {
    $stmt = $this->conn->prepare("SELECT * FROM albums WHERE singer_id = ? LIMIT $offset, $limit");
    $stmt->bind_param("i", $singer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return [
      'data'=> $data,
      'total_page' => $this->DB_GET_TOTAL_PAGE_ALBUM($singer_id, $limit)
    ];
  }
  public function DB_DELETE_ALBUM($id) {
    $stmt = $this->conn->prepare("DELETE FROM albums WHERE id = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()){
      $stmt->close();
      return true;
    }else {
      return false;
    }
  }
   // fields = ["title", "release_year"]
  public function DB_UPDATE_ALBUM($fields, $values, $types) {
    if (empty($fields)) {
      return false;
    }
    $sql = "UPDATE albums SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param($types, ...$values);

    return $stmt->execute();
  }

  // topics
  public function DB_INSERT_TOPIC($name, $description, $country_code, $image_url) {
    $stmt = $this->conn->prepare("INSERT INTO topics (name, description, country_code, image_url) VALUE (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $description, $country_code, $image_url);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    }else {
      $stmt->close();
      return false;
    }
  }
  public function DB_GET_TOTAL_PAGE_TOPIC($country_code, $limit) {
    $stmt_total = $this->conn->prepare("SELECT COUNT(*) FROM topics WHERE country_code = ?");
    $stmt_total->bind_param("s", $country_code);
    $stmt_total->execute();
    $stmt_total->bind_result($total_rows);
    $stmt_total->fetch();
    $stmt_total->close();
    return ceil($total_rows / $limit);
  }
  // page = ?, limit = ?
  public function DB_GET_TOPIC($country_code, $offset, $limit) {
    $stmt = $this->conn->prepare("SELECT * FROM topics WHERE country_code = ? LIMIT $offset, $limit");
    $stmt->bind_param("s", $country_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return [
      'data'=> $data,
      'total_page' => $this->DB_GET_TOTAL_PAGE_TOPIC($country_code, $limit)
    ];
  }
  public function DB_DELETE_TOPIC($id) {
    $stmt = $this->conn->prepare("DELETE FROM topics WHERE id = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()){
      $stmt->close();
      return true;
    }else {
      return false;
    }
  }
   // fields = ["title", "release_year"]
  public function DB_UPDATE_TOPIC($fields, $values, $types) {
    if (empty($fields)) {
      return false;
    }
    $sql = "UPDATE albums SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param($types, ...$values);

    return $stmt->execute();
  }

  // songs
  public function DB_INSERT_SONG($title, $duration, $lyric, $file_url, $cover_url) {
    $stmt = $this->conn->prepare("INSERT INTO songs (title, duration, lyric, file_url, cover_url) VALUE (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $title, $duration, $lyric, $file_url, $cover_url);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    }else {
      $stmt->close();
      return false;
    }
  }
  public function DB_GET_ARRAY_ID_SINGER_SONG($singer_id) {
    $stmt_total = $this->conn->prepare("SELECT * FROM song_singers WHERE singer_id = ?");
    $stmt_total->bind_param("i", $singer_id);
    $stmt_total->execute();
    $result = $stmt_total->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt_total->close();
    return array_map(fn($row) => $row['song_id'], $data);
  }
  public function DB_GET_ARRAY_ID_ALBUM_SONG($album_id) {
    $stmt_total = $this->conn->prepare("SELECT * FROM song_albums WHERE album_id = ?");
    $stmt_total->bind_param("i", $album_id);
    $stmt_total->execute();
    $result = $stmt_total->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt_total->close();
    return array_map(fn($row) => $row['song_id'], $data);
  }
  public function DB_GET_ARRAY_ID_TOPIC_SONG($topic_id) {
    $stmt_total = $this->conn->prepare("SELECT * FROM song_topics WHERE topic_id = ?");
    $stmt_total->bind_param("i", $topic_id);
    $stmt_total->execute();
    $result = $stmt_total->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt_total->close();
    return array_map(fn($row) => $row['song_id'], $data);
  }
  // page = ?, limit = ?
  public function DB_GET_SINGER_SONG($singer_id, $offset, $limit) {
    $song_ids = $this->DB_GET_ARRAY_ID_SINGER_SONG($singer_id);
    if (empty($song_ids)) {
      return [];
    }
    $ids_string = implode(",", array_map('intval', $song_ids));
    $query = "SELECT * FROM songs WHERE id IN ($ids_string) LIMIT ?, ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return [
      "data" => $data,
      "total_page" => ceil(count($song_ids)/$limit)
    ];
  }
  public function DB_GET_ALBUM_SONG($album_id, $offset, $limit) {
    $song_ids = $this->DB_GET_ARRAY_ID_ALBUM_SONG($album_id);
    if (empty($song_ids)) {
      return [];
    }
    $ids_string = implode(",", array_map('intval', $song_ids));
    $query = "SELECT * FROM songs WHERE id IN ($ids_string) LIMIT ?, ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return [
      "data" => $data,
      "total_page" => ceil(count($song_ids)/$limit)
    ];
  }
  public function DB_GET_TOPIC_SONG($topic_id, $offset, $limit) {
    $song_ids = $this->DB_GET_ARRAY_ID_TOPIC_SONG($topic_id);
    if (empty($song_ids)) {
      return [];
    }
    $ids_string = implode(",", array_map('intval', $song_ids));
    $query = "SELECT * FROM songs WHERE id IN ($ids_string) LIMIT ?, ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return [
      "data" => $data,
      "total_page" => ceil(count($song_ids)/$limit)
    ];
  }
  public function DB_DELETE_SONG($id) {
    $stmt = $this->conn->prepare("DELETE FROM songs WHERE id = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()){
      $stmt->close();
      return true;
    }else {
      return false;
    }
  }
   // fields = ["title", "release_year"]
  public function DB_UPDATE_SONG($fields, $values, $types) {
    if (empty($fields)) {
      return false;
    }
    $sql = "UPDATE songs SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param($types, ...$values);

    return $stmt->execute();
  }
}

