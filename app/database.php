<?php
$filename = "admin-setup/config.php";
if(file_exists($filename)){
    include_once "admin-setup/config.php";
    $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DB , USER , PASSWORD);
}else{
    echo "Database Configuration not set";
}