<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$gjp = post::clear($_POST['gjp']);
$getSent = post::number(isset($_POST["getSent"]) ? $_POST["getSent"] : 0);
$page = $_POST["page"];
$offset = $page*10;
if($getSent == 0){
	$q = "SELECT * FROM friendreq WHERE toID = :accountID LIMIT 10 OFFSET $offset";
	$countquery = "SELECT count(*) FROM friendreq WHERE toID = :accountID";
} else if($getSent == 1) {
	$q = "SELECT * FROM friendreq WHERE fromID = :accountID LIMIT 10 OFFSET $offset";
	$countquery = "SELECT count(*) FROM friendreq WHERE fromID = :accountID";
}
$query = $db->prepare($q);
$query->execute([':accountID' => $accountID]);
$result = $query->fetchAll();
$countquery = $db->prepare($countquery);
$countquery->execute([':accountID' => $accountID]);
$reqcount = $countquery->fetchColumn();
if($reqcount == 0) exit('-2');
$reqstring = '';
foreach($result as $request) {
	if($getSent == 0) {
		$requester = $request["fromID"];
	} else if($getSent == 1) {
		$requester = $request["toID"];
	}
	$query = $db->prepare("SELECT * FROM accounts WHERE accountID = :requester");
	$query->execute([':requester' => $requester]);
	$result2 = $query->fetchAll();
	$user = $result2[0];
	$uploadTime = date("d/m/Y G.i", $request["timestamp"]);
	$extid = $user["accountID"];
	$reqstring .= "1:".$user["userName"].":2:".$user["accountID"].":9:".$user["icon"].":10:".$user["color1"].":11:".$user["color2"].":14:".$user["iconType"].":15:".$user["special"].":16:".$extid.":32:".$request["friendreqID"].":35:".$request["comment"].":41:".$request["isNew"].":37:".$uploadTime."|";
}
$reqstring = substr($reqstring, 0, -1);
echo $reqstring."#${reqcount}:${offset}:10";