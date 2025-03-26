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
  public function Upload()
  {
    if (!isset($_FILES["file"]) || $_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
      return "Lỗi: Không có file hoặc file bị lỗi.";
    }

    $file = $_FILES["file"]["tmp_name"];

    try {
      $response = (new UploadApi())->upload($file);
      return "Upload thành công! <br> URL: <a href='" . $response['secure_url'] . "'>" . $response['secure_url'] . "</a>";
    } catch (Exception $e) {
      return "Lỗi upload: " . $e->getMessage();
    }
  }
  public function JWTencode($data)
  {
    return JWT::encode($data, envLoaderService::getEnv('JWT_SECRET'), 'HS256');
  }
  public function JWTdecode($jwt)
  {
    return JWT::decode($jwt, new Key(envLoaderService::getEnv('JWT_SECRET'), 'HS256'));
  }
  public function getAuth($token)
  {
    $data = $this->JWTdecode($token);
    if ($data) {
      return $this->convert_json($data);
    }
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
          <title>Thông báo từ ' .$company_admin. '</title>
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
                    <p style="margin: 0;">&copy; 2025 ' .$company_admin. '. Mọi quyền được bảo lưu.</p>
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
  public function loginAuth($email, $password)
  {
    $data = $this->instance->DB_GET_AUTH($email, $password);
    $data['token'] = $this->JWTencode($data);
    if ($data) {
      return $this->convert_json($data);
    }
    return $this->convert_json(['message' => 'Login failed']);
  }
  public function otpAuth($email)
  {
    if ($this->instance->DB_CHECK_EMAIL_AUTH($email)) {
      return  $this->convert_json(['message' => 'email exits on table']);
    }
    $otp = $this->generateOTP();
    $senMail = $this->checkMail($email, $otp);
    if (!$senMail) {
      return $this->convert_json(['message' => 'Failed to sendMail']);
    }
    $success = $this->instance->DB_INSERT_OTP($email, $otp);
    if ($success) {
      return $this->convert_json(['message' => 'OTP saved successfully']);
    } else {
      return $this->convert_json(['message' => 'Failed to save OTP']);
    }
  }
  public function registerAuth($username, $email, $password, $country_code, $avatar_url, $otp) {
    if($this->instance->DB_CHECK_DELETE_OTP($email, $otp)) {
      $success = $this->instance->DB_INSERT_AUTH($username, $email, $password, $country_code, $avatar_url);
      if ($success) {
        return $this->loginAuth($email, $password);
      }
      else{
        return $this->convert_json(['message' => 'Failed to save auth']);
      }
    }else {
      return $this->convert_json(['message' => 'Failed to delete otp']);
    }
  }
}
