<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = $_POST['accountID'];
$gjp = $_POST['gjp'];
$toAccountID = $_POST['toAccountID'];
$comment = ($_POST['comment'] == '') ? 'Tm8gY29tbWVudC4=' : $_POST['comment'];

GJP::check($accountID, $gjp);
$query = $db->prepare("INSERT INTO friendreq (fromID, toID, comment, timestamp) VALUES (:from, :to, :comment, :time)");
$query->execute([':from' => $accountID, ':to' => $toAccountID, ':comment' => $comment, ':time' => time()]);
echo '1';