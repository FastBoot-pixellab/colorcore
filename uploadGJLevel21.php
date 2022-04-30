<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$levelID = post::number($_POST['levelID']);
$levelName = post::clear($_POST['levelName']);
$levelDesc = post::clear($_POST['levelDesc']);
$levelVersion = post::number($_POST['levelVersion']);
$levelLength = post::number($_POST['levelLength']);
$audioTrack = post::number($_POST['audioTrack']);
$auto = post::number($_POST['auto']);
$password = post::number($_POST['password']);
$original = post::number($_POST['original']);
$twoPlayer = post::number($_POST['twoPlayer']);
$songID = post::number($_POST['songID']);
$objects = post::number($_POST['objects']);
$coins = post::number($_POST['coins']);
$requestedStars = post::number($_POST['requestedStars']);
$unlisted = post::number($_POST['unlisted']);
$wt = post::number($_POST['wt']);
$wt2 = post::number($_POST['wt2']);
$ldm = post::number($_POST['ldm']);
$extraString = post::clear($_POST['extraString']);
$levelString = post::clear($_POST['levelString']);
$levelInfo = post::clear($_POST['levelInfo']);

GJP::check();
$query = $db->prepare("SELECT count(*) FROM levels WHERE accountID = :accountID AND levelName = :levelName");
$query->execute([':accountID' => $accountID, ':levelName' => $levelName]);
if($levelVersion == 1 && $levelID == 0 && $query->fetchColumn() == 0) {
    //upload level
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
        ':levelDesc' => ($levelDesc == '') ? "Tm8gZGVzY3JpcHRpb24u" : $levelDesc,
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
    //why not? levelstring may be so large.
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
        levelInfo=:levelInfo,
        updateTimestamp = :updTime
    WHERE levelID = :ID AND accountID = :accountID");
    $query->execute([
        ':accountID' => $accountID,
        ':levelName' => $levelName,
        ':levelDesc' => ($levelDesc == '') ? "Tm8gZGVzY3JpcHRpb24u" : $levelDesc,
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
        ':updTime' => time(),
        ':ID' => $levelID,
        ':accountID' => $accountID
    ]);
    $query = $db->prepare("SELECT levelID FROM levels WHERE accountID = :accountID AND levelName = :levelName");
    $query->execute([':accountID' => $accountID, ':levelName' => $levelName]);
    $id = $query->fetchColumn();
    file_put_contents(__DIR__."/levels/$id.uwu", $levelString);
    echo $id;
}