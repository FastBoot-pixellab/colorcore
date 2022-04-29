<?php
require 'lib/db.php';
require 'lib/main.php';

$gjp = post::clear($_POST['gjp']);
$accountID = post::number($_POST['accountID']);
$commentID = post::number($_POST['commentID']);

GJP::check($accountID, $gjp);
$query = $db->prepare("DELETE FROM comments WHERE accountID = :accID AND commentID = :commentID");
$query->execute([':accID' => $accountID, ':commentID' => $commentID]);
echo '1';