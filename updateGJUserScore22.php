<?php
require 'lib/db.php';
require 'lib/main.php';
$GJPcheck = new GJP();

$accountID = $_POST['accountID'];
$gjp = $_POST['gjp'];
$userName = $_POST['userName'];
$stars = $_POST['stars'];
$demons = $_POST['demons'];
$diamonds = $_POST['diamonds'];
$icon = $_POST['icon'];
$color1 = $_POST['color1'];
$color2 = $_POST['color2'];
$iconType = $_POST['iconType'];
$coins = $_POST['coins'];
$userCoins = $_POST['userCoins'];
$accIcon = $_POST['accIcon'];
$accShip = $_POST['accShip'];
$accBall = $_POST['accBall'];
$accBird = $_POST['accBird'];
$accDart = $_POST['accDart'];
$accRobot = $_POST['accRobot'];
$accGlow = $_POST['accGlow'];
$accSpider = $_POST['accSpider'];
$accExplosion = $_POST['accExplosion'];

$GJPcheck::check($accountID, $gjp);
$query = $db->prepare("UPDATE accounts SET
    stars=:stars,
    demons=:demons,
    diamonds=:diamonds,
    icon=:icon,
    color1=:color1,
    color2=:color2,
    iconType=:iconType,
    coins=:coins,
    userCoins=:userCoins,
    accIcon=:accIcon,
    accShip=:accShip,
    accBall=:accBall,
    accBird=:accBird,
    accDart=:accDart,
    accRobot=:accRobot,
    accGlow=:accGlow,
    accSpider=:accSpider,
    accExplosion=:accExplosion
WHERE ID = :ID");
$query->execute([
    ':stars' => $stars,
    ':demons' => $demons,
    ':diamonds' => $diamonds,
    ':icon' => $icon,
    ':color1' => $color1,
    ':color2' => $color2,
    ':iconType' => $iconType,
    ':coins' => $coins,
    ':userCoins' => $userCoins,
    ':accIcon' => $accIcon,
    ':accShip' => $accShip,
    ':accBall' => $accBall,
    ':accBird' => $accBird,
    ':accDart' => $accDart,
    ':accRobot' => $accRobot,
    ':accGlow' => $accGlow,
    ':accSpider' => $accSpider,
    ':accExplosion' => $accExplosion,
    ':ID' => $accountID
]);
$query = $db->prepare("SELECT ID FROM accounts WHERE userName = :userName");
$query->execute([':userName' => $userName]);
$id = $query->fetchColumn();
echo $id;