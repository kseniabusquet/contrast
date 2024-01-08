<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags($_POST["name"]);
    $email = strip_tags($_POST["email"]);
    $subject = strip_tags($_POST["subject"]);
    $message = strip_tags($_POST["message"]);

    $to = "contrast-spb@inbox.ru";
    $subject = "Сообщение от $name: $subject";
    $body = "Имя: $name\nEmail: $email\nТема псообщенияисьма: $subject\nСообщение: $message";
    $headers = "От: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo "<div class='alert alert-success'>Ваше сообщение успешно отправлено!</div>";
    } else {
        echo "<div class='alert alert-danger'>Извините, Ваше сообщение не было отправлено, попробуйте еще раз позднее.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Что-то пошло не так, попробуйте еще раз позднее.</div>";
}
?>
