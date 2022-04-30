<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$messageID = post::number($_POST['messageID']);

GJP::check();
$query = $db->prepare("SELECT * FROM messages WHERE messageID = :id AND (toID = :accID OR fromID = :accID) LIMIT 1");
$query->execute([':id' => $messageID, ':accID' => $accountID]);
$result = $query->fetch();
if($query->rowCount() == 0) exit('-1');
if(empty($_POST["isSender"])) {
	$query=$db->prepare("UPDATE messages SET isNew = 1 WHERE messageID = :messageID AND toID = :accID");
	$query->execute([':messageID' => $messageID, ':accID' =>$accountID]);
	$accountID = $result['fromID'];
	$isSender = 0;
} else {
	$isSender = 1;
	$accountID = $result['toID'];
}
$query = $db->prepare("SELECT * FROM accounts WHERE accountID = :accountID");
$query->execute([':accountID' => $accountID]);
$result2 = $query->fetch();
$uploadDate = main::getTime(time() - $result['timestamp']);
echo '6:'.$result2['userName'].':3:'.$result2['accountID'].':2:'.$result2['accountID'].':1:'.$result['messageID'].':4:'.$result['subject'].':8:'.$result['isNew'].':9:'.$isSender.':5:'.$result['body'].':7:'.$uploadDate;