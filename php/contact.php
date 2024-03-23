<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;

// Include the PHPMailer library
require 'php/PHPMailer-master/src/PHPMailer.php';
require 'php/PHPMailer-master/src/SMTP.php';
require 'php/PHPMailer-master/src/OAuth.php';
require 'php/PHPMailer-master/src/Exception.php';

// Check if required fields are set
if (isset($_POST["name"], $_POST["email"], $_POST["subject"], $_POST["message"])) {

  // Sanitize the input
  $name = strip_tags($_POST["name"]);
  $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
  $subject = strip_tags($_POST["subject"]);
  $message = strip_tags($_POST["message"]);

  // Validate the email
  if ($email === false) {
    echo "<div class='alert alert-danger'>Пожалуйста, введите действительный адрес электронной почты.</div>";
    exit();
  }

  // Handle file upload
  if (isset($_FILES["attachment"])) {
    $file_name = $_FILES["attachment"]["name"];
    $file_tmp_name = $_FILES["attachment"]["tmp_name"];
    $file_size = $_FILES["attachment"]["size"];
    $file_error = $_FILES["attachment"]["error"];

    // Simple validation (Modify or enhance as needed)
    if ($file_error !== UPLOAD_ERR_OK) {
      echo "<div class='alert alert-danger'>Ошибка при загрузке файла. Попробуйте еще раз.</div>";
      exit();
    }

    // Define target directory and generate unique filename
    $target_dir = "uploads/";
    $file_name = uniqid() . "_" . $file_name;
    $file_target = $target_dir . $file_name;

    // Move uploaded file
    if (!move_uploaded_file($file_tmp_name, $file_target)) {
      echo "<div class='alert alert-danger'>Не удалось загрузить файл. Попробуйте еще раз.</div>";
      exit();
    }
  }

  // Create a new PHPMailer instance
  $mail = new PHPMailer();

  // Set the sender and recipient information
  $mail->setFrom($email, $name);
  $mail->addAddress('ksenia.busquet@gmail.com');

  // Set the email subject and body
  $mail->isHTML(true)
  $mail->Subject = "[contrast-s.ru] Сообщение от $name: $subject";
  $mail->Body = "<strong>Имя:</strong> $name<br><strong>Email:</strong> $email<br><strong>Тема сообщения:</strong> $subject<br><strong>Сообщение:</strong> $message<br><br>";

  // Attach the file if uploaded
  if (isset($file_target)) {
    $mail->addAttachment($file_target);
  }

  // Send the email
  if ($mail->send()) {
    echo "<div class='alert alert-success'>Ваше сообщение успешно отправлено!</div>";
  } else {
    echo "<div class='alert alert-danger'>Извините, Ваше сообщение не было отправлено, попробуйте еще раз позднее.</div>";
    echo 'Mailer Error: ' . $mail->ErrorInfo;
  }

} else {
  echo "<div class='alert alert-danger'>Пожалуйста, заполните все обязательные поля. Проверьте введённые данные и попробуйте ещё раз.</div>";
}

?>
