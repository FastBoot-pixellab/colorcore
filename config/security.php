<?php
/* GDPS account activation */

/*
    0 - Disable activation (login immediately after registration)
    1 - HCaptcha (activate here: http://yourhost.com/database/activateAccount.php)
    2 - reCAPTCHA (activate here: http://yourhost.com/database/activateAccount.php)
    3 - Mail (SMTP server required)
*/
$activationType = 0;

if($activationType == 1) {
    $HCaptchaKey = '';
    $HCaptchaSecret = ''; //0x...
}
if($activationType == 2) {
    $reCaptchaKey = '';
    $reCaptchaSecret = '';
}
if($activationType == 3) {
    $smtp_host = '';
    $smtp_port = 465;
    $smtp_mail = ''; //mail address
    $smtp_password = ''; //mail password
}