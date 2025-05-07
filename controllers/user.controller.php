<?php
require_once 'conf/database.php';

class UserController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $users = $this->db->getUsers($page, $limit);
        $totalUsers = $this->db->getTotalUsers();
        $totalPages = ceil($totalUsers / $limit);
        
        require_once 'views/admin/users/index.php';
    }

    public function create() {
        $countries = $this->db->DB_GET_COUNTRIES();
        require_once 'views/admin/users/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $role = $_POST['role'] ?? 'user';
            $country_code = $_POST['country_code'] ?? '';
            $avatar_url = $_POST['avatar_url'] ?? '';
            $status = $_POST['status'] ?? 'active';

            // Validate input
            if (empty($username) || empty($email) || empty($password) || empty($country_code)) {
                $_SESSION['error'] = "All required fields must be filled out.";
                header('Location: /admin/users/create');
                exit;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = "Passwords do not match.";
                header('Location: /admin/users/create');
                exit;
            }

            // Check if email already exists
            if ($this->db->DB_CHECK_EMAIL_AUTH($email)) {
                $_SESSION['error'] = "Email already exists.";
                header('Location: /admin/users/create');
                exit;
            }

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Create user
            $result = $this->db->DB_INSERT_AUTH($username, $email, $hashedPassword, $country_code, $avatar_url);

            if ($result) {
                $_SESSION['success'] = "User created successfully.";
                header('Location: /admin/users');
            } else {
                $_SESSION['error'] = "Failed to create user.";
                header('Location: /admin/users/create');
            }
            exit;
        }
    }

    public function edit($id) {
        try {
            $user = $this->db->getUserById($id);
            if (!$user) {
                throw new Exception('User not found');
            }
            $countries = $this->db->DB_GET_COUNTRIES();
            require_once 'views/admin/users/edit.php';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /admin/users');
            exit;
        }
    }

    public function update($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            // Debug log
            error_log("Update user request - POST data: " . print_r($_POST, true));

            // Validate required fields
            $requiredFields = ['username', 'email', 'role', 'status', 'country_code'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Field '$field' is required");
                }
            }

            // Validate role
            if (!in_array($_POST['role'], ['client', 'admin'])) {
                throw new Exception("Invalid role value");
            }

            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'role' => trim($_POST['role']),
                'status' => trim($_POST['status']),
                'country_code' => trim($_POST['country_code']),
                'vip' => isset($_POST['vip']) ? 1 : 0,
                'avatar_url' => trim($_POST['avatar_url'] ?? '')
            ];

            // Debug log
            error_log("Update user data: " . print_r($data, true));

            // If new password is provided, add it to the data
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            $result = $this->db->updateUser($id, $data);
            if (!$result) {
                throw new Exception('Failed to update user. Please check the error logs for details.');
            }

            $_SESSION['success'] = "User updated successfully.";
            header('Location: /admin/users');
        } catch (Exception $e) {
            error_log("Error in update user: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header('Location: /admin/users/edit/' . $id);
        }
        exit;
    }

    public function ban($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $result = $this->db->banUser($id);
            if (!$result) {
                throw new Exception('Failed to ban user');
            }

            $_SESSION['success'] = "User banned successfully.";
            header('Location: /admin/users');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /admin/users');
        }
        exit;
    }

    public function delete($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $result = $this->db->deleteUser($id);
            if (!$result) {
                throw new Exception('Failed to delete user');
            }

            $_SESSION['success'] = "User deleted successfully.";
            header('Location: /admin/users');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /admin/users');
        }
        exit;
    }
} 