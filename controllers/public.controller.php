<?php
class PublicController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getPublicPages() {
        try {
            $pages = $this->db->getPublicPages();
            require_once 'views/admin/public/index.php';
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function editPublicPage($id) {
        try {
            $page = $this->db->getPublicPageById($id);
            if (!$page) {
                throw new Exception('Page not found');
            }
            require_once 'views/admin/public/edit.php';
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function updatePublicPage($id) {
        try {
            $data = [
                'title' => $_POST['title'],
                'desc' => $_POST['desc'],
                'type1_desc' => $_POST['type1_desc'],
                'type1_total' => (int)$_POST['type1_total'],
                'type2_desc' => $_POST['type2_desc'],
                'type2_total' => (int)$_POST['type2_total'],
                'type3_desc' => $_POST['type3_desc'],
                'type3_total' => (int)$_POST['type3_total'],
                'title2' => $_POST['title2'],
                'section3' => $_POST['section3']
            ];
            $this->db->updatePublicPage($id, $data);
            header('Location: /admin/public');
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getContactInfo() {
        try {
            $contactInfo = $this->db->getContactInfo();
            require_once 'views/admin/public/contact.php';
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function updateContactInfo() {
        try {
            $data = [
                'address' => $_POST['address'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'facebook' => $_POST['facebook'],
                'twitter' => $_POST['twitter'],
                'instagram' => $_POST['instagram']
            ];
            $this->db->updateContactInfo($data);
            header('Location: /admin/public/contact');
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
} 