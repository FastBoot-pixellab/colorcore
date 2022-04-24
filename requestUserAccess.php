<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = $_POST['accountID'];
$gjp = $_POST['gjp'];

GJP::check($accountID, $gjp);
echo main::getBadge($accountID);