<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = post::number($_POST['accountID']);
$gjp = post::clear($_POST['gjp']);

GJP::check($accountID, $gjp);
echo main::getBadge($accountID);