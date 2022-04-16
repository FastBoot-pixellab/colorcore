<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = $_POST['accountID'];
$gjp = $_POST['gjp'];
$commentID = $_POST['commentID'];

GJP::check($accountID, $gjp);
$query = $db->prepare("DELETE FROM acccomments WHERE ID = :commentID AND accountID = :accountID");
$query->execute([':commentID' => $commentID, ':accountID' => $accountID]);
echo '1';