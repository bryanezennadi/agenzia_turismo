<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configurazione SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'bryan.ezennadi@iisviolamarchesini.edu.it';
    $mail->Password = 'ezfp zhkb lcne qezz'; // non la password normale!
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Mittente e destinatario
    $mail->setFrom('bryan.ezennadi@iisviolamarchesini.edu.it', 'Bryan');
    $mail->addAddress($email, $nome);

    // Contenuto
    $mail->isHTML(true);
    $mail->Subject = 'login effettuato';
    $mail->Body = 'grazie '.$nome.' per esserti registrato';

    $mail->send();
    echo 'Messaggio inviato con successo!';
} catch (Exception $e) {
    echo "Errore: {$mail->ErrorInfo}";
}
