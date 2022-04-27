<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = $_POST['accountID'];
$target = $_POST['targetAccountID'];

$appendix = '';
$badge = main::getBadge($target);
$rank = main::getRank($target);
$query = $db->prepare('SELECT * FROM accounts WHERE accountID = :target');
$query->execute([':target' => $target]);
$data = $query->fetch();
if($target != $accountID) {
    //friendereqs
    $friendstate=0;
    //to me
    $query = $db->prepare("SELECT * FROM friendreq WHERE toID = :accID AND fromID = :target");
    $query->execute([':accID' => $accountID, ':target' => $target]);
    $tocount = $query->rowCount();
    if($tocount > 0) {
        $friendstate = 3;
    }
    $toreqs = $query->fetchAll();
    $timestamp = date("d/m/Y G.i", $toreqs["timestamp"]);
    //from me
    $query = $db->prepare("SELECT * FROM friendreq WHERE fromID = :accID AND toID = :target");
    $query->execute([':accID' => $accountID, ':target' => $target]);
    $fromcount = $query->rowCount();
    if($fromcount > 0) {
        $friendstate = 4;
    }
    $fromreqs = $query->fetchAll();
    //friends already
    $query = $db->prepare("SELECT * FROM friends WHERE person1 = :p1 AND person2 = :p2");
    $query->execute([':p1' => $accountID, ':p2' => $target]);
    $frcount = $query->rowCount();
    if($frcount > 0) {
        $friendstate = 1;
    }
    if($tocount > 0) {
        $appendix = ':32:'.$toreqs['friendreqID'].':35:'.$toreqs['comment'].':37:'.$timestamp;
    }
}
//:31:FRIEND_STATE:44:
echo '1:'.$data['userName'].':2:'.$data['accountID'].':13:'.$data['coins'].':17:'.$data['userCoins'].':10:'.$data['color1'].':11:'.$data['color2'].':3:'.$data['stars'].':46:'.$data['diamonds'].':4:'.$data['demons'].':8:'.$data['cp'].':18:'.$data['ms'].':19:'.$data['frs'].':50:'.$data['cs'].':20:'.$data['yt'].':21:'.$data['accIcon'].':22:'.$data['accShip'].':23:'.$data['accBall'].':24:'.$data['accBird'].':25:'.$data['accDart'].':26:'.$data['accRobot'].':28:'.$data['accGlow'].':43:'.$data['accSpider'].':47:'.$data['accExplosion'].':30:'.$rank.':16:'.$data['accountID'].':31:'.$friendstate.':44:'.$data['twitter'].':45:'.$data['twitch'].':29:1:49:'.$badge.$appendix;