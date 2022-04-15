<?php
require '../lib/db.php';
require '../lib/main.php';
$main = new main();

$userName = $_POST['userName'];
$password = $_POST['password'];

$check = $main->checkAccount($userName, $password);
if($check == 1) {
    $query = $db->prepare("SELECT ID FROM accounts WHERE userName = :userName");
    $query->execute([':userName' => $userName]);
    $id = $query->fetchColumn();
    echo $id.','.$id;
} else if($check == -2) echo '-12';
else echo '-1';