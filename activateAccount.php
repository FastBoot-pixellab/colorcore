<?php
require 'lib/db.php';
require 'lib/main.php';
require 'lib/captcha.php';
include 'config/security.php';
echo "<style>
body {
    font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
}
</style>
<h2>Activate account</h2>";
if($activationType == 1) {
    //TODO: move verify functions to captcha.php
    if(isset($_POST['userName']) && $_POST['userName'] != '' && isset($_POST['password']) && $_POST['password'] != '' && isset($_POST['h-captcha-response'])) {
        $data = array(
            'secret' => $HCaptchaSecret,
            'response' => $_POST['h-captcha-response']
        );
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        $responseData = json_decode($response);
        if($responseData->success) {
            $userName = $_POST['userName'];
            $password = $_POST['password'];
            $check = main::checkAccount($userName, $password);
            if($check == -3) {
                $query = $db->prepare("UPDATE accounts SET isActive = 1 WHERE userName = :userName LIMIT 1");
                $query->execute([':userName' => $userName]);
                echo 'Account activated!';
            } else if($check == 1) echo "Account already activated";
            else echo 'Activation error';
        } else echo 'Captcha failed';
    } else echo "
    <form method='POST'>
    Username: <input type='text' name='userName'><br>
    Password: <input type='password' name='password'><br>
    ".captcha::show(1, $HCaptchaKey)."
    <input type='submit' value='Activate'>
    </form>
    ";
} else if($activationType == 2) {
    if(isset($_POST['userName']) && $_POST['userName'] != '' && isset($_POST['password']) && $_POST['password'] != '' && isset($_POST['g-recaptcha-response'])) {
        $data = array(
            'secret' => $reCaptchaSecret,
            'response' => $_POST["g-recaptcha-response"],
            'remoteip' => main::getIP(),
        );
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        if(json_decode($response, true)['success'] == '1') {
            $userName = $_POST['userName'];
            $password = $_POST['password'];
            $check = main::checkAccount($userName, $password);
            if($check == -3) {
                $query = $db->prepare("UPDATE accounts SET isActive = 1 WHERE userName = :userName LIMIT 1");
                $query->execute([':userName' => $userName]);
                echo 'Account activated!';
            } else if($check == 1) echo "Account already activated";
            else echo 'Activation error';
        } else echo 'Captcha failed';
    } else echo "
    <form method='POST'>
    Username: <input type='text' name='userName'><br>
    Password: <input type='password' name='password'><br>
    ".captcha::show(2, $reCaptchaKey)."
    <input type='submit' value='Activate'>
    </form>
    ";
} else if($activationType == 3) {
    if(isset($_GET['token'])) {
        $token = $_GET['token'];
        $query = $db->prepare("SELECT * FROM accounts WHERE token = :token");
        $query->execute([':token' => $token]);
        if($query->rowCount() > 0) {
            $query1 = $db->prepare("UPDATE accounts SET isActive = 1 WHERE token = :token");
            $query1->execute([':token' => $token]);
            echo 'Account activated!';
        } else echo 'Invalid token';
    } else echo "Token doesn't exist";
} else if($activationType == 0) echo "You don't need to activate account";
else echo "Invalid activation type";