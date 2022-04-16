<?php
require 'lib/db.php';
require 'lib/main.php';
$GJPcheck = new GJP();

$accountID = $_POST['accountID'];
$gjp = $_POST['gjp'];
$commentID = $_POST['commentID'];

$GJPcheck::check($accountID, $gjp);
$query = $db->prepare("DELETE FROM acccomments WHERE ID = :commentID AND accountID = :accountID");
$query->execute([':commentID' => $commentID, ':accountID' => $accountID]);
echo '1';