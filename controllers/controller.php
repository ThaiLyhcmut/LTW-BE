<?php
require './conf/database.php';
require './vendor/firebase/php-jwt/src/JWT.php';
require './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './vendor/cloudinary/cloudinary_php/src/Configuration/Configuration.php';
require './vendor/cloudinary/cloudinary_php/src/Api/Upload/UploadApi.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;


class Controller
{
  private $instance;
  public function __construct()
  {
    $this->instance = Database::getInstance();
    Configuration::instance([
      'cloud' => [
        'cloud_name' => envLoaderService::getEnv("CLOUD_NAME"),
        'api_key'    => envLoaderService::getEnv("API_KEY"),
        'api_secret' => envLoaderService::getEnv("API_SECRET")
      ],
      'url' => [
        'secure' => true // Bật HTTPS
      ]
    ]);
  }
  // cloudinary
  public function Upload()
  {
    if (!isset($_FILES["file"]) || $_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
      return false;
    }

    $file = $_FILES["file"]["tmp_name"];

    try {
      $response = (new UploadApi())->upload($file);
      return $response['secure_url'];
    } catch (Exception $e) {
      return false;
    }
  }
  public function UploadAudio()
  {
    if (!isset($_FILES["fileAudio"])) {
      die("Error: No file uploaded!");
    }

    if ($_FILES["fileAudio"]["error"] !== UPLOAD_ERR_OK) {
      die("File upload error: " . $_FILES["fileAudio"]["error"]);
    }

    var_dump($_FILES["fileAudio"]);

    $file = $_FILES["fileAudio"]["tmp_name"];

    try {
      $response = (new UploadApi())->upload($file, [
        "resource_type" => "video",
        "format" => pathinfo($_FILES["fileAudio"]["name"], PATHINFO_EXTENSION) // Giữ nguyên định dạng gốc
      ]);
      return $response['secure_url'];
    } catch (Exception $e) {
      die("Cloudinary upload error: " . $e->getMessage());
    }
  }

