<?php
include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_GET['email'];
$password = $_GET['pass'];

if (isset($email)) {
    $status = "0";
    $query = "INSERT INTO `user`(`email`, `password`,`status`) VALUES ('$email','$password','$status')";
    $result = $mysqli->query($query);

    if ($result) {

        $response = array();
        $response["data"] = array();
        $dat['STATUS'] = "OK";
        $dat['CODE'] = "200";
        array_push($response['data'], $dat);
        echo json_encode($response, JSON_UNESCAPED_SLASHES);



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
        $mail->Body = 'http://sems.api88.link/d82eha9qeu91cn9e917319.php?12j4has8=' . $email;
        //$mail->addAttachment('test.txt');
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'The email message was sent.';
        }
        
    }
}
