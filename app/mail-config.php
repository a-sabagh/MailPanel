<?php
require_once './helper.php';

if (isset($_POST['save_configuration'])) {
    include_once 'database.php';
    $smtpsecure = strip_tags(htmlspecialchars($_POST['smtpsecure']));
    $host = strip_tags(htmlspecialchars($_POST['host']));
	$imap_path = strip_tags(htmlspecialchars($_POST['imap_path']));
    $port = strip_tags(htmlspecialchars($_POST['port']));
    $username = strip_tags(htmlspecialchars($_POST['username']));
    $password = strip_tags(htmlspecialchars($_POST['password']));
    $hash_pass = "KksnNu7r8d" . $password . "16zSmTQHkl";
    $statement = "UPDATE configuration SET "
            . " smtpsecure=:smtpsecure,"
            . " host=:host ,"
			. " imap_path=:imap_path ,"
            . " port=:port ,"
            . " username=:username ,"
            . " password=:password";
    $stmt = $dbh->prepare($statement);
    $stmt->bindParam(":smtpsecure" , $smtpsecure , PDO::PARAM_STR);
	$stmt->bindParam(":imap_path" , $imap_path);
    $stmt->bindParam(":host" , $host , PDO::PARAM_STR);
    $stmt->bindParam(":port" , $port , PDO::PARAM_INT);
    $stmt->bindParam(":username" , $username , PDO::PARAM_STR);
    $stmt->bindParam(":password" , $password , PDO::PARAM_STR);
    $result = $stmt->execute();
    if($result){
        app_redirect("index.php");
    }else{
        echo 'Error with save configuration';
    }
}else{
    app_redirect("index.php");
}
