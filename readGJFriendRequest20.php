<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$requestID = post::number($_POST['requestID']);

GJP::check();
$query = $db->prepare("UPDATE friendreq SET isNew = 0 WHERE friendreqID = :id AND toID = :toID");
$query->execute([':id' => $requestID, ':toID' => $accountID]);
echo '1';