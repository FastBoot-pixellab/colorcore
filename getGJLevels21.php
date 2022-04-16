<?php

//works only with 'search', some code by klimer
//soon i write full & my own

include "lib/db.php";
include "lib/main.php";

$levelString = "";
$songString = "";
$order = "timestamp";
$levelMultiString = "";
$userString = "";
$where[] = "NOT unlisted = 1";
$q = '';

$type = !empty($_POST["type"]) ? htmlspecialchars($_POST["type"]) : 0;
$difficulty = !empty($_POST["diff"]) ? htmlspecialchars($_POST["diff"]) : "-";
if(isset($_POST["page"]) AND is_numeric($_POST["page"])){
	$offset = htmlspecialchars($_POST["page"])."0";
}else{
	$offset = 0;
}
$str = htmlspecialchars($_POST["str"]);
if($type == 0 || $type == 15) {
if(!empty($str)) {
    if(is_numeric($str)) {
        $where[] = array("levelID = '$str'");
    } else {
        $where[] = "levelName LIKE '%$str%'";
    }
}
}

$query = "SELECT * FROM levels";
if(!empty($where)){
	$q .= " WHERE (" . implode(" ) AND ( ", $where) . ")";
}
if($order){
	$q .= "ORDER BY $order DESC";
}
$q .= " LIMIT 10 OFFSET $offset";
$query .= $q;
$query = $db->prepare($query);
$query->execute();

$cquery = $db->prepare("SELECT count(*) FROM levels $q"); 
$cquery->execute();
$count = $cquery->fetchColumn();


$result = $query->fetchAll();

foreach($result as &$level) {
	if($level["ID"]!=""){
		$levelMultiString .= $level["ID"].",";
		if(!empty($gauntlet)){
			$levelString .= "44:$gauntlet:";
		}
		$levelString .= "1:".$level["ID"].":2:".$level["levelName"].":5:".$level["levelVersion"].":6:".$level["accountID"].":8:10:9:".$level["starDifficulty"].":10:".$level["downloads"].":12:".$level["audioTrack"].":13:21:14:".$level["likes"].":17:".$level["starDemon"].":43:".$level["starDemonDiff"].":25:".$level["auto"].":18:".$level["stars"].":19:".$level["featured"].":42:".$level["epic"].":45:".$level["objects"].":3:".$level["levelDesc"].":15:".$level["levelLength"].":30:".$level["original"].":31:0:37:".$level["coins"].":38:".$level["coins"].":39:".$level["requestedStars"].":46:1:47:2:40:".$level["ldm"].":35:".$level["songID"]."|";
		if($level["songID"]!=0){
			$song = $main->getSongString($level["songID"]);
			if($song){
				$songString .= $main->getSongString($level["songID"]) . "~:~";
			}
		}
		$userString .= main::getUserString($level["accountID"])."|";
	}
}
$levelString = substr($levelString, 0, -1);
$levelMultiString = substr($levelMultiString, 0, -1);
$userString = substr($userString, 0, -1);
$songString = substr($songString, 0, -3);
echo $levelString."#".$userString;
echo "#".$songString;
echo "#".$count.":".$offset.":10";
echo "#";
echo main::genMulti($levelMultiString);