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
}

global $db;
$db = Database::getInstance()->getConnection();
