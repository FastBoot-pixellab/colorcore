<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$target = post::number($_POST['targetAccountID']);
$isSender = post::number($_POST['isSender']);

GJP::check();
if($isSender == 1) $query = $db->prepare("DELETE FROM friendreq WHERE fromID = :accID AND toID = :target LIMIT 1");
else if($isSender == 0) $query = $db->prepare("DELETE FROM friendreq WHERE toID = :target AND toID = :accID LIMIT 1");
$query->execute([':accID' => $accountID, ':target' => $target]);
echo '1';