<?php
require 'lib/db.php';
require 'lib/main.php';

$gjp = $_POST['gjp'];
$accountID = $_POST['accountID'];
$commentID = $_POST['commentID'];

GJP::check($accountID, $gjp);
$query = $db->prepare("DELETE FROM comments WHERE accountID = :accID AND commentID = :commentID");
$query->execute([':accID' => $accountID, ':commentID' => $commentID]);
echo '1';