<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/GJP.php';

$accountID = post::number($_POST['accountID']);
$type = post::number($_POST['type']);

GJP::check();
if($type == 0) { //friends
    $q = "SELECT * FROM friends WHERE person1 = :accountID OR person2 = :accountID";
} else if($type == 1) { //blocks
}
$query = $db->prepare($q);
$query->execute();
$users = $query->fetchAll();
if($query->rowCount() == 0) exit('-2');
$people = "";
$peoplestring = "";
$new = array();
foreach($users as $user) {
    $person = $user["person1"];
    $isnew = $user["isNew1"];
    if($user["person1"] == $accountID){
        $person = $user["person2"];
        $isnew = $user["isNew2"];
    }
    $new[$person] = $isnew;
    $people .= $person.',';
}
$people = substr($people, 0, -1);
$query = $db->prepare("SELECT * FROM accounts WHERE accountID IN ($people) ORDER BY userName ASC");
$query->execute();
$users = $query->fetchAll();
foreach($users as $user){
    $peoplestring .= '1:'.$user['userName'].':2:'.$user['accountID'].":9:".$user['icon'].':10:'.$user['color1'].':11:'.$user['color2'].':14:'.$user['iconType'].':15:'.$user['special'].':16:'.$user['accountID'].':18:0:41:'.$new[$user['accountID']].'|';
}
$peoplestring = substr($peoplestring, 0, -1);
$query = $db->prepare("UPDATE friends SET isNew1 = 0 WHERE person2 = :me");
$query->execute([':me' => $accountID]);
$query = $db->prepare("UPDATE friends SET isNew2 = 0 WHERE person1 = :me");
$query->execute([':me' => $accountID]);
if($peoplestring == '') exit("-1");
echo $peoplestring;