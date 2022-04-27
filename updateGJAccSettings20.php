<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = $_POST['accountID'];
$gjp = $_POST['gjp'];
$ms = $_POST['mS'];
$frs = $_POST['frS'];
$cs = $_POST['cS'];
$yt = $_POST['yt'];
$twitter = $_POST['twitter'];
$twitch = $_POST['twitch'];

GJP::check($accountID, $gjp);
$query = $db->prepare("UPDATE accounts SET
    ms=:ms,
    frs=:frs,
    cs=:cs,
    yt=:yt,
    twitter=:twitter,
    twitch=:twitch
WHERE accountID = :accountID");
$query->execute([':ms' => $ms, ':frs' => $frs, ':cs' => $cs, ':yt' => $yt, ':twitter' => $twitter, ':twitch' => $twitch, ':accountID' => $accountID]);
echo '1';