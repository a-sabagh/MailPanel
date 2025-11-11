<?php
require_once './helper.php';

$filename = "config.php";
if(file_exists($filename)){
    include_once 'config.php';
    $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DB , USER , PASSWORD);
    $configuration = "CREATE TABLE IF NOT EXISTS configuration("
            . "id INT(1) NOT NULL AUTO_INCREMENT,"
            . "host VARCHAR(128) NOT NULL,"
			. "imap_path VARCHAR(128) NOT NULL,"
            . "smtpsecure VARCHAR(32) NOT NULL,"
            . "port INT(4) UNSIGNED NOT NULL,"
            . "username VARCHAR(128) NOT NULL,"
            . "password VARCHAR(128) NOT NULL,"
            . "PRIMARY KEY(id)"
            . ")";
    $dbh->query($configuration);
    $contacts = "CREATE TABLE IF NOT EXISTS contacts("
            . "id INT(3) NOT NULL AUTO_INCREMENT,"
            . "firstname VARCHAR(32) NOT NULL,"
            . "lastname VARCHAR(32) NOT NULL,"
            . "email VARCHAR(32) NOT NULL,"
            . "PRIMARY KEY(id)"
            . ")";
    $dbh->query($contacts);
    $user_query = "CREATE TABLE IF NOT EXISTS user("
            . "id INT(1) NOT NULL AUTO_INCREMENT,"
            . "username VARCHAR(128) NOT NULL,"
            . "password VARCHAR(128) NOT NULL,"
            . "identifier VARCHAR(128),"
            . "ip VARCHAR(32),"
            . "last_login DATETIME,"
            . "PRIMARY KEY (id)"
            . ")";
    $dbh->query($user_query);
    app_redirect("admin-setup/setup-mail.php");
}else{
    echo 'configuration is not successfully';
    app_redirect("index.php");
}