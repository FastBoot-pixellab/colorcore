<?php
chdir(dirname(__FILE__));
include '../config/database.php';
try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
} catch(PDOException $error) {
    exit('Database connection error: '.$error);
}