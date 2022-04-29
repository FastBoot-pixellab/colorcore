<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = post::number($_POST['accountID']);
$gjp = post::clear($_POST['gjp']);
$levelID = post::number($_POST['levelID']);
$rating = post::number($_POST['rating']);

GJP::check($accountID, $gjp);
$badge = main::getBadge($accountID);
if($badge == 1 || $badge == 2) {
    $query = $db->prepare("UPDATE levels SET starDemonDiff = :diff WHERE starDemon = 1 AND levelID = :levelID");
    $diff = 3;
    switch($rating) {
        case 1:
            $diff = 3;
            break;
        case 2:
            $diff = 4;
            break;
        case 3:
            $diff = 2;
            break;
        case 4:
            $diff = 5;
            break;
        case 5:
            $diff = 6;
            break;
    }
    $query->execute([':diff' => $diff, ':levelID' => $levelID]);
    echo '1';
} else echo '-1';