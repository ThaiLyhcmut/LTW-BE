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
}

