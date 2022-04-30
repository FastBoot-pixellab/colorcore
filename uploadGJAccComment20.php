<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$comment = post::clear($_POST['comment']);

GJP::check();
$decodecomment = base64_decode($comment);
$query = $db->prepare("INSERT INTO acccomments (accountID, comment, timestamp) VALUES (:accountID, :comment, :timestamp)");
$query->execute([':accountID' => $accountID, ':comment' => $decodecomment, ':timestamp' => time()]);
echo '1';