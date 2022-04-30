<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$gjp = post::clear($_POST['gjp']);
$levelID = post::number($_POST['levelID']);
$stars = post::number($_POST['stars']);
$feature = post::number($_POST['feature']);

GJP::check($accountID, $gjp);
$badge = main::getBadge($accountID);
if($badge == 1 || $badge == 2) {
    $diff = main::getDiffFromStars($stars);
    main::rateLevel($levelID, $stars, $diff, $feature);
    echo '1';
} else echo '-1';