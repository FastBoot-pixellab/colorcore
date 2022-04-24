<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = $_POST['accountID'];
$gjp = $_POST['gjp'];
$comment = $_POST['comment'];
$levelID = $_POST['levelID'];
$percent = !empty($_POST["percent"]) ? $_POST["percent"] : 0;

GJP::check($accountID, $gjp);
if($comment != '') {
    $query = $db->prepare("INSERT INTO comments (levelID, timestamp, comment, accountID, percent) VALUES (:levelID, :timestamp, :comment, :accountID, :percent)");
    $query->execute([':levelID' => $levelID, ':timestamp' => time(), ':comment' => base64_encode($comment), ':accountID' => $accountID, ':percent' => $percent]);
    echo '1';
}