<?php
class InfoController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getInfo() {
        try {
            // Lấy thông tin thống kê
            $totalSongs = $this->getTotalSongs();
            $totalSingers = $this->getTotalSingers();
            $totalAlbums = $this->getTotalAlbums();
            $totalUsers = $this->getTotalUsers();

            // Tính thời gian hoạt động
            $startTime = strtotime('2024-01-01'); // Thay đổi thành thời gian thực tế
            $uptime = $this->formatUptime(time() - $startTime);

            // Lấy thông tin người dùng nếu đã đăng nhập
            $userInfo = null;
            if (isset($_SESSION['user_id'])) {
                $userInfo = $this->getUserInfo($_SESSION['user_id']);
            }

            // Trả về view với dữ liệu
            require_once 'views/admin/info.php';
        } catch (Exception $e) {
            // Xử lý lỗi
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function getTotalSongs() {
        return $this->db->DB_GET_COUNT_SONG();
    }

    private function getTotalSingers() {
        $stmt = $this->db->getConnection()->prepare("SELECT COUNT(*) as total FROM singers");
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data['total'];
    }

    private function getTotalAlbums() {
        $stmt = $this->db->getConnection()->prepare("SELECT COUNT(*) as total FROM albums");
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data['total'];
    }

    private function getTotalUsers() {
        $stmt = $this->db->getConnection()->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data['total'];
    }

    private function getUserInfo($userId) {
        $stmt = $this->db->getConnection()->prepare("SELECT username, email, role, created_at as joined_date FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }

    private function formatUptime($seconds) {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        return sprintf("%d days, %d hours, %d minutes", $days, $hours, $minutes);
    }
} 