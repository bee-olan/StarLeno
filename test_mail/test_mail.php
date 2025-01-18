<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
  // Включаем подробное логирование (2 = server details)
  $mail->SMTPDebug = 2;

  // Настройки сервера
  $mail->isSMTP();
  $mail->Host = 'smtp.beget.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'admin@regtema.ru';
  $mail->Password = 'WOouS1_2PW8';
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->Port = 465;

  // Отправитель и получатель
  $mail->setFrom('admin@regtema.ru', 'Test Sender');
  $mail->addAddress('dima-chen86@mail.ru', 'Test Receiver');

  // Содержимое
  $mail->isHTML(true);
  $mail->CharSet = 'UTF-8';
  $mail->Subject = 'Тестовое письмо ' . date('Y-m-d H:i:s');
  $mail->Body = 'Это <b>тестовое</b> письмо для проверки работы SMTP. Время отправки: ' . date('Y-m-d H:i:s');
  $mail->AltBody = 'Это тестовое письмо для проверки работы SMTP. Время отправки: ' . date('Y-m-d H:i:s');

  $mail->send();
  echo "\nПисьмо успешно отправлено\n";
} catch (Exception $e) {
  echo "\nОшибка при отправке письма: {$mail->ErrorInfo}\n";
}