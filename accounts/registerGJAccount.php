<?php
require '../lib/db.php';
require '../lib/main.php';
$main = new main();

if($_POST['userName'] && $_POST['email'] && $_POST['password']) {
    $userName = $_POST['userName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = $db->prepare("SELECT * FROM accounts WHERE userName = :userName");
    $query->execute([':userName' => $userName]);
    if($query->rowCount() == 0) {
        $db->prepare("INSERT INTO accounts (userName, password, email, timestamp, IP) VALUES (:userName, :password, :email, :timestamp,  :IP)")
        ->execute([':userName' => $userName, ':password' => password_hash($password, PASSWORD_DEFAULT), ':email' => $email, ':timestamp' => time(), ':IP' => $main::getIP()]);
        echo '1';
    } else echo '-3';
}