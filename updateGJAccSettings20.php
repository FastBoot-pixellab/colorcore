<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$gjp = post::clear($_POST['gjp']);
$ms = post::number($_POST['mS']);
$frs = post::number($_POST['frS']);
$cs = post::number($_POST['cS']);
$yt = post::clear($_POST['yt']);
$twitter = post::clear($_POST['twitter']);
$twitch = post::clear($_POST['twitch']);

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