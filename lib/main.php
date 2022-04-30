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
    static function getTime($delta) {
        if ($delta < 31536000) {
			if ($delta < 2628000) {
				if ($delta < 604800) {
                    if ($delta < 86400) {
						if ($delta < 3600) {
							if ($delta < 60) {
								return $delta." second".($delta == 1 ? "" : "s");
							} else {
                        		$rounded = floor($delta / 60);
								return $rounded." minute".($rounded == 1 ? "" : "s");
							}
						} else {
							$rounded = floor($delta / 3600);
							return $rounded." hour".($rounded == 1 ? "" : "s");
						}
					} else {
						$rounded = floor($delta / 86400);
						return $rounded." day".($rounded == 1 ? "" : "s");
					}
				} else {
					$rounded = floor($delta / 604800);
					return $rounded." week".($rounded == 1 ? "" : "s");
				}
			} else {
				$rounded = floor($delta / 2628000); 
				return $rounded." month".($rounded == 1 ? "" : "s");
			}
		} else {
			$rounded = floor($delta / 31536000);
			return $rounded." year".($rounded == 1 ? "" : "s");
		}
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