<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = post::number($_POST['accountID']);

GJP::check();
echo main::getBadge($accountID);