<?php
class main {
    static function getIP() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            if($_SERVER['HTTP_CLIENT_IP'] == '::1') return '127.0.0.1';
            return $_SERVER['HTTP_CLIENT_IP'];
        } else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            if($_SERVER['HTTP_X_FORWARDED_FOR'] == '::1') return '127.0.0.1';
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if(!empty($_SERVER['REMOTE_ADDR'])) {
            if($_SERVER['REMOTE_ADDR'] == '::1') return '127.0.0.1';
            return $_SERVER['REMOTE_ADDR'];
        } else return null;
    }
    static function checkAccount($userName, $password) {
        include 'db.php';
        $query = $db->prepare("SELECT password, isBanned, isActive FROM accounts WHERE userName = :userName");
        $query->execute([':userName' => $userName]);
        if($query->rowCount() > 0) {
            $account = $query->fetch();
            if($account['isBanned'] != 1) {
                if($account['isActive'] == 1) {
                    if(password_verify($password, $account['password'])) return 1;
                    else return -1;
                } else return -3;
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
    static function getColor($badge) {
        if($badge == 1) return '255,178,0';
        else if($badge == 2) return '248,255,0';
        else return '255,255,255';
    }
    static function getRank($accountID) {
        include 'db.php';
        $query = $db->prepare("SELECT stars FROM accounts WHERE accountID = :ID");
        $query->execute([':ID' => $accountID]);
        $stars = $query->fetchColumn();
        $query = $db->prepare("SELECT count(*) FROM accounts WHERE stars > :stars AND isBanned = 0");
        $query->execute([':stars' => $stars]);
        if($query->rowCount() > 0) {
            return $query->fetchColumn() + 1;
        } else return 0;
    }
    static function getUserString($userdata) {
		include "/db.php";
		return $userdata['accountID'] . ':' . $userdata['userName'] . ':' . $userdata['accountID'];
	}
    static function getSongString($song) {
		include "/db.php";
		if($song['ID'] == 0 || empty($song['ID'])){
			return false;
		}
		//$song = $query3->fetch();
		$dl = $song["download"];
		if(strpos($dl, ':') !== false){
			$dl = urlencode($dl);
		}
		return "1~|~".$song["ID"]."~|~2~|~".str_replace("#", "", $song["name"])."~|~3~|~".$song["authorID"]."~|~4~|~".$song["authorName"]."~|~5~|~".$song["size"]."~|~6~|~~|~10~|~".$dl."~|~7~|~~|~8~|~1";
	}
    static function rateLevel($levelID, $stars, $diff, $feature) {
        require 'db.php';
        if($diff[1] == 1) {
            $query = $db->prepare("UPDATE levels SET rateTimestamp = :time, auto = 1, starDemon = 0, starStars = :stars, starDifficulty = :diff, featured = :featured WHERE levelID = :levelID");
        } else if($diff[1] == 2) {
            $query = $db->prepare("UPDATE levels SET rateTimestamp = :time, auto = 0, starDemon = 0, starStars = :stars, starDifficulty = :diff, featured = :featured WHERE levelID = :levelID");
        } else if($diff[1] == 3) {
            $query = $db->prepare("UPDATE levels SET rateTimestamp = :time, auto = 0, starDemon = 1, starStars = :stars, starDifficulty = :diff, featured = :featured WHERE levelID = :levelID");
        } else {
            $query = $db->prepare("UPDATE levels SET rateTimestamp = :time, auto = 0, starDemon = 0, starStars = :stars, starDifficulty = :diff, featured = :featured WHERE levelID = :levelID");
        }
        $query->execute([':time' => time(), ':stars' => $stars, ':diff' => $diff[0], ':featured' => $feature, ':levelID' => $levelID]);
    }
    static function suggestLevel($levelID, $diff) {
        require 'db.php';
        if($diff[1] == 1) {
            $query = $db->prepare("UPDATE levels SET auto = 1, starDemon = 0, starDifficulty = :diff WHERE levelID = :levelID");
        } else if($diff[1] == 2) {
            $query = $db->prepare("UPDATE levels SET auto = 0, starDemon = 0, starDifficulty = :diff WHERE levelID = :levelID");
        } else if($diff[1] == 3) {
            $query = $db->prepare("UPDATE levels SET auto = 0, starDemon = 1, starDifficulty = :diff WHERE levelID = :levelID");
        } else {
            $query = $db->prepare("UPDATE levels SET auto = 0, starDemon = 0, starDifficulty = :diff WHERE levelID = :levelID");
        }
        $query->execute([':diff' => $diff[0], ':levelID' => $levelID]);
    }
    static function getDiffFromStars($stars) {
        if($stars == 0) return array(0, 0);
        if($stars == 2) return array(10, 0);
        if($stars == 3) return array(20, 0);
        if($stars == 4 || $stars == 5) return array(30, 0);
        if($stars == 6 || $stars == 7) return array(40, 0);
        if($stars == 8 || $stars == 9) return array(50, 2);
        if($stars == 10) return array(50, 3);
        if($stars == 1) return array(50, 1);
    }
    static function genToken() {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        for($i = 0;$i<8;$i++) {
            $token .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $token;
    }
    static function checkEmail($email) {
        $allowed = array('yandex.ru', 'mail.ru', 'gmail.com');
        $domain = explode('@', $email)[1];
        if(in_array($domain, $allowed)) return false;
        else return true;
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
        $query = $db->prepare("SELECT password FROM accounts WHERE accountID = :ID AND isActive = 1");
        $query->execute([':ID' => $accountID]);
        if($query->rowCount() == 0) exit('-1');
        $hash = $query->fetchColumn();
        if(password_verify($gjpdecode, $hash)) return;
        else exit('-1');
    }
}

class Hash {
	static function genMulti($lvlsarray) {
		include "db.php";
		$hash = "";
		foreach($lvlsarray as $id){
			$query=$db->prepare("SELECT levelID, starStars, starCoins FROM levels WHERE levelID = :id");
			$query->execute([':id' => $id]);
			$result2 = $query->fetchAll();
			$result = $result2[0];
			$hash = $hash . $result["levelID"][0].$result["levelID"][strlen($result["levelID"])-1].$result["starStars"].$result["starCoins"];
		}
		return sha1($hash . "xI25fpAapCQg");
	}
    static function genSolo($levelstring) {
		$hash = "aaaaa";
		$len = strlen($levelstring);
		$divided = intval($len/40);
		$p = 0;
		for($k = 0; $k < $len ; $k= $k+$divided){
			if($p > 39) break;
			$hash[$p] = $levelstring[$k]; 
			$p++;
		}
		return sha1($hash . "xI25fpAapCQg");
	}
    static function genSolo2($lvlsmultistring) {
		return sha1($lvlsmultistring . "xI25fpAapCQg");
	}
}

class post {
    static function clear($str) {
        if(isset($str)) return $str;
        else exit('-1');
    }
    static function number($num) {
        if(isset($num)) return preg_replace("/[^0-9]/", '', $num);
        else exit('-1');
    }
}