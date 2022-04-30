<?php
require 'lib/main.php';
require 'lib/db.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$toID = post::number($_POST['toAccountID']);
$subject = post::clear($_POST['subject']);
$body = post::clear($_POST['body']);

GJP::check();
if($subject != '' && $body != '') {
    $query = $db->prepare("INSERT INTO messages (fromID, toID, subject, body, timestamp) VALUES (:from, :to, :subject, :body, :time)");
    $query->execute([':from' => $accountID, ':to' => $toID, ':subject' => $subject, ':body' => $body, ':time' => time()]);
    echo '1';
}