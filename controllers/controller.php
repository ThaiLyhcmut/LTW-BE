<?php
require './conf/database.php';
require './vendor/firebase/php-jwt/src/JWT.php';
require './vendor/phpmailer/phpmailer/src/PHPMailer.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Controller
{
  private $instance;
  public function __construct()
  {
    $this->instance = Database::getInstance();
  }
  public function JWTencode($data)
  {
    return JWT::encode($data, envLoaderService::getEnv('JWT_SECRET'), 'HS256');
  }
  public function JWTdecode($jwt)
  {
    return JWT::decode($jwt, new Key(envLoaderService::getEnv('JWT_SECRET'), 'HS256'));
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
    $stmt = $this->instance->getConnection()->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    unset($data['password']);
    $data['token'] = $this->JWTencode($data);
    if ($data) {
      return $this->convert_json($data);
    }
    return $this->convert_json(['message' => 'Login failed']);
  }
  public function getAuth($token)
  {
    $data = $this->JWTdecode($token);
    if ($data) {
      return $this->convert_json($data);
    }
    return $this->convert_json(['message' => 'Auth failed']);
  }
  public function checkEmailAuth($email)
  {
    $stmt = $this->instance->getConnection()->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
  }
  public function checkOtp($email, $otp) {
    $stmt = $this->instance->getConnection()->prepare("DELETE FROM otps WHERE email = ? AND otp = ?");
    $stmt->bind_param('ss', $email, $otp);
    if ($stmt->execute()) {
      $stmt->close();
      return true;
    }else {
      $stmt->close();
      return false;
    }
  }
  public function otpAuth($email)
  {
    $stmt = $this->instance->getConnection()->prepare("INSERT INTO otps (email, otp) VALUE (?, ?)");
    if ($this->checkEmailAuth($email)) {
      return  $this->convert_json(['message' => 'email exits on table']);
    }
    $otp = $this->generateOTP();
    $senMail = $this->checkMail($email, $otp);
    if (!$senMail) {
      return $this->convert_json(['message' => 'Failed to sendMail']);
    }
    $stmt->bind_param("ss", $email, $otp);
    if ($stmt->execute()) {
      $stmt->close();
      return $this->convert_json(['message' => 'OTP saved successfully']);
    } else {
      $stmt->close();
      return $this->convert_json(['message' => 'Failed to save OTP']);
    }
  }
  public function registerAuth($username, $email, $password, $otp) {
    if($this->checkOtp($email, $otp)) {
      $stmt = $this->instance->getConnection()->prepare("INSERT INTO users (username, email, password) VALUE (?, ?, ?)");
      $stmt->bind_param("sss", $username, $email, $password);
      if ($stmt->execute()) {
        $stmt->close();
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
