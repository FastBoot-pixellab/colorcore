<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::clear($_POST['accountID']);
$gjp = post::clear($_POST['gjp']);
$levelID = post::number($_POST['levelID']);

GJP::check($accountID, $gjp);
$query = $db->prepare("DELETE FROM levels WHERE levelID = :id AND accountID = :accID LIMIT 1");
$query->execute([':id' => $levelID, ':accID' => $accountID]);
if($query->rowCount() > 0) unlink(__DIR__."/levels/$levelID.uwu");
echo '1';