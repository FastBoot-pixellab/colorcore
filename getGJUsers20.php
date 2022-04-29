<?php
require 'lib/db.php';
require 'lib/main.php';

$str = post::clear($_POST['str']);
$page = post::number($_POST['page']);

$offset = $page*10;
$query = $db->prepare("SELECT * FROM accounts WHERE accountID = :str OR userName LIKE CONCAT('%', :str, '%') ORDER BY stars DESC LIMIT 10 OFFSET $offset");
$query->execute([':str' => $str]);
$count = $query->rowCount();
$users = $query->fetchAll();
$string = "";
foreach($users as $user) {
    $string .= '1:'.$user['userName'].':2:'.$user['accountID'].':13:'.$user['coins'].':17:'.$user['userCoins'].':9:'.$user['icon'].':10:'.$user['color1'].':11:'.$user['color2'].':14:'.$user['iconType'].':15:'.$user['special'].':16:'.$user['accountID'].':3:'.$user['stars'].':8:'.$user['cp'].':4:'.$user['demons'].'|';
}
$string = substr($string, 0, -1);
echo $string.'#'.$count.':'.$offset.':10';