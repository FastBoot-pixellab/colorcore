<?php
require 'lib/db.php';
require 'lib/main.php';

$page = $_POST['page'];
$mode = $_POST['mode'];
$count = isset($_POST['count']) ? $_POST['count'] : 10;

$commentpage = $page * $count;
$commentstring = "";$userstring = "";$users = array();
$commentpage = $page * $count;
switch($mode) {
	case 0:
		$modeColumn = "commentID";
		break;
	default:
		$modeColumn = "likes";
		break;
}
if(isset($_POST['levelID'])) {
	$filterColumn = 'levelID';
	$displayLevelID = false;
	$filterID = $_POST["levelID"];
	$userListJoin = $userListWhere = $userListColumns = "";
} else if(isset($_POST['userID'])) {
	$filterColumn = 'accountID';
	$displayLevelID = true;
	$filterID = $_POST["userID"];
	$userListColumns = ", levels.unlisted";
	$userListJoin = "INNER JOIN levels ON comments.levelID = levels.levelID";
	$userListWhere = "AND levels.unlisted = 0";
} else exit('-1');
$query2 = $db->prepare("SELECT count(*) FROM levels WHERE $filterColumn = :filterID");
$query2->execute([':filterID' => $filterID]);
$commentcount = $query2->fetchColumn();
if($commentcount == 0) exit("-2");
$query = "SELECT comments.*, accounts.* FROM comments LEFT JOIN accounts ON comments.accountID = accounts.accountID ${userListJoin} WHERE comments.${filterColumn} = :filterID ${userListWhere} ORDER BY comments.${modeColumn} DESC LIMIT ${count} OFFSET ${commentpage}";
$query = $db->prepare($query);
$query->execute([':filterID' => $filterID]);
$result = $query->fetchAll();
$visiblecount = $query->rowCount();
//var_dump($result);
foreach($result as $comment) {
	$uploadDate = date("d/m/Y G.i", $comment["timestamp"]);
	$commentText = base64_decode($comment["comment"]);
	if($displayLevelID) $commentstring .= "1~".$comment["levelID"]."~";
	$commentstring .= "2~".$commentText."~3~".$comment["accountID"]."~4~".$comment["likes"]."~5~0~7~".$comment["isSpam"]."~9~".$uploadDate."~6~".$comment["commentID"]."~10~".$comment["percent"];
	if ($comment['userName']) {
		$extID = $comment['accountID'];
		$badge = main::getBadge($extID);
		$colorString = $badge > 0 ? "~12~255,255,255" : "";

		$commentstring .= "~11~${badge}${colorString}:1~".$comment["userName"]."~7~1~9~".$comment["icon"]."~10~".$comment["color1"]."~11~".$comment["color2"]."~14~".$comment["iconType"]."~15~".$comment["special"]."~16~".$comment["accountID"];
		// } else if(!in_array($comment["userID"], $users)){
		// 	$users[] = $comment["userID"];
		// 	$userstring .=  $comment["userID"] . ":" . $comment["userName"] . ":" . $extID . "|";
		// }
		$commentstring .= "|";
	}
}
$commentstring = substr($commentstring, 0, -1);
echo $commentstring;
// if($binaryVersion < 32){
// 	$userstring = substr($userstring, 0, -1);
// 	echo "#$userstring";
// }
echo "#${commentcount}:${commentpage}:${visiblecount}";