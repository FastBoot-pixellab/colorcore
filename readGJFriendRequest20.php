<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = $_POST['accountID'];
$gjp = $_POST['gjp'];
$requestID = $_POST['requestID'];

$query = $db->prepare("UPDATE friendreq SET isNew = 0 WHERE friendreqID = :id AND toID = :toID");
$query->execute([':id' => $requestID, ':toID' => $accountID]);
echo '1';