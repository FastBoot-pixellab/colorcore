<?php
class captcha {
    public static function show($type, $key) {
        if($type == 1) { //hcaptcha
            return "<script src='https://www.hCaptcha.com/1/api.js' async defer></script><div class='h-captcha' data-sitekey='$key'></div>";
        } else if($type == 2) { //recaptcha
            return "<script src='https://www.google.com/recaptcha/api.js'></script><div class='g-recaptcha' data-sitekey='$key'></div>";
        }
    }
}