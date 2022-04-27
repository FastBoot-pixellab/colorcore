<?php
require 'lib/db.php';
require 'lib/main.php';

$gjp = $_POST['gjp'];
$accountID = $_POST['accountID'];
$target = $_POST['targetAccountID'];
$isSender = $_POST['isSender'];

GJP::check($accountID, $gjp);
if($isSender == 1) $query = $db->prepare("DELETE FROM friendreq WHERE fromID = :accID AND toID = :target LIMIT 1");
else if($isSender == 0) $query = $db->prepare("DELETE FROM friendreq WHERE toID = :target AND toID = :accID LIMIT 1");
$query->execute([':accID' => $accountID, ':target' => $target]);
echo '1';