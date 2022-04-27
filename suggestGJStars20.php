<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = $_POST['accountID'];
$gjp = $_POST['gjp'];
$levelID = $_POST['levelID'];
$stars = $_POST['stars'];
$feature = $_POST['feature'];

GJP::check($accountID, $gjp);
$badge = main::getBadge($accountID);
if($badge == 1 || $badge == 2) {
    $diff = main::getDiffFromStars($stars);
    main::rateLevel($levelID, $stars, $diff, $feature);
    echo '1';
} else echo '-1';