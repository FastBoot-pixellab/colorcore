<?php
require 'lib/db.php';
require 'lib/main.php';

$levelID = $_POST['levelID'];

$query = $db->prepare("SELECT * FROM levels WHERE levelID = :levelID");
$query->execute([':levelID' => $levelID]);
$level = $query->fetch();
if(file_exists(dirname(__FILE__) . "/levels/$levelID.uwu")) {
    $levelstring = file_get_contents(__DIR__ . "/levels/$levelID.uwu");
} else exit('-1');
$uploadDate = date("d-m-Y G-i", $level['timestamp']);
$updateDate = date("d-m-Y G-i", $level["updateTimestamp"]);
$pass = $level["password"];
$xorPass = '';
$desc = $level["levelDesc"];
if($pass != 0) {
    $cipher = new XORCipher();
    $xorPass = base64_encode($cipher->cipher($pass, 26364));
}
if(substr($levelstring, 0, 3) == 'kS1'){
    $levelstring = base64_encode(gzcompress($levelstring));
    $levelstring = str_replace("/", "_", $levelstring);
    $levelstring = str_replace("+", "-", $levelstring);
}
$response = "1:".$level["levelID"].":2:".$level["levelName"].":3:".$level["levelDesc"].":4:".$levelstring.":5:".$level["levelVersion"].":6:".$level["accountID"].":8:10:9:".$level["starDifficulty"].":10:".$level["downloads"].":11:1:12:".$level["audioTrack"].":13:21:14:".$level["likes"].":17:".$level["starDemon"].":43:".$level["starDemonDiff"].":25:".$level["auto"].":18:".$level["starStars"].":19:".$level["featured"].":42:".$level["epic"].":45:".$level["objects"].":15:".$level["levelLength"].":30:".$level["original"].":31:".$level['twoPlayer'].":28:".$uploadDate. ":29:".$updateDate. ":35:".$level["songID"].":36:".$level["extraString"].":37:".$level["coins"].":38:".$level["starCoins"].":39:".$level["requestedStars"].":46:".$level["wt"].":47:".$level["wt2"].":48:1:40:".$level["ldm"].":27:$xorPass";
$response .= "#" . Hash::genSolo($levelstring) . "#";
$somestring = $level["accountID"].",".$level["starStars"].",".$level["starDemon"].",".$level["levelID"].",".$level["starCoins"].",".$level["featured"].",".$pass.",0";
$response .= Hash::genSolo2($somestring);
//$response .= "#" . $somestring;
$query = $db->prepare("UPDATE levels SET downloads = (downloads + 1) WHERE levelID = :levelID");
$query->execute([':levelID' => $levelID]);
echo $response;