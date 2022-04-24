<?php
require 'lib/db.php';

$levelID = $_POST['levelID'];

$query = $db->prepare("INSERT INTO reports (type, ID) VALUES ('level', :ID)");
$query->execute([':ID' => $levelID]);
echo '1';