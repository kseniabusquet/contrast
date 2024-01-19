<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required fields are set
    if (isset($_POST["name"], $_POST["email"], $_POST["subject"], $_POST["message"])) {
        $name = strip_tags($_POST["name"]);
        $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
        $subject = strip_tags($_POST["subject"]);
        $message = strip_tags($_POST["message"]);

        // Validate email
        if ($email === false) {
            echo "<div class='alert alert-danger'>Пожалуйста, введите действительный адрес электронной почты.</div>";
            exit(); // Stop execution if email is invalid
        }

        $to = "contrast-spb@inbox.ru";
        $subject = "[contrast-s.ru] Сообщение от $name: $subject";
        $body = "<strong>Имя:</strong> $name<br><strong>Email:</strong> $email<br><strong>Тема сообщения:</strong> $subject<br><strong>Сообщение:</strong> $message";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        if (mail($to, $subject, $body, $headers)) {
            echo "<div class='alert alert-success'>Ваше сообщение успешно отправлено!</div>";
        } else {
            echo "<div class='alert alert-danger'>Извините, Ваше сообщение не было отправлено, попробуйте еще раз позднее.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Please fill out all required fields. Check the entered data and try again.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Что-то пошло не так, попробуйте еще раз позднее.</div>";
}
?>
