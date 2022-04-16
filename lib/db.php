<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

chdir(dirname(__FILE__));
include '../config/database.php';
try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
} catch(PDOException $error) {
    exit('Database connection error: '.$error);
}