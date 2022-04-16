<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = $_POST['accountID'];
$gjp = $_POST['gjp'];
$levelID = $_POST['levelID'];
$levelName = $_POST['levelName'];
$levelDesc = $_POST['levelDesc'];
$levelVersion = $_POST['levelVersion'];
$levelLength = $_POST['levelLength'];
$audioTrack = $_POST['audioTrack'];
$auto = $_POST['auto'];
$password = $_POST['password'];
$original = $_POST['original'];
$twoPlayer = $_POST['twoPlayer'];
$songID = $_POST['songID'];
$objects = $_POST['objects'];
$coins = $_POST['coins'];
$requestedStars = $_POST['requestedStars'];
$unlisted = $_POST['unlisted'];
$wt = $_POST['wt'];
$wt2 = $_POST['wt2'];
$ldm = $_POST['ldm'];
$extraString = $_POST['extraString'];
$levelString = $_POST['levelString'];
$levelInfo = $_POST['levelInfo'];

GJP::check($accountID, $gjp);
$query = $db->prepare("SELECT count(*) FROM levels WHERE accountID = :accountID AND levelName = :levelName");
$query->execute([':accountID' => $accountID, ':levelName' => $levelName]);
if($levelVersion == 1 && $levelID == 0 && $query->fetchColumn() == 0) {
    //new level
    $query = $db->prepare("INSERT INTO levels (accountID, levelName, levelDesc, levelVersion, levelLength, audioTrack, auto, password, original, twoPlayer, songID, objects, coins, requestedStars, unlisted, wt, wt2, ldm, timestamp, extraString, levelInfo) VALUES (
        :accountID,
        :levelName,
        :levelDesc,
        :levelVersion,
        :levelLength,
        :audioTrack,
        :auto,
        :password,
        :original,
        :twoPlayer,
        :songID,
        :objects,
        :coins,
        :requestedStars,
        :unlisted,
        :wt,
        :wt2,
        :ldm,
        :timestamp,
        :extraString,
        :levelInfo
    )");
    $query->execute([
        ':accountID' => $accountID,
        ':levelName' => $levelName,
        ':levelDesc' => base64_decode($levelDesc),
        ':levelVersion' => $levelVersion,
        ':levelLength' => $levelLength,
        ':audioTrack' => $audioTrack,
        ':auto' => $auto,
        ':password' => $password,
        ':original' => $original,
        ':twoPlayer' => $twoPlayer,
        ':songID' => $songID,
        ':objects' => $objects,
        ':coins' => $coins,
        ':requestedStars' => $requestedStars,
        ':unlisted' => $unlisted,
        ':wt' => $wt,
        ':wt2' => $wt2,
        ':ldm' => $ldm,
        ':timestamp' => time(),
        ':extraString' => $extraString,
        ':levelInfo' => $levelInfo
    ]);
    $id = $db->lastInsertId();
    //why not db? levelstring may be so large.
    file_put_contents(__DIR__."/levels/$id.uwu", $levelString);
    echo $id;
} else {
    //update level
    $query = $db->prepare("UPDATE levels SET
        accountID=:accountID,
        levelName=:levelName,
        levelDesc=:levelDesc,
        levelVersion=:levelVersion,
        levelLength=:levelLength,
        audioTrack=:audioTrack,
        auto=:auto,
        password=:password,
        original=:original,
        twoPlayer=:twoPlayer,
        songID=:songID,
        objects=:objects,
        coins=:coins,
        requestedStars=:requestedStars,
        unlisted=:unlisted,
        wt=:wt,
        wt2=:wt2,
        ldm=:ldm,
        extraString=:extraString,
        levelInfo=:levelInfo
    WHERE ID = :ID AND accountID = :accountID");
    $query->execute([
        ':accountID' => $accountID,
        ':levelName' => $levelName,
        ':levelDesc' => base64_decode($levelDesc),
        ':levelVersion' => $levelVersion,
        ':levelLength' => $levelLength,
        ':audioTrack' => $audioTrack,
        ':auto' => $auto,
        ':password' => $password,
        ':original' => $original,
        ':twoPlayer' => $twoPlayer,
        ':songID' => $songID,
        ':objects' => $objects,
        ':coins' => $coins,
        ':requestedStars' => $requestedStars,
        ':unlisted' => $unlisted,
        ':wt' => $wt,
        ':wt2' => $wt2,
        ':ldm' => $ldm,
        ':extraString' => $extraString,
        ':levelInfo' => $levelInfo,
        ':ID' => $levelID,
        ':accountID' => $accountID
    ]);
    $query = $db->prepare("SELECT ID FROM levels WHERE accountID = :accountID AND levelName = :levelName");
    $query->execute([':accountID' => $accountID, ':levelName' => $levelName]);
    $id = $query->fetchColumn();
    file_put_contents(__DIR__."/levels/$id.uwu", $levelString);
    echo $id;
}