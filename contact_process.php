<?php
require('./webHandler.php');
require('./config/PHPMailer.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$_POST = array_map('htmlspecialchars', $_POST);
$from = $_POST['email'];
$name = $_POST['name'];
$subject = $_POST['subject'];
$cmessage = $_POST['message'];

$subject = "You have a message from your website.";

$body = "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Travel The World</title></head><body>";
$body .= "<table style='width: 100%;'>";
$body .= "<tr>";
$body .= "<td style='border:none;'><strong>Name:</strong> {$name}</td>";
$body .= "<td style='border:none;'><strong>Email:</strong> {$from}</td>";
$body .= "</tr>";
$body .= "<tr><td style='border:none;'><strong>Subject:</strong> {$subject}</td></tr>";
$body .= "<tr><td></td></tr>";
$body .= "<tr><td colspan='2' style='border:1px solid black;'><strong>Message:</strong> {$cmessage}</td></tr>";
$body .= "</table>";
$body .= "</body></html>";


$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->SMTPDebug = 0; // 2 -> ON, 0 -> OFF
    $mail->Host = 'smtp.hostinger.in';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'username@gmail.com';
    $mail->Password = 'randome@2763';

    $mail->setFrom(SITE_EMAIL, SITE_TITLE);
    $mail->addReplyTo(SITE_EMAIL, SITE_TITLE);
    $mail->addAddress(SITE_EMAIL, SITE_TITLE);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->send();
} catch (Exception $e) {
}
