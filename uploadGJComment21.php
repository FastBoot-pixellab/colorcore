<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$comment = post::clear($_POST['comment']);
$levelID = post::number($_POST['levelID']);
$percent = post::number(!empty($_POST["percent"]) ? $_POST["percent"] : 0);

GJP::check();
if($comment != '') {
    $query = $db->prepare("INSERT INTO comments (levelID, timestamp, comment, accountID, percent) VALUES (:levelID, :timestamp, :comment, :accountID, :percent)");
    $query->execute([':levelID' => $levelID, ':timestamp' => time(), ':comment' => base64_encode($comment), ':accountID' => $accountID, ':percent' => $percent]);
    echo '1';
}