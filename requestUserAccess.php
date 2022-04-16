<?php
require 'lib/db.php';
require 'lib/main.php';
$main = new main();

$accountID = $_POST['accountID'];
$gjp = $_POST['gjp'];

echo $main::getBadge($accountID);