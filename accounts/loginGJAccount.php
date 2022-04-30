<?php
require '../lib/db.php';
require '../lib/main.php';
require '../lib/PluginManager.php';
require '../config/security.php';

$userName = post::clear($_POST['userName']);
$password = post::clear($_POST['password']);

//TODO: maybe I need to make a better version
$check = main::checkAccount($userName, $password);
if($activationType == 4 && $check == -3) {
    $query = $db->prepare("SELECT isActive FROM accounts WHERE userName = :userName");
    $query->execute([':userName' => $userName]);
    if($query->fetchColumn() == 0) {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Authorization: Bot $bot_token\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $json = file_get_contents("https://discord.com/api/channels/$bot_channelID/messages?limit=100", false, $context);
        $messages = json_decode($json, true);
        foreach($messages as $message) {
            if($message['content'] == $userName) {
                $query = $db->prepare("UPDATE accounts SET isActive = 1, discordID = :id");
                $query->execute([':id' => $message['id']]);
            }
        }
    }
}

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