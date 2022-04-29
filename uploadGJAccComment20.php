<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = post::number($_POST['accountID']);
$gjp = post::clear($_POST['gjp']);
$comment = post::clear($_POST['comment']);

GJP::check($accountID, $gjp);
$decodecomment = base64_decode($comment);
$query = $db->prepare("INSERT INTO acccomments (accountID, comment, timestamp) VALUES (:accountID, :comment, :timestamp)");
$query->execute([':accountID' => $accountID, ':comment' => $decodecomment, ':timestamp' => time()]);
echo '1';