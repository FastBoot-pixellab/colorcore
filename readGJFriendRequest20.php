<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$gjp = post::clear($_POST['gjp']);
$requestID = post::number($_POST['requestID']);

GJP::check($accountID, $gjp);
$query = $db->prepare("UPDATE friendreq SET isNew = 0 WHERE friendreqID = :id AND toID = :toID");
$query->execute([':id' => $requestID, ':toID' => $accountID]);
echo '1';