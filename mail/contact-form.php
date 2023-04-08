<?php
if(empty($_POST['name']) || empty($_POST['subject']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  http_response_code(500);
  exit();
}

$name = strip_tags(htmlspecialchars($_POST['name']));
$email = strip_tags(htmlspecialchars($_POST['email']));
$m_subject = strip_tags(htmlspecialchars($_POST['subject']));
$message = strip_tags(htmlspecialchars($_POST['message']));

$to = "ksenia.busquet@gmail.com"; // Change this email to your //
$subject = "ООО КОНТРАСТ: $m_subject:  $name";
$body = "Вам пришло сообщение через форму обратной связи.\n\n"."Детали сообщения:\n\nИмя: $name\n\nEmail: $email\n\nТема сообщения: $m_subject\n\nСообщение: $message";
$header = "From: $email";
$header .= "Reply-To: $email";

if(!mail($to, $subject, $body, $header))
  http_response_code(500);
?>
