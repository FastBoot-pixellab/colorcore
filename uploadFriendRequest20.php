<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$gjp = post::clear($_POST['gjp']);
$toAccountID = post::number($_POST['toAccountID']);
$comment = post::clear(($_POST['comment'] == '') ? 'Tm8gY29tbWVudC4=' : $_POST['comment']);

GJP::check($accountID, $gjp);
$query = $db->prepare("INSERT INTO friendreq (fromID, toID, comment, timestamp) VALUES (:from, :to, :comment, :time)");
$query->execute([':from' => $accountID, ':to' => $toAccountID, ':comment' => $comment, ':time' => time()]);
echo '1';