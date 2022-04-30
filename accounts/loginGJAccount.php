<?php
require '../lib/db.php';
require '../lib/main.php';
require '../lib/PluginManager.php';

$userName = post::clear($_POST['userName']);
$password = post::clear($_POST['password']);

$check = main::checkAccount($userName, $password);
if($check == 1) {
    $query = $db->prepare("SELECT accountID FROM accounts WHERE userName = :userName");
    $query->execute([':userName' => $userName]);
    $id = $query->fetchColumn();
    $plugins = PluginManager::init();
    foreach($plugins as $plugin) {
        require __DIR__."/plugins/$plugin.php";
        if(method_exists($plugin, "on_loginGJAccount")) $plugin::on_loginGJAccount($userName, $password);
    }
    echo $id.','.$id;
} else if($check == -2) echo '-12';
else echo '-1';