<?php

$email = $_GET['email'];
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpMailer/src/Exception.php';
require 'phpMailer/src/PHPMailer.php';
require 'phpMailer/src/SMTP.php';

$mail = new PHPMailer;
$mail->isSMTP();
$mail->IsHTML(true);
$mail->Host = 'smtp.hostinger.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'admin@sems.api88.link';
$mail->Password = '@354un99UL';
$mail->setFrom('admin@sems.api88.link', 'Admin');
$mail->addReplyTo('admin@sems.api88.link', 'Admin');
$mail->addAddress($email, 'User');
$mail->Subject = 'KONFIRMASI AKUN ';
// $mail->msgHTML(file_get_contents('email.html'), __DIR__);
$mail->Body = 'This is a plain text message body';
//$mail->addAttachment('test.txt');
if (!$mail->send()) {
echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
echo 'The email message was sent.';
}
?>