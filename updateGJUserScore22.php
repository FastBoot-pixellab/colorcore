<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$userName = post::clear($_POST['userName']);
$stars = post::number($_POST['stars']);
$demons = post::number($_POST['demons']);
$diamonds = post::number($_POST['diamonds']);
$icon = post::number($_POST['icon']);
$color1 = post::number($_POST['color1']);
$color2 = post::number($_POST['color2']);
$iconType = post::number($_POST['iconType']);
$coins = post::number($_POST['coins']);
$userCoins = post::number($_POST['userCoins']);
$accIcon = post::number($_POST['accIcon']);
$accShip = post::number($_POST['accShip']);
$accBall = post::number($_POST['accBall']);
$accBird = post::number($_POST['accBird']);
$accDart = post::number($_POST['accDart']);
$accRobot = post::number($_POST['accRobot']);
$accGlow = post::number($_POST['accGlow']);
$accSpider = post::number($_POST['accSpider']);
$accExplosion = post::number($_POST['accExplosion']);
$special = post::number($_POST['special']);

GJP::check();
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
    accExplosion=:accExplosion,
    special=:special
WHERE accountID = :ID");
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
    ':special' => $special,
    ':ID' => $accountID
]);
$query = $db->prepare("SELECT accountID FROM accounts WHERE userName = :userName");
$query->execute([':userName' => $userName]);
$id = $query->fetchColumn();
echo $id;