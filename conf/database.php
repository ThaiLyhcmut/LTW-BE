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
  public function DB_GET_AUTH($email, $password)
  {
    $stmt = $this->conn->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    unset($data['password']);
    return $data;
  }
  public function DB_CHECK_EMAIL_AUTH($email)
  {
    $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
  }
  public function DB_CHECK_DELETE_OTP($email, $otp)
  {
    $stmt = $this->conn->prepare("DELETE FROM otps WHERE email = ? AND otp = ?");
    $stmt->bind_param('ss', $email, $otp);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  }
  public function DB_INSERT_OTP($email, $otp)
  {
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
  public function DB_INSERT_AUTH($username, $email, $password, $country_code, $avatar_url)
  {
    $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, country_code, avatar_url) VALUE (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $password, $country_code, $avatar_url);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return true;
    }
  }
  public function DB_GET_COUNTRIES()
{
    $stmt = $this->conn->prepare("SELECT * FROM countries");
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC); // lấy tất cả dòng, mỗi dòng là 1 mảng assoc
    $stmt->close();
    return $data;
}


  // favorite
  public function DB_INSERT_FAVORITE($user_id, $song_id)
  {
    $stmt = $this->conn->prepare("INSERT INTO favorites (user_id, song_id) VALUE (?, ?)");
    $stmt->bind_param("ii", $user_id, $song_id);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      return false;
    }
  }
  public function DB_GET_COUNT_FAVORITE_SONG($user_id)
  {
    $stmt_total = $this->conn->prepare("SELECT count(*) as total FROM favorites WHERE user_id = ?");
    $stmt_total->bind_param("i", $user_id);
    $stmt_total->execute();
    $result = $stmt_total->get_result();
    $data = $result->fetch_assoc();
    $stmt_total->close();
    return $data['total'];
  }
  public function DB_GET_FAVORITE_SONG($user_id, $offset, $limit)
  {
    $stmt = $this->conn->prepare(
      "
        SELECT s.* 
        FROM songs s
        JOIN favorites f ON s.id = f.song_id
        WHERE f.user_id = ?
        LIMIT ?, ?
      "
    );
    $stmt->bind_param("iii", $user_id, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return [
      "data" => $data,
      "total_page" => ceil($this->DB_GET_COUNT_FAVORITE_SONG($user_id) / $limit)
    ];
  }
  public function DB_DELETE_FAVORITE($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM favorites WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  }
  // comment
  public function DB_INSERT_COMMENT($user_id, $song_id, $content)
  {
    $stmt = $this->conn->prepare("INSERT INTO comments (user_id, song_id, content) VALUE (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $song_id, $content);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      return false;
    }
  }
  public function DB_DELETE_COMMENT($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM comment WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  }
  public function DB_GET_COMMENT($song_id)
  {
    $stmt = $this->conn->prepare("
        SELECT c.id, c.user_id, u.username, c.song_id, c.content, c.created_at
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.song_id = ?
        ORDER BY c.created_at DESC
    ");
    $stmt->bind_param("i", $song_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $data;
  }
  // singer
  public function DB_INSERT_SINGER($name, $country_code, $avatar_url)
  {
    $stmt = $this->conn->prepare("INSERT INTO singers (name, country_code, avatar_url) VALUE (?, ?, ?)");
    $stmt->bind_param('sss', $name, $country_code, $avatar_url);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  }
  public function DB_GET_TOTAL_PAGE_SINGER($country_code, $limit)
  {
    $stmt_total = $this->conn->prepare("SELECT COUNT(*) FROM singers WHERE country_code = ?");
    $stmt_total->bind_param("s", $country_code);
    $stmt_total->execute();
    $stmt_total->bind_result($total_rows);
    $stmt_total->fetch();
    $stmt_total->close();
    return ceil($total_rows / $limit);
  }
  // page = ?, limit = ?
  public function DB_GET_SINGER($country_code, $offset, $limit)
  {
    $stmt = $this->conn->prepare("SELECT * FROM singers WHERE country_code = ? LIMIT $offset, $limit");
    $stmt->bind_param("s", $country_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC); // Lấy tất cả dữ liệu
    $stmt->close();
    return ["data" => $data, "total_page" => $this->DB_GET_TOTAL_PAGE_SINGER($country_code, $limit)];
  }
  public function DB_GET_DETAIL_SINGER($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM singers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
  }
  public function DB_DELETE_SINGER($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM singers WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      return false;
    }
  }
  // fields = ["name", "image"]
  public function DB_UPDATE_SINGER($fields, $values, $types)
  {
    if (empty($fields)) {
      return false;
    }
    $sql = "UPDATE singers SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param($types, ...$values);

    return $stmt->execute();
  }

  // albums
  public function DB_INSERT_ALBUM($title, $singer_id, $release_year, $cover_url)
  {
    $stmt = $this->conn->prepare("INSERT INTO albums (title, singer_id, release_year, cover_url) VALUE (?, ?, ?, ?)");
    $stmt->bind_param("siis", $title, $singer_id, $release_year, $cover_url);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  }
  public function DB_GET_TOTAL_PAGE_SINGER_ALBUM($singer_id, $limit)
  {
    $stmt_total = $this->conn->prepare("SELECT COUNT(*) FROM albums WHERE singer_id = ?");
    $stmt_total->bind_param("s", $singer_id);
    $stmt_total->execute();
    $stmt_total->bind_result($total_rows);
    $stmt_total->fetch();
    $stmt_total->close();
    return ceil($total_rows / $limit);
  }
  public function DB_GET_TOTLE_PAGE_ALBUM($limit)
  {
    $stmt_total = $this->conn->prepare("SELECT COUNT(*) FROM albums");
    $stmt_total->execute();
    $stmt_total->bind_result($total_rows);
    $stmt_total->fetch();
    $stmt_total->close();
    return ceil($total_rows / $limit);
  }
  // page = ?, limit = ?
  public function DB_GET_SINGER_ALBUM($singer_id, $offset, $limit)
  {
    $stmt = $this->conn->prepare("SELECT * FROM albums WHERE singer_id = ? LIMIT $offset, $limit");
    $stmt->bind_param("i", $singer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return [
      'data' => $data,
      'total_page' => $this->DB_GET_TOTAL_PAGE_SINGER_ALBUM($singer_id, $limit)
    ];
  }
  public function DB_GET_ALBUM($offset, $limit)
  {
    $stmt = $this->conn->prepare(
      "
      SELECT a.*, sg.name
      FROM albums a 
      JOIN singers sg ON a.singer_id = sg.id
      LIMIT $offset, $limit
    "
    );
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return [
      'data' => $data,
      'total_page' => $this->DB_GET_TOTLE_PAGE_ALBUM($limit)
    ];
  }
  public function DB_GET_DETAIL_ALBUM($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM albums WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
  }
  public function DB_DELETE_ALBUM($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM albums WHERE id = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      return false;
    }
  }
  // fields = ["title", "release_year"]
  public function DB_UPDATE_ALBUM($fields, $values, $types)
  {
    if (empty($fields)) {
      return false;
    }
    $sql = "UPDATE albums SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param($types, ...$values);

    return $stmt->execute();
  }

  // topics
  public function DB_INSERT_TOPIC($name, $description, $country_code, $image_url)
  {
    $stmt = $this->conn->prepare("INSERT INTO topics (name, description, country_code, image_url) VALUE (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $description, $country_code, $image_url);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  }
  public function DB_GET_TOTAL_PAGE_TOPIC($country_code, $limit)
  {
    $stmt_total = $this->conn->prepare("SELECT COUNT(*) FROM topics WHERE country_code = ?");
    $stmt_total->bind_param("s", $country_code);
    $stmt_total->execute();
    $stmt_total->bind_result($total_rows);
    $stmt_total->fetch();
    $stmt_total->close();
    return ceil($total_rows / $limit);
  }
  // page = ?, limit = ?
  public function DB_GET_TOPIC($country_code, $offset, $limit)
  {
    $stmt = $this->conn->prepare("SELECT * FROM topics WHERE country_code = ? LIMIT $offset, $limit");
    $stmt->bind_param("s", $country_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return [
      'data' => $data,
      'total_page' => $this->DB_GET_TOTAL_PAGE_TOPIC($country_code, $limit)
    ];
  }
  public function DB_GET_DETAIL_TOPIC($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM topics WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
  }
  public function DB_DELETE_TOPIC($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM topics WHERE id = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      return false;
    }
  }
  // fields = ["title", "release_year"]
  public function DB_UPDATE_TOPIC($fields, $values, $types)
  {
    if (empty($fields)) {
      return false;
    }
    $sql = "UPDATE albums SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param($types, ...$values);

    return $stmt->execute();
  }

  // songs
  public function DB_INSERT_SONG($title, $duration, $lyric, $file_url, $cover_url)
  {
    $stmt = $this->conn->prepare("INSERT INTO songs (title, duration, lyric, file_url, cover_url) VALUE (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $title, $duration, $lyric, $file_url, $cover_url);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  }
  public function DB_GET_COUNT_SINGER_SONG($singer_id)
  {
    $stmt_total = $this->conn->prepare("SELECT count(*) as total FROM song_singers WHERE singer_id = ?");
    $stmt_total->bind_param("i", $singer_id);
    $stmt_total->execute();
    $result = $stmt_total->get_result();
    $data = $result->fetch_assoc();
    $stmt_total->close();
    return $data['total'];
  }
  public function DB_GET_COUNT_ALBUM_SONG($album_id)
  {
    $stmt_total = $this->conn->prepare("SELECT count(*) as total FROM song_albums WHERE album_id = ?");
    $stmt_total->bind_param("i", $album_id);
    $stmt_total->execute();
    $result = $stmt_total->get_result();
    $data = $result->fetch_assoc();
    $stmt_total->close();
    return $data['total'];
  }
  public function DB_GET_COUNT_TOPIC_SONG($topic_id)
  {
    $stmt_total = $this->conn->prepare("SELECT count(*) as total FROM song_topics WHERE topic_id = ?");
    $stmt_total->bind_param("i", $topic_id);
    $stmt_total->execute();
    $result = $stmt_total->get_result();
    $data = $result->fetch_assoc();
    $stmt_total->close();
    return $data['total'];
  }
  public function DB_GET_COUNT_SONG()
  {
    $stmt_total = $this->conn->prepare("SELECT count(*) as total FROM songs");
    $stmt_total->execute();
    $result = $stmt_total->get_result();
    $data = $result->fetch_assoc();
    $stmt_total->close();
    return $data['total'];
  }
  // page = ?, limit = ?
  public function DB_GET_SINGER_SONG($singer_id, $offset, $limit)
  {
    $query = "
      SELECT s.*, sg.name
      FROM songs s
      JOIN song_singers ssg ON s.id = ssg.song_id
      JOIN singers sg ON sg.id = ssg.singer_id  
      WHERE ssg.singer_id = ?
      LIMIT ?, ?
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("iii", $singer_id, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return [
      "data" => $data,
      "total_page" => ceil($this->DB_GET_COUNT_SINGER_SONG($singer_id) / $limit)
    ];
  }
  public function DB_GET_ALBUM_SONG($album_id, $offset, $limit)
  {
    $query = "
      SELECT s.*, sg.name
      FROM songs s
      JOIN song_albums al ON s.id = al.song_id
      JOIN albums a ON a.id = al.album_id
      JOIN singers sg ON sg.id = a.singer_id
      WHERE al.album_id = ?
      LIMIT ?, ?
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("iii", $album_id, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return [
      "data" => $data,
      "total_page" => ceil($this->DB_GET_COUNT_ALBUM_SONG($album_id) / $limit)
    ];
  }
  public function DB_GET_TOPIC_SONG($topic_id, $offset, $limit)
  {
    $query = "
      SELECT s.*, sg.name
      FROM songs s
      JOIN song_topics sp ON s.id = sp.song_id
      JOIN song_singers ssg ON s.id = ssg.song_id
      JOIN singers sg ON sg.id = ssg.singer_id
      WHERE sp.topic_id = ?
      LIMIT ?, ?
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("iii", $topic_id, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return [
      "data" => $data,
      "total_page" => ceil($this->DB_GET_COUNT_TOPIC_SONG($topic_id) / $limit)
    ];
  }
  public function DB_GET_SONG($offset, $limit)
  {
    $stmt = $this->conn->prepare(
      "
      SELECT s.*, sg.name 
      FROM songs s 
      JOIN song_singers ssg ON s.id = ssg.song_id
      JOIN singers sg ON sg.id = ssg.singer_id  
      LIMIT ?, ?"
    );
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return [
      "data" => $data,
      "total_page" => ceil($this->DB_GET_COUNT_SONG() / $limit)
    ];
  }
  public function DB_GET_DETAIL_SONG($id)
  {
    $stmt = $this->conn->prepare(
      "
      SELECT s.*, sg.name
      FROM songs s
      JOIN song_singers ssg ON s.id = ssg.song_id
      JOIN singers sg ON sg.id = ssg.singer_id  
      WHERE s.id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
  }
  public function DB_DELETE_SONG($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM songs WHERE id = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      return false;
    }
  }
  // fields = ["title", "release_year"]
  public function DB_UPDATE_SONG($fields, $values, $types)
  {
    if (empty($fields)) {
      return false;
    }
    $sql = "UPDATE songs SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param($types, ...$values);

    return $stmt->execute();
  }
  // vips
  public function DB_INSERT_VIP($text, $description, $discountPercent, $price, $time)
  {
    $stmt = $this->conn->prepare("
      INSERT INTO vips (text,description , discountPercent, price, time) VALUE (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssidi", $text, $description, $discountPercent, $price, $time);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  }
  public function DB_UPDATE_VIP($fields, $values, $types)
  {
    if (empty($fields)) {
      return false;
    }
    $sql = "UPDATE vips SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param($types, ...$values);

    return $stmt->execute();
  }
  public function DB_GET_VIP()
  {
    $stmt = $this->conn->prepare("SELECT * FROM vips");
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $data;
  }
  public function DB_DELETE_VIP($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM vips WHERE id = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      return false;
    }
  }
  // history
  public function DB_UPDATE_VIP_USER($user_id, $time)
  {
    $stmt = $this->conn->prepare("
      UPDATE users 
      SET expired_at = 
          CASE 
              WHEN expired_at IS NULL OR expired_at < NOW() THEN DATE_ADD(NOW(), INTERVAL ? MONTH) 
              ELSE DATE_ADD(expired_at, INTERVAL ? MONTH) 
          END, 
          vip = 1
      WHERE id = ?;
    ");
    $stmt->bind_param("iii", $time, $time, $user_id);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  }
  public function DB_INSERT_HISTORY($user_id, $txhash, $time)
  {
    $stmt = $this->conn->prepare("
      INSERT INTO history (user_id, txhash) VALUE (?, ?)
    ");
    $stmt->bind_param("is", $user_id, $txhash);
    if ($stmt->execute()) {
      $stmt->close();
      return $this->DB_UPDATE_VIP_USER($user_id, $time);
    } else {
      $stmt->close();
      return false;
    }
  }
  public function DB_GET_HISTORY($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM history WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $data;
  }
  public function DB_CREATE_CONTANTS_SONG_ALBUM($song_id, $album_id) {}
  public function DB_CREATE_CONTANTS_SONG_SINGER($song_id, $singer_id) {}
  public function DB_CREATE_CONTANTS_SONG_TOPIC($song_id, $topic_id) {}



  public function DB_INSERT_POST($title, $img, $desc)
  {
    $stmt = $this->conn->prepare("INSERT INTO posts (title, img, `desc`) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $title, $img, $desc);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  }

  // Lấy tổng số trang (dựa trên limit)
  public function DB_GET_TOTAL_PAGE_POST($limit)
  {
    $stmt_total = $this->conn->prepare("SELECT COUNT(*) FROM posts");
    $stmt_total->execute();
    $stmt_total->bind_result($total_rows);
    $stmt_total->fetch();
    $stmt_total->close();
    return ceil($total_rows / $limit);
  }

  // Lấy danh sách post theo phân trang
  public function DB_GET_POST($offset, $limit)
  {
    $stmt = $this->conn->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return ["data" => $data, "total_page" => $this->DB_GET_TOTAL_PAGE_POST($limit)];
  }

  // Lấy chi tiết một bài post theo id
  public function DB_GET_DETAIL_POST($id)
  {
    $stmt = $this->conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
  }

  // Xóa một bài post theo id
  public function DB_DELETE_POST($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      return false;
    }
  }
  //fields = ["name", "image"]
  public function DB_UPDATE_POST($fields, $values, $types)
  {
    if (empty($fields)) {
      return false;
    }
    $sql = "UPDATE post SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param($types, ...$values);

    return $stmt->execute();
  }

  public function DB_GET_ABOUT()
  {
    $stmt = $this->conn->prepare("SELECT * FROM about WHERE id = 1");
    if (!$stmt) {
      die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc(); // chỉ lấy 1 dòng
    $stmt->close();
    $data['section1'] = json_decode($data['section1'], true);
    $data['section2'] = json_decode($data['section2'], true);
    $data['section3'] = json_decode($data['section3'], true);

    return $data;
  }
}
