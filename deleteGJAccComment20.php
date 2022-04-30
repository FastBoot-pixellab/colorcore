<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$commentID = post::number($_POST['commentID']);

GJP::check();
$query = $db->prepare("DELETE FROM acccomments WHERE ID = :commentID AND accountID = :accountID");
$query->execute([':commentID' => $commentID, ':accountID' => $accountID]);
echo '1';