  // JWT
  public function JWTencode($data)
  {
    return JWT::encode($data, envLoaderService::getEnv('JWT_SECRET'), 'HS256');
  }
  public function JWTdecode($jwt)
  {
    return JWT::decode($jwt, new Key(envLoaderService::getEnv('JWT_SECRET'), 'HS256'));
  }
  // helper
  public function getAuth($token)
  {
    $data = $this->JWTdecode($token);
    if ($data) {
      return $this->convert_json($data);
    }
    http_response_code(400);
    return $this->convert_json(['message' => 'Auth failed']);
  }
  public function generateOTP()
  {
    return (string) random_int(100000, 999999);
  }
  public function checkMail($email, $otp)
  {
    $email_admin = envLoaderService::getEnv("EMAIL_ADMIN");
    $passw_admin = envLoaderService::getEnv("PASSW_ADMIN");
    $host_admin = envLoaderService::getEnv("HOST_ADMIN");
    $company_admin = envLoaderService::getEnv("COMPANY_ADMIN");
    $port_admin = (int) envLoaderService::getEnv("PORT_ADMIN");
    $mail = new PHPMailer(true);
    try {

      $mail->isSMTP();
      $mail->Host = $host_admin;
      $mail->SMTPAuth = true;
      $mail->Username = $email_admin;
      $mail->Password = $passw_admin;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = $port_admin;
      $mail->setFrom($email_admin, $company_admin);
      $mail->addAddress($email);
      $mail->isHTML(true);
      $mail->Subject = "📩 [Xác nhận] Đăng ký tài khoản thành công - " . $company_admin;
      $mail->CharSet = 'UTF-8';
      $mail->Encoding = 'base64';
      $mail->Body = '<!DOCTYPE html>
      <html lang="vi">
      <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Thông báo từ ' . $company_admin . '</title>
      </head>
      <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
        <table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f4f4; padding: 20px 0;">
          <tr>
            <td align="center">
              <table role="presentation" border="0" width="600px" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; padding: 20px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                
                <!-- HEADER -->
                <tr>
                  <td align="center" style="padding: 20px; background-color: #007BFF; color: #ffffff; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                    <h1 style="margin: 0; font-size: 24px;">📢 Thông báo từ ' . $company_admin . '</h1>
                  </td>
                </tr>
      
                <!-- CONTENT -->
                <tr>
                  <td align="center" style="padding-bottom: 20px;">
                    <h2 style="color: #333; margin: 20px 0;">🎉 Chào mừng bạn đến với <span style="color: #007BFF;">ThaiLyMusic</span>!</h2>
                  </td>
                </tr>
                <tr>
                  <td align="center" style="padding: 10px 20px;">
                    <p style="color: #555; font-size: 16px;">Cảm ơn bạn đã đăng ký tài khoản tại <strong>ThaiLyMusic</strong>! Vui lòng xác nhận email của bạn để hoàn tất quá trình đăng ký.</p>
                    <p style="color: #000; font-size: 20px; font-weight: bold; background-color: #f0f0f0; display: inline-block; padding: 10px 20px; border-radius: 5px;">
                      Mã OTP của bạn: <strong>' . $otp . '</strong>
                    </p>
                  </td>
                </tr>
      
                <!-- FOOTER -->
                <tr>
                  <td align="center" style="padding-top: 20px; color: #999; font-size: 14px;">
                    <p>Trân trọng,</p>
                  </td>
                </tr>
                <tr>
                  <td align="center" style="padding-top: 10px; font-size: 12px; color: #777;">
                    <p style="margin: 0;">Nếu bạn không đăng ký tài khoản, vui lòng bỏ qua email này.</p>
                    <p style="margin: 0;">&copy; 2025 ' . $company_admin . '. Mọi quyền được bảo lưu.</p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </body>
      </html>';
      $mail->send();
      return true;
    } catch (Exception $e) {
      return false;
    }
  }
  public function getBody()
  {
    $input = file_get_contents("php://input");
    return json_decode($input, true);
  }
  public function getFormData()
  {
    $data = [];
    foreach ($_POST as $key => $value) {
      $data[$key] = $value;
    }
    return $data;
  }
  public function getQueryParam($key, $default = null)
  {
    return isset($_GET[$key]) ? $_GET[$key] : $default;
  }
  public function getBearerToken()
  {
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
      $matches = [];
      if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
        return $matches[1]; // Trả về token sau 'Bearer '
      }
    }
    return null;
  }
  public function convert_json_from_array($value)
  {
    return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }
  public function convert_json($value)
  {
    return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  }
  // auth
  public function loginAuth($email, $password)
  {
    $data = $this->instance->DB_GET_AUTH($email, $password);
    $data['token'] = $this->JWTencode($data);
    if ($data) {
      return $this->convert_json($data);
    }
    http_response_code(400);
    return $this->convert_json(['message' => 'Login failed']);
  }
  public function otpAuth($email)
  {
    if ($this->instance->DB_CHECK_EMAIL_AUTH($email)) {
      http_response_code(400);
      return  $this->convert_json(['message' => 'email exits on table']);
    }
    $otp = $this->generateOTP();
    $senMail = $this->checkMail($email, $otp);
    if (!$senMail) {
      http_response_code(400);
      return $this->convert_json(['message' => 'Failed to sendMail']);
    }
    $success = $this->instance->DB_INSERT_OTP($email, $otp);
    if ($success) {
      return $this->convert_json(['message' => 'OTP saved successfully']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Failed to save OTP']);
    }
  }
  public function registerAuth($username, $email, $password, $country_code, $avatar_url, $otp)
  {
    if ($this->instance->DB_CHECK_DELETE_OTP($email, $otp)) {
      $success = $this->instance->DB_INSERT_AUTH($username, $email, $password, $country_code, $avatar_url);
      if ($success) {
        return $this->loginAuth($email, $password);
      } else {
        http_response_code(400);
        return $this->convert_json(['message' => 'Failed to save auth']);
      }
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Failed to delete otp']);
    }
  }
  public function country()
  {
    return $this->instance->DB_GET_COUNTRY();
  }

  // favorite
  public function createFavorite($user_id, $song_id)
  {
    $access = $this->instance->DB_INSERT_FAVORITE($user_id, $song_id);
    if ($access) {
      return $this->convert_json(['messgae' => 'Create favorite completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Create favorite faild']);
    }
  }
  public function getFavorite($user_id, $page, $limit)
  {
    $offset = ($page - 1) * $limit;
    $data = $this->instance->DB_GET_FAVORITE_SONG($user_id,  $offset, $limit);
    if ($data) {
      return $this->convert_json_from_array($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get song faild of favorite']);
    }
  }
  public function deleteFavorite($id)
  {
    $access = $this->instance->DB_DELETE_FAVORITE($id);
    if ($access) {
      return $this->convert_json(['message' => 'Delete favorite complete']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Delete favorite faild']);
    }
  }
  // comment
  public function createComment($user_id, $song_id, $content)
  {
    $access = $this->instance->DB_INSERT_COMMENT($user_id, $song_id, $content);
    if ($access) {
      return $this->convert_json(['messgae' => 'Create comment completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Create comment faild']);
    }
  }
  public function deleteComment($id)
  {
    $access = $this->instance->DB_DELETE_COMMENT($id);
    if ($access) {
      return $this->convert_json(['message' => 'Delete comment complete']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Delete comment faild']);
    }
  }
  public function getComment($song_id)
  {
    $data = $this->instance->DB_GET_COMMENT($song_id);
    if ($data) {
      return $this->convert_json_from_array($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get singer faild']);
    }
  }
  // singer
  public function createSinger($name, $country_code, $avatar_url)
  {
    $access = $this->instance->DB_INSERT_SINGER($name, $country_code, $avatar_url);
    if ($access) {
      return $this->convert_json(['messgae' => 'Create singer completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Create singer faild']);
    }
  }

  public function editSinger($fields, $values, $types)
  {
    if (empty($fields)) {
      http_response_code(400);
      return $this->convert_json(['message' => "data invalid"]);
    }
    if ($this->instance->DB_UPDATE_SINGER($fields, $values, $types)) {
      return $this->convert_json(['message' => 'Edit singer completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Edit singer error']);
    }
  }
  public function getSinger($country_code, $page, $limit)
  {
    $offset = ($page - 1) * $limit;
    $data = $this->instance->DB_GET_SINGER($country_code, $offset, $limit);
    if ($data) {
      return $this->convert_json_from_array($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get singer faild']);
    }
  }
  public function getDetailSinger($id) {
    $data = $this->instance->DB_GET_DETAIL_SINGER($id);
    if ($data) {
      return $this->convert_json($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get detail singer faild']);
    }
  }
  public function deleteSinger($id)
  {
    $access = $this->instance->DB_DELETE_SINGER($id);
    if ($access) {
      return $this->convert_json(['message' => 'Delete singer complete']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Delete singer faild']);
    }
  }


  // album
  public function createAlbum($title, $singer_id, $release_year, $cover_url)
  {
    $access = $this->instance->DB_INSERT_ALBUM($title, $singer_id, $release_year, $cover_url);
    if ($access) {
      return $this->convert_json(['messgae' => 'Create album completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Create album faild']);
    }
  }

  public function editAlbum($fields, $values, $types)
  {
    if (empty($fields)) {
      http_response_code(400);
      return $this->convert_json(['message' => "data invalid"]);
    }
    if ($this->instance->DB_UPDATE_ALBUM($fields, $values, $types)) {
      return $this->convert_json(['message' => 'Edit album completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Edit album error']);
    }
  }
  public function getSingerAlbum($singer_id, $page, $limit)
  {
    $offset = ($page - 1) * $limit;
    $data = $this->instance->DB_GET_SINGER_ALBUM($singer_id, $offset, $limit);
    if ($data) {
      return $this->convert_json_from_array($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get album faild']);
    }
  }
  public function getAlbum($page, $limit)
  {
    $offset = ($page - 1) * $limit;
    $data = $this->instance->DB_GET_ALBUM($offset, $limit);
    if ($data) {
      return $this->convert_json_from_array($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get album faild']);
    }
  }
  public function getDetailAlbum($id) {
    $data = $this->instance->DB_GET_DETAIL_ALBUM($id);
    if ($data) {
      return $this->convert_json($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get detail album faild']);
    }
  }
  public function deleteAlbum($id)
  {
    $access = $this->instance->DB_DELETE_ALBUM($id);
    if ($access) {
      return $this->convert_json(['message' => 'Delete album complete']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Delete album faild']);
    }
  }

  // topics
  public function createTopic($name, $description, $country_code, $image_url)
  {
    $access = $this->instance->DB_INSERT_TOPIC($name, $description, $country_code, $image_url);
    if ($access) {
      return $this->convert_json(['messgae' => 'Create topic completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Create topic faild']);
    }
  }

  public function editTopic($fields, $values, $types)
  {
    if (empty($fields)) {
      http_response_code(400);
      return $this->convert_json(['message' => "data invalid"]);
    }
    if ($this->instance->DB_UPDATE_ALBUM($fields, $values, $types)) {
      return $this->convert_json(['message' => 'Edit topic completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Edit topic error']);
    }
  }
  public function getTopic($country_code, $page, $limit)
  {
    $offset = ($page - 1) * $limit;
    $data = $this->instance->DB_GET_TOPIC($country_code, $offset, $limit);
    if ($data) {
      return $this->convert_json_from_array($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get topic faild']);
    }
  }
  public function getDetailTopic($id) {
    $data = $this->instance->DB_GET_DETAIL_TOPIC($id);
    if ($data) {
      return $this->convert_json($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get detail topic faild']);
    }
  }
  public function deleteTopic($id)
  {
    $access = $this->instance->DB_DELETE_TOPIC($id);
    if ($access) {
      return $this->convert_json(['message' => 'Delete topic complete']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Delete topic faild']);
    }
  }


  // songs
  public function createSong($title, $duration, $lyric, $file_url, $cover_url)
  {
    $access = $this->instance->DB_INSERT_SONG($title, $duration, $lyric, $file_url, $cover_url);
    if ($access) {
      return $this->convert_json(['messgae' => 'Create song completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Create song faild']);
    }
  }
  public function editSong($fields, $values, $types)
  {
    if (empty($fields)) {
      http_response_code(400);
      return $this->convert_json(['message' => "data invalid"]);
    }
    if ($this->instance->DB_UPDATE_SONG($fields, $values, $types)) {
      return $this->convert_json(['message' => 'Edit song completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Edit song error']);
    }
  }
  public function getSingerSong($singer_id, $page, $limit)
  {
    $offset = ($page - 1) * $limit;
    $data = $this->instance->DB_GET_SINGER_SONG($singer_id, $offset, $limit);
    if ($data) {
      return $this->convert_json_from_array($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get song faild of singer']);
    }
  }
  public function getAlbumSong($album_id, $page, $limit)
  {
    $offset = ($page - 1) * $limit;
    $data = $this->instance->DB_GET_ALBUM_SONG($album_id, $offset, $limit);
    if ($data) {
      return $this->convert_json_from_array($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get song faild of album']);
    }
  }
  public function getTopicSong($topic_id, $page, $limit)
  {
    $offset = ($page - 1) * $limit;
    $data = $this->instance->DB_GET_TOPIC_SONG($topic_id, $offset, $limit);
    if ($data) {
      return $this->convert_json_from_array($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get song faild of topic']);
    }
  }
  public function getSong($page, $limit) {
    $offset = ($page - 1) * $limit;
    $data = $this->instance->DB_GET_SONG($offset, $limit);
    if ($data) {
      return $this->convert_json_from_array($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get song faild']);
    }
  }
  public function getDetailSong($id) {
    $data = $this->instance->DB_GET_DETAIL_SONG($id);
    if ($data) {
      return $this->convert_json($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get song faild']);
    }
  }
  public function deleteSong($id)
  {
    $access = $this->instance->DB_DELETE_SONG($id);
    if ($access) {
      return $this->convert_json(['message' => 'Delete song complete']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Delete song faild']);
    }
  }


  public function createVip($text, $description, $discountPercent, $price, $time) {
    $access = $this->instance->DB_INSERT_VIP($text, $description, $discountPercent, $price, $time);
    if ($access) {
      return $this->convert_json(['messgae' => 'Create vip completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Create vip faild']);
    }
  }
  public function getVip() {
    $data = $this->instance->DB_GET_VIP();
    if ($data) {
      return $this->convert_json_from_array($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get vip faild']);
    }
  }
  public function deleteVip($id) {
    $access = $this->instance->DB_DELETE_VIP($id);
    if ($access) {
      return $this->convert_json(['message' => 'Delete vip complete']);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Delete vip faild']);
    }
  }
  public function ediVip($fields, $values, $types)
  {
    if (empty($fields)) {
      http_response_code(400);
      return $this->convert_json(['message' => "data invalid"]);
    }
    if ($this->instance->DB_UPDATE_VIP($fields, $values, $types)) {
      return $this->convert_json(['message' => 'Edit vip completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Edit vip error']);
    }
  }


  public function createHistory($user_id, $txhash, $time) {
    $access = $this->instance->DB_INSERT_HISTORY($user_id, $txhash, $time);
    if ($access) {
      return $this->convert_json(['messgae' => 'Create history completed']);
    } else {
      http_response_code(400);
      return $this->convert_json(['message' => 'Create history faild']);
    }
  }
  public function getHistory($id) {
    $data = $this->instance->DB_GET_HISTORY($id);
    if ($data) {
      return $this->convert_json_from_array($data);
    } else {
      http_response_code(400);
      return $this->convert_json(['messgae' => 'Get vip faild']);
    }
  }
}
