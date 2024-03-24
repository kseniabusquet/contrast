<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if required fields are set
  if (isset ($_POST["name"], $_POST["email"], $_POST["subject"], $_POST["message"])) {
    $name = strip_tags($_POST["name"]);
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $subject = strip_tags($_POST["subject"]);
    $message = strip_tags($_POST["message"]);

    // Validate email
    if ($email === false) {
      echo "<div class='alert alert-danger'>Пожалуйста, введите действительный адрес электронной почты.</div>";
      exit(); // Stop execution if email is invalid
    }

    // Email sending
    $to = "contrast-spb@inbox.ru";
    $subject = "[contrast-s.ru] Сообщение от $name: $subject";

    // Multipart email headers
    $boundary = uniqid();
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    // Construct email body
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= "<strong>Имя:</strong> $name<br><strong>Email:</strong> $email<br><strong>Тема сообщения:</strong> $subject<br><strong>Сообщение:</strong> $message<br><br>";

    // File upload handling
    if (isset ($_FILES["attachment"])) {
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
      $target_dir = "../uploads/";
      $file_name = uniqid() . "_" . $file_name;
      $file_target = $target_dir . $file_name;

      // Attachment portion
      $body .= "--$boundary\r\n";
      $body .= "Content-Type: application/octet-stream; name=\"" . $file_name . "\"\r\n";
      $body .= "Content-Transfer-Encoding: base64\r\n";
      $body .= "Content-Disposition: attachment\r\n\r\n";
      $body .= chunk_split(base64_encode(file_get_contents($file_target))) . "\r\n";
      $body .= "--$boundary--";

      // Move uploaded file
      if (!move_uploaded_file($file_tmp_name, $file_target)) {
        echo "<div class='alert alert-danger'>Не удалось загрузить файл. Попробуйте еще раз.</div>";
        exit();
      }
    }

    // Send the email
    if (mail($to, $subject, $body, $headers)) {
      echo "<div class='alert alert-success'>Ваше сообщение успешно отправлено!</div>";
    } else {
      echo "<div class='alert alert-danger'>Извините, Ваше сообщение не было отправлено, попробуйте еще раз позднее.</div>";
    }
  } else {
    echo "<div class='alert alert-danger'>Пожалуйста, заполните все обязательные поля. Проверьте введённые данные и попробуйте ещё раз.</div>";
  }
} else {
  echo "<div class='alert alert-danger'>Произошла ошибка, попробуйте ещё раз.</div>";
}
