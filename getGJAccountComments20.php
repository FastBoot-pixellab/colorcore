<?php
require 'lib/db.php';
require 'lib/main.php';

$accountID = post::number($_POST["accountID"]);
$page = post::number($_POST["page"]);

$commentpage = $page * 10;
$commentstr = '';
$query = $db->prepare("SELECT * FROM acccomments WHERE accountID = :accountID ORDER BY timestamp DESC LIMIT 10 OFFSET $commentpage");
$query->execute([':accountID' => $accountID]);
if($query->rowCount() == 0) exit('#0:0:0');
$comments = $query->fetchAll();
$countq = $db->prepare("SELECT count(*) FROM acccomments WHERE accountID = :accountID");
$countq->execute([':accountID' => $accountID]);
$commentcount = $countq->fetchColumn();
foreach($comments as $comment) {
    $date = date("d/m/Y G:i", $comment["timestamp"]);
    $commentstr .= '2~'.base64_encode($comment['comment']).'~3~'.$comment['accountID'].'~4~'.$comment['likes'].'~5~0~7~'.$comment['isSpam'].'~9~'.$date.'~6~'.$comment['ID'].'|';
}
$commentstr = substr($commentstr, 0, -1);
echo $commentstr.'#'.$commentcount.':'.$commentpage.':10';