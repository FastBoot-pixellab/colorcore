<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$page = post::number($_POST['page']);

GJP::check();
$offset = $page * 10;
if(!isset($_POST["getSent"]) || $_POST["getSent"] != 1) {
	$query = "SELECT * FROM messages WHERE toID = :to ORDER BY messageID DESC LIMIT 10 OFFSET $offset";
	$countquery = "SELECT count(*) FROM messages WHERE toID = :to";
	$getSent = 0;
} else {
	$query = "SELECT * FROM messages WHERE fromID = :to ORDER BY messageID DESC LIMIT 10 OFFSET $offset";
	$countquery = "SELECT count(*) FROM messages WHERE fromID = :to";
	$getSent = 1;
}
$query = $db->prepare($query);
$query->execute([':to' => $accountID]);
$result = $query->fetchAll();
$countquery = $db->prepare($countquery);
$countquery->execute([':to' => $accountID]);
$msgcount = $countquery->fetchColumn();
if($msgcount == 0) exit('-2');
$msgstring = "";
foreach($result as $message) {
	if($message['messageID'] != '') {
		$uploadDate = main::getTime(time() - $message['timestamp']);
		if($getSent == 1) {
			$accountID = $message['toID'];
		} else {
			$accountID = $message['fromID'];
		}
		$query=$db->prepare("SELECT * FROM accounts WHERE accountID = :accountID");
		$query->execute([':accountID' => $accountID]);
		$result2 = $query->fetchAll()[0];
		$msgstring .= '6:'.$result2['userName'].':3:'.$result2['accountID'].":2:".$result2['accountID'].":1:".$message['messageID'].":4:".$message['subject'].":8:".$message['isNew'].':9:'.$getSent.':7:'.$uploadDate.'|';
	}
}
$msgstring = substr($msgstring, 0, -1);
echo $msgstring.'#'.$msgcount.':'.$offset.':10';