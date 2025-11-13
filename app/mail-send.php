<?php

require_once './admin-setup/helper.php';

if (isset($_POST['send_mail'])) {
    include_once 'includes/class.phpmailer.php';
    include_once 'includes/class.smtp.php';
    include_once 'database.php';
    $query = "SELECT * FROM configuration";
    $result = $dbh->query($query);
    $row_obj = $result->fetchObject();
    $host = $row_obj->host;
    $smtpsecure = $row_obj->smtpsecure;
    $port = $row_obj->port;
    $username = $row_obj->username;
    $password = $row_obj->password;
    $to = (isset($_POST['to'])) ? $_POST['to'] : '';
    $from = (isset($_POST['from'])) ? strip_tags(htmlspecialchars($_POST['from'])) : '';
    $subject = (isset($_POST['subject']))? $_POST['subject'] : '';
    $cc = (isset($_POST['cc'])) ? $_POST['cc'] : '';
    $bcc = (isset($_POST['bcc'])) ? $_POST['bcc'] : '';
    $reply_to = (isset($_POST['reply_to'])) ? strip_tags(htmlspecialchars($_POST['reply_to'])) : '';
    $body = (isset($_POST['body'])) ? $_POST['body'] : '';
    $attach = (isset($_FILES['attachment'])) ? $_FILES['attachment']['tmp_name'] : '';
    $from_array = explode(',', $from);
    $from_name = $from_array[0];
    $from_mail = $from_array[1];
    $mail = new PHPMailer(TRUE);
    $mail->isSMTP();
    $mail->isHTML(TRUE);
    $mail->setFrom($from_mail , $from_name);
    $mail->SMTPAuth = TRUE;
    $mail->Host = $host;
    $mail->SMTPSecure = $smtpsecure;
    $mail->Port = $port;
    $mail->Username = $username;
    $mail->Password = $password;
    $count = count($to);
    for ($i = 0; $i < $count; $i++) {
        $mail->addAddress($to[$i]);
    }
    if (isset($_POST['cc'])) {
        $count = count($cc);
        for ($i = 0; $i < $count; $i++) {
            $mail->addCC($cc[$i]);
        }
    }
    if (isset($_POST['bcc'])) {
        $count = count($bcc);
        for ($i = 0; $i < $count; $i++) {
            $mail->addBCC($bcc[$i]);
        }
    }
    $mail->addReplyTo($reply_to);
    $mail->Subject = $subject;
    $mail->Body = $body;
    (!empty($attach)) ? $mail->addAttachment($attach , "attach.jpg") : TRUE;
    try {
        $result = $mail->send();
        if ($result) {
            app_redirect("index.php?send=true");
        } else {
            app_redirect("index.php?send=false");
        }
    } catch (phpmailerException $ex) {
        echo $ex->errorMessage();
    }
} else {
    app_redirect("index.php");
}
