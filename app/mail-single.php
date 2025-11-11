<?php include_once 'admin-setup/setup.php'; ?> 
<?php if(isset($_GET['message_id'])){?>
<!DOCTYPE html>
<html>
    <head>
        <title>MailPanel</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="assets/css/chosen.min.css"/>
        <link rel="stylesheet" href="assets/css/style.css"/>
        <?php include_once 'database.php'; ?>
    </head>
    <body>
        <div class="vertical-space"></div>
        <div class="row">
            <div class="container">
                <div class="panel panel-info">
                    <div class="panel-heading"><b>Mail Panel</b></div><!--.panel-heading-->
                    <div class="panel-body">
						<?php
						$query = "SELECT * FROM configuration LIMIT 1";
						$result = $dbh->query($query);
						$row_obj = $result->fetchObject();
						$imapPath = $row_obj->imap_path;
						$username = $row_obj->username;
						$password = $row_obj->password; 
						$inbox = imap_open($imapPath , $username , $password);
						$emails = imap_search($inbox , "ALL");
						$message_id = $_GET['message_id'];
						foreach($emails as $key=>$email){
							$header_info = imap_headerinfo($inbox , $email);
							if($header_info->message_id == $message_id){
						?>
								<h3>toAddress:</h3>
								<p><?php echo $header_info->toaddress; ?></p>
								<h3>fromAddress:</h3>
								<p><?php echo $header_info->fromaddress; ?></p>
								<h3>Subject:</h3>
								<p><?php echo $header_info->subject; ?></p>
								<h3>BodyText:</h3>
								<?php 
								$mail_body = imap_fetchbody($inbox, $email , 1);
								?>
								<p class="body-message"><?php echo $mail_body; ?></p>
						<?php
							}
						}
						?>
                        <a href="index.php" class="btn btn-info btn-block" >Back To Home</a>
                    </div><!--.panel-body-->
                </div><!--.panel-body-->
            </div><!--.container-->
        </div><!--.row-->
        <script src="assets/js/jquery-3.2.1.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/chosen.jquery.min.js"></script>
        <script src="assets/js/tinymce.min.js"></script>
        <script src="assets/js/scripts.js"></script>
    </body>
</html>
<?php
}else{
	app_redirect("index.php");
}
?>
