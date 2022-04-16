<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = $_POST['accountID'];
$target = $_POST['targetAccountID'];

if($target == $accountID) {
    $query = $db->prepare('SELECT * FROM accounts WHERE ID = :target');
    $query->execute([':target' => $target]);
    $data = $query->fetch();
    $badge = main::getBadge($target);
    $rank = main::getRank($accountID);
    //:31:FRIEND_STATE:44:
    echo '1:'.$data['userName'].':2:'.$data['ID'].':13:'.$data['coins'].':17:'.$data['userCoins'].':10:'.$data['color1'].':11:'.$data['color2'].':3:'.$data['stars'].':46:'.$data['diamonds'].':4:'.$data['demons'].':8:'.$data['cp'].':18:'.$data['ms'].':19:'.$data['frs'].':50:'.$data['cs'].':20:'.$data['yt'].':21:'.$data['accIcon'].':22:'.$data['accShip'].':23:'.$data['accBall'].':24:'.$data['accBird'].':25:'.$data['accDart'].':26:'.$data['accRobot'].':28:'.$data['accGlow'].':43:'.$data['accSpider'].':47:'.$data['accExplosion'].':30:'.$rank.':16:'.$data['ID'].':31:1:44:'.$data['twitter'].':45:'.$data['twitch'].':29:1:49:'.$badge;
}