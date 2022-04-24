<?php
require "lib/db.php";
require "lib/main.php";

$type = $_POST['type'];
//$diff = $_POST['diff'];
$featured = $_POST['featured'];
$epic = $_POST['epic'];
$original = $_POST['original'];
$page = $_POST['page'];
//$len = $_POST['len'];
$str = $_POST['str'];
$twoPlayer = $_POST['twoPlayer'];

$lvlsmultistring = array();$lvlstring = "";$userstring = "";$songsstring = "";
$offset = $page * 10;
$qparams = array("unlisted = 0");
if($featured == 1) $qparams[] = "featured = 1";
if($epic == 1) $qparams[] = "epic = 1";
if($original == 1) $qparams[] = "original = 1";
if($twoPlayer == 1) $qparams[] = "twoPlayer = 1";
if($type == 2) { //params without anything
	$q = "SELECT * FROM levels WHERE (" . implode(") AND (", $qparams) . ") LIMIT 10 OFFSET $offset";
	$query = $db->prepare($q);
	$query->execute();
	$levels = $query->fetchAll();
	foreach($levels as $level) {
		$lvlsmultistring[] = $level["ID"];
		$lvlstring .= "1:".$level["ID"].":2:".$level["levelName"].":5:".$level["levelVersion"].":6:".$level["accountID"].":8:10:9:".$level["starDifficulty"].":10:".$level["downloads"].":12:".$level["audioTrack"].":13:21:14:".$level["likes"].":17:".$level["starDemon"].":43:".$level["starDemonDiff"].":25:".$level["auto"].":18:".$level["starStars"].":19:".$level["featured"].":42:".$level["epic"].":45:".$level["objects"].":3:".$level["levelDesc"].":15:".$level["levelLength"].":30:".$level["original"].":31:".$level['twoPlayer'].":37:".$level["coins"].":38:".$level["starCoins"].":39:".$level["requestedStars"].":46:1:47:2:40:".$level["ldm"].":35:".$level["songID"]."|";
		if($level["songID"] != 0) {
			$song = main::getSongString($level);
			if($song) {
				$songsstring .= $song . "~:~";
			}
			$userstring .= main::getUserString($level1)."|";
		}
	}
} else if($type == 0) { //search
	$q = "SELECT * FROM levels WHERE levelName LIKE :q AND (" . implode(" AND (", $qparams) . ") LIMIT 10 OFFSET $offset";
	$query = $db->prepare($q);
	$query->execute([':q' => '%'.$str.'%']);
	$levels = $query->fetchAll();
	foreach($levels as $level) {
		$lvlsmultistring[] = $level["ID"];
		$lvlstring .= "1:".$level["ID"].":2:".$level["levelName"].":5:".$level["levelVersion"].":6:".$level["accountID"].":8:10:9:".$level["starDifficulty"].":10:".$level["downloads"].":12:".$level["audioTrack"].":13:21:14:".$level["likes"].":17:".$level["starDemon"].":43:".$level["starDemonDiff"].":25:".$level["auto"].":18:".$level["starStars"].":19:".$level["featured"].":42:".$level["epic"].":45:".$level["objects"].":3:".$level["levelDesc"].":15:".$level["levelLength"].":30:".$level["original"].":31:".$level['twoPlayer'].":37:".$level["coins"].":38:".$level["starCoins"].":39:".$level["requestedStars"].":46:1:47:2:40:".$level["ldm"].":35:".$level["songID"]."|";
		if($level["songID"] != 0) {
			$song = main::getSongString($level);
			if($song) {
				$songsstring .= $song . "~:~";
			}
			$userstring .= main::getUserString($level1)."|";
		}
	}
}
$lvlstring = substr($lvlstring, 0, -1);
$userstring = substr($userstring, 0, -1);
$songsstring = substr($songsstring, 0, -3);
echo $lvlstring."#".$userstring;
echo '#'.$songsstring.'#'.$query->rowCount().':'.$offset.':10#';
echo Hash::genMulti($lvlsmultistring);