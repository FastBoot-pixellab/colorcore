<?php
require 'lib/db.php';
require 'lib/main.php';

$type = $_POST["type"];

$str = '';
$queryf = '';
switch($type) {
    case 'top':
        $queryf = "SELECT * FROM accounts WHERE isBanned = 0 AND stars >= 0 ORDER BY stars DESC LIMIT 100";
        break;
    case 'creators':
        $queryf = "SELECT * FROM accounts WHERE isBanned = 0 AND cp >= 0 ORDER BY cp DESC LIMIT 100";
        break;
    case 'relative':
        exit('-1');
        break;
    case 'friends':
        exit('-1');
        break;
}
$query = $db->prepare($queryf);
$query->execute();
$users = $query->fetchAll();
foreach($users as $user) {
    $str .= '1:'.$user['userName'].':2:'.$user['accountID'].':13:'.$user['coins'].':17:'.$user['userCoins'].':6:1:9:'.$user['icon'].':10:'.$user['color1'].':11:'.$user['color2'].':14:'.$user['iconType'].':15:'.$user['special'].':16:'.$user['accountID'].':3:'.$user['stars'].':8:'.$user['cp'].':4:'.$user['demons'].':7:'.$user['accountID'].':46:'.$user['diamonds'].'|';
}
if($str == '') exit(-1);
$str = substr($str, 0, -1);
echo $str;