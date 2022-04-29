<?php
require 'lib/db.php';

$levelID = post::number($_POST['levelID']);

$query = $db->prepare("INSERT INTO reports (type, ID) VALUES ('level', :ID)");
$query->execute([':ID' => $levelID]);
echo '1';