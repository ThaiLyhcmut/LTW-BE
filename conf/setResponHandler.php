<?php


set_exception_handler(function ($exception) {
  http_response_code(500);
  echo json_encode([
      'date' => date('Y-m-d H:i:s'),
      'code' => "500",
      'message' => $exception->getMessage(),
      'file' => $exception->getFile(),
      'line' => $exception->getLine()
  ]);
});

// // Cấu hình bắt lỗi PHP Warning, Notice
set_error_handler(function ($severity, $message, $file, $line) {
  http_response_code(500);
  echo json_encode([
      'date' => date('Y-m-d H:i:s'),
      'code' => "500",
      'message' => $message,
      'file' => $file,
      'line' => $line
  ]);
  exit();
});
?>