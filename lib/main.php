<?php
class main {
    function getIP() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if(!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        } else return null;
    }
    function checkAccount($userName, $password) {
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
}