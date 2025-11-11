<?php
session_start();

include_once 'admin-setup/setup.php'; 
include_once "admin-setup/config.php";

if (isset($_POST['login'])) {
    $username = strip_tags(htmlspecialchars($_POST['username']));
    $password = strip_tags(htmlspecialchars($_POST['password']));
    $remember = $_POST['remember'];
    do_login($username, $password, $remember);
}

if(isset($_GET['logged_out']) && $_GET['logged_out'] == 'true'){ do_logout(); }
function is_user_login() {
    if (isset($_SESSION['mailpanel_login'])) {
        return TRUE;
    } else {
		if(isset($_COOKIE['mailpanel_identifier'])){
			$statement = "SELECT * FROM user WHERE username=:username";
			$stat = $dbh->prepare($statement);
			$stat->bindParam(":username" , $username);
			$result = $stat->execute();
			if($result){
				$row_obj = $stat->fetchObject();
				$identifier = $row_obj->identifier;
				if($identifier == $_COOKIE['mailpanel_identifier']){
					return TRUE;
				}else{
					return FALSE;
				}
			}else{
				return FALSE;
			}			
		}else{
			return FALSE;
		}
    }
}

function do_login($username, $password, $remember) {
    $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DB, USER, PASSWORD);
    $statement = "SELECT * FROM user WHERE username=:username";
    $stat = $dbh->prepare($statement);
    $stat->bindParam(":username", $username);
    $result = $stat->execute();
    if ($result) {
        $row_obj = $stat->fetchObject();
        $check_password = (md5($password) == $row_obj->password) ? TRUE : FALSE;
        if ($check_password) {
            if (isset($remember)) {
                $_SESSION['mailpanel_login'] = 'true';
				$_SESSION['mailpanel_username'] = $username;
                $ip = $_SERVER['REMOTE_ADDR'];
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $salt = rng_random_string();
                $identifier = md5($ip . $salt . $user_agent);
                $statement = "UPDATE user SET identifier=:identifier WHERE username=:username";
                $stat = $dbh->prepare($statement);
                $stat->bindParam(":identifier", $identifier);
                $stat->bindParam(":username", $username);
                $result = $stat->execute();
                if ($result) {
                    setcookie('mailpanel_identifier', $identifier , time() + 3600);
                    setcookie('mailpanel_username', $username , time() + 3600);
                    app_redirect("index.php");
                }
            } else {
                $_SESSION['mailpanel_login'] = 'true';
				$_SESSION['mailpanel_username'] = $username;
                app_redirect("index.php");
            }
        }
		echo 'PASSWORD INCORRECT';
    } else {
        return FALSE;
    }
}

function rng_random_string(){
	$characters = "QWERTYUIOPQASDFGHJKLZXCVBNM";
	$characters .= "qwertyuiopasdfghjklzxcvbnm";
	$characters .= "1234567890";
	$characters_length = strlen($characters);
	$random_string = '';
	for($i=0;$i<7;$i++){
		$random_string .= $characters[rand(0,$characters_length -1 )];
	}
	return $random_string;
}

function do_logout(){
	if(isset($_COOKIE['mailpanel_username'])){
		$dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DB, USER, PASSWORD);
		$username = $_COOKIE['mailpanel_username'];
		$statement = "UPDATE user SET identifier='' WHERE username=:username";
		$stat = $dbh->prepare($statement);
		$stat->bindParam(":username" , $username);
		$stat->execute();		
	}
	unset($_SESSION['mailpanel_login'] , $_SESSION['mailpanel_username']);
	unset($_COOKIE['mailpanel_identifier']);
	unset($_COOKIE['mailpanel_username']);
	session_destroy();
	setcookie("mailpanel_identifier" , "" , -1);
	setcookie("mailpanel_username" , "" , -1);
	app_redirect("index.php");
}
