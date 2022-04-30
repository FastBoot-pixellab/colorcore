<?php
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
    static function check() {
        require dirname(__FILE__).'/db.php';
        //require dirname(__FILE__).'/main.php';
        $accountID = post::number($_POST['accountID']);
        $gjp = post::clear($_POST['gjp']);
        $gjpdecode = GJP::decode($gjp);
        $query = $db->prepare("SELECT password FROM accounts WHERE accountID = :ID AND isActive = 1");
        $query->execute([':ID' => $accountID]);
        if($query->rowCount() == 0) exit('-1');
        $hash = $query->fetchColumn();
        if(password_verify($gjpdecode, $hash)) return;
        else exit('-1');
    }
}