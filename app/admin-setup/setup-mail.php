<?php
require_once './helper.php';

if (isset($_POST['save_configuration']) && file_exists("config.php")) {
    include_once 'config.php';
    $smtpsecure = $_POST['smtpsecure'];
	$host = $_POST['host'];
	$imap_path = $_POST['imap_path'];
	$port = $_POST['port'];
	$username = $_POST['username'];
	$password = $_POST['password'];
    $dbh = new PDO("mysql:host=" . HOST . ";dbname=" . DB, USER, PASSWORD);
	$statement = "INSERT INTO configuration ( smtpsecure , host , imap_path , port , username , password )"
		. "VALUES ( :smtpsecure , :host , :imap_path , :port , :username , :password )";
	$stmt = $dbh->prepare($statement);
	$stmt->bindParam(":smtpsecure" , $smtpsecure);
	$stmt->bindParam(":host" , $host);
	$stmt->bindParam(":imap_path" , $imap_path);
	$stmt->bindParam(":port" , $port);
	$stmt->bindParam(":username" , $username);
	$stmt->bindParam(":password" , $password);
	$result = $stmt->execute();
	if($result){
		app_redirect("admin-setup/setup-user.php");
	}else{
		echo "Error With saving Configuration";
	}
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SetupConfig</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" >
        <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css" >
        <style>
            br{
                margin: 15px 0;
                display: block;
                width: 100%;
            }
            @media screen and (min-width: 980px){
                .jumbotron{
                    min-height: 350px;
                }
            }

        </style>
    </head>
    <body>
        <div class="container">
            <br><br><br>
            <h2 class="text-center">Mail Configuration</h2><br>
            <div class="jumbotron">
                <br><br>
                <form action="" method="post">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-3"><b>SMTPSecure:</b></div>
                        <div class="col-md-7">
                            <input id="smtpsecure" name="smtpsecure" class="form-control" type="text">
                        </div><br>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-3"><b>Host :</b></div>
                        <div class="col-md-7">
                            <input id="host" name="host" class="form-control" type="text">
                        </div><br>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-3"><b>IMAP Path :</b></div>
                        <div class="col-md-7">
                            <input id="imap-path" name="imap_path" class="form-control" type="text">
                        </div><br>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-3"><b>Port :</b></div>
                        <div class="col-md-7">
                            <input id="port" name="port" class="form-control" type="text">
                        </div><br>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-3"><b>Username :</b></div>
                        <div class="col-md-7">
                            <input id="username" name="username" class="form-control" type="text">
                        </div><br>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-3"><b>Password :</b></div>
                        <div class="col-md-7">
                            <input id="password" name="password" class="form-control" type="text">
                        </div><br>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-1 col-md-11">
                            <br><br> 
                            <input type="submit" name="save_configuration" class="btn btn-default" value="Save Configuration" >
                        </div>
                    </div>
                </form>
            </div><!--.jumbotron-->
        </div><!--.container-->
    </body>
</html>
