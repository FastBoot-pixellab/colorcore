<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$commentID = post::number($_POST['commentID']);

GJP::check();
$query = $db->prepare("DELETE FROM comments WHERE accountID = :accID AND commentID = :commentID");
$query->execute([':accID' => $accountID, ':commentID' => $commentID]);
echo '1';