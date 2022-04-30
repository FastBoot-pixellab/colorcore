<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$itemID = post::number($_POST['itemID']);
$like = post::number(($_POST['like'] == 1) ? 1 : 0);
$type = post::number($_POST['type']);

GJP::check();
if($type == 1) { //level
    if($like == 1) $query = $db->prepare("UPDATE levels SET likes = (likes + 1) WHERE levelID = :itemID");
    else if($like == 0) $query = $db->prepare("UPDATE levels SET likes = (likes - 1) WHERE levelID = :itemID");
    $query->execute([':itemID' => $itemID]);
    echo '1';
} else if($type == 3) { //post
    if($like == 1) $query = $db->prepare("UPDATE acccomments SET likes = (likes + 1) WHERE ID = :itemID");
    else if($like == 0) $query = $db->prepare("UPDATE acccomments SET likes = (likes - 1) WHERE ID = :itemID");
    $query->execute([':itemID' => $itemID]);
    echo '1';
} else if($type == 2) { //comment
    if($like == 1) $query = $db->prepare("UPDATE comments SET likes = (likes + 1) WHERE commentID = :itemID");
    else if($like == 0) $query = $db->prepare("UPDATE comments SET likes = (likes - 1) WHERE commentID = :itemID");
    $query->execute([':itemID' => $itemID]);
    echo '1';
}