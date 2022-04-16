<?php
class main {
    static function getIP() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if(!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        } else return null;
    }
    static function checkAccount($userName, $password) {
        include 'db.php';
        $query = $db->prepare("SELECT password, isBanned FROM accounts WHERE userName = :userName");
        $query->execute([':userName' => $userName]);
        if($query->rowCount() > 0) {
            $account = $query->fetch();
            if($account['isBanned'] != 1) {
                if(password_verify($password, $account['password'])) return 1;
                else return -1;
            } else return -2;
        } else return -1;
    }
    static function getBadge($accountID) {
        include 'db.php';
        $query = $db->prepare("SELECT type FROM modbadges WHERE accountID = :accountID LIMIT 1");
        $query->execute([':accountID' => $accountID]);
        if($query->rowCount() > 0) {
            $badge = $query->fetchColumn();
            if($badge >= 2) return 2;
            return $badge;
        } else return -1;
    }
    static function getRank($accountID) {
        include 'db.php';
        $query = $db->prepare("SELECT stars FROM accounts WHERE ID = :ID");
        $query->execute([':ID' => $accountID]);
        $stars = $query->fetchColumn();
        $query = $db->prepare("SELECT count(*) FROM accounts WHERE stars > :stars AND isBanned = 0");
        $query->execute([':stars' => $stars]);
        if($query->rowCount() > 0) {
            return $query->fetchColumn() + 1;
        } else return 0;
    }
}

class GJP {
    static function decode($gjp) {
        require dirname(__FILE__).'/XORCipher.php';
        $cipher = new XORCipher();
        $gjpdecode = str_replace('_', '/', $gjp);
		$gjpdecode = str_replace('-', '+', $gjpdecode);
		$gjpdecode = base64_decode($gjpdecode);
		$gjpdecode = $cipher->cipher($gjpdecode, 37526);
        return $gjpdecode;
    }
    static function check($accountID, $gjp) {
        require dirname(__FILE__).'/db.php';
        $gjpdecode = GJP::decode($gjp);
        $query = $db->prepare("SELECT password FROM accounts WHERE ID = :ID");
        $query->execute([':ID' => $accountID]);
        $hash = $query->fetchColumn();
        if(password_verify($gjpdecode, $hash)) return;
        else exit('-1');
    }
}