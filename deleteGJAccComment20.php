<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = post::number($_POST['accountID']);
$gjp = post::clear($_POST['gjp']);
$commentID = post::number($_POST['commentID']);

GJP::check($accountID, $gjp);
$query = $db->prepare("DELETE FROM acccomments WHERE ID = :commentID AND accountID = :accountID");
$query->execute([':commentID' => $commentID, ':accountID' => $accountID]);
echo '1';