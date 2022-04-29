<?php
require '../lib/db.php';
require '../lib/main.php';
include '../config/security.php';
use PHPMailer\PHPMailer\PHPMailer;
require_once '../lib/PHPMailer/Exception.php';
require_once '../lib/PHPMailer/PHPMailer.php';
require_once '../lib/PHPMailer/SMTP.php';

if($_POST['userName'] && $_POST['email'] && $_POST['password']) {
    $userName = $_POST['userName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = $db->prepare("SELECT * FROM accounts WHERE userName = :userName");
    $query->execute([':userName' => $userName]);
    if($query->rowCount() == 0) {
        $token = main::genToken();
        if($activationType == 3) {
            if(main::checkEmail($email)) exit('-6');
            $query2 = $db->prepare("SELECT * FROM accounts WHERE email = :email");
            $query2->execute([':email' => $email]);
            if($query2->rowCount() == 0) { 
                $path = 'http://'.$_SERVER["HTTP_HOST"].str_replace('/accounts/registerGJAccount.php', '', $_SERVER['PHP_SELF']).'/activateAccount.php?token='.$token;
                $mail = new PHPMailer(true);
                $mail->CharSet = 'utf-8';
                $mail->isSMTP();
                $mail->Host = $smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = $smtp_mail;
                $mail->Password = $smtp_password;
                $mail->SMTPSecure = 'ssl';
                $mail->Port = $smtp_port;
                $mail->setFrom($smtp_mail);
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'GDPS account activation';
                $mail->Body = "<h1 align=center>Welcome $userName!</h1><p align=center>Please activate your GDPS account <a href='".$path."'>here</a></p><p align=center>Powered by <a href='https://github.com/itsMysteryio/colorcore'>ColorCore</a></p>";
                $mail->AltBody = '';
                if($mail->send()) {
                    $query = $db->prepare("INSERT INTO accounts (userName, password, email, timestamp, IP, token) VALUES (:userName, :password, :email, :timestamp, :IP, :token)");
                    $query->execute([':userName' => $userName, ':password' => password_hash($password, PASSWORD_DEFAULT), ':email' => $email, ':timestamp' => time(), ':IP' => main::getIP(), ':token' => $token]);
                    echo '1';
                } else echo '-1';
            } else echo '-3';
        } else {
            $query1 = $db->prepare("INSERT INTO accounts (userName, password, email, timestamp, IP, token) VALUES (:userName, :password, :email, :timestamp, :IP, :token)");
            $query1->execute([':userName' => $userName, ':password' => password_hash($password, PASSWORD_DEFAULT), ':email' => $email, ':timestamp' => time(), ':IP' => main::getIP(), ':token' => $token]);
            echo '1';
        }
    } else echo '-2';
}