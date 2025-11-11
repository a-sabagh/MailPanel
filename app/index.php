<?php 
include_once __DIR__ . '/admin-setup/helper.php'; 
include_once __DIR__ . '/admin-setup/setup.php'; 
include_once __DIR__ . '/database.php';
include_once __DIR__ . '/login.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>MailPanel</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="assets/css/chosen.min.css"/>
        <link rel="stylesheet" href="assets/css/style.css"/>
    </head>
    <body>
        <?php
        if (is_user_login()) {
            ?>
			<nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <span class="navbar-brand">Welcome <?php echo $_SESSION['mailpanel_username']; ?><a href="login.php?logged_out=true" > (logout) </a></span>
                    </div>
                </div>
            </nav>
            <div class="vertical-space"></div>
            <div class="row">
                <div class="container">
                    <div class="panel panel-default">
                        <div class="panel-heading"><b>Mail Panel</b></div><!--.panel-heading-->
                        <div class="panel-body">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#compose" data-toggle="tab" aria-expanded="false">Compose</a></li>
                                <li class=""><a href="#maillist" data-toggle="tab" aria-expanded="false">Inbox</a></li>
                                <li class=""><a href="#add-contact" data-toggle="tab" aria-expanded="false">AddContact</a></li>
                                <li class=""><a href="#contact" data-toggle="tab" aria-expanded="true">ContactsList</a></li>
                                <li class=""><a href="#configuration" data-toggle="tab" aria-expanded="false">Configuration</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="maillist" class="tab-pane">
                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>From</th>
                                                <th>Date</th>
                                                <th>Size</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM configuration LIMIT 1";
                                            $result = $dbh->query($query);
                                            $row_obj = $result->fetchObject();
                                            $imapPath = $row_obj->imap_path;
                                            $username = $row_obj->username;
                                            $password = $row_obj->password;
                                            $inbox = imap_open($imapPath, $username, $password);
                                            $emails = imap_search($inbox, "ALL");
                                            foreach ($emails as $key => $email) {
                                                $header_info = imap_headerinfo($inbox, $email);
                                                ?>
                                                <tr>
                                                    <td><?php echo $header_info->subject; ?></td>
                                                    <td><?php echo $header_info->fromaddress; ?></td>
                                                    <td><?php echo $header_info->date; ?></td>
                                                    <td><?php echo $header_info->Size . " Byte"; ?></td>
                                                    <td><a href="mail-single.php?message_id=<?php echo $header_info->message_id; ?>" title="SeeMail" >Mail Details</a></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div><!--.maillist-->
                                <div id="compose" class="tab-pane active">
                                    <form class="mail-send" action="mail-send.php" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="block" for="from">From:</label>
                                            <input id="from" name="from" class="form-control" placeholder="" type="text">
                                        </div>
                                        <div class="form-group">
                                            <label class="block" for="reply-to">Reply to:</label>
                                            <input name="reply_to" id="reply-to" class="form-control" placeholder="" type="text">
                                        </div>
                                        <div class="form-group">
                                            <label class="block" for="subject">Subject:</label>
                                            <input name="subject" id="subject" class="form-control" placeholder="" type="text">
                                        </div>
                                        <div class="form-group">
                                            <label for="to">To:</label>
                                            <select name="to[]" class="chosen-select" data-placeholder=" "  multiple="multiple">
                                                <?php
                                                $query = "SELECT * FROM contacts";
                                                $result = $dbh->query($query);
                                                while ($row_obj = $result->fetchObject()) {
                                                    echo '<option value="' . $row_obj->email . '">' . $row_obj->email . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="cc">Cc:</label>
                                            <select name="cc[]" class="chosen-select" data-placeholder=" "  multiple="multiple">
                                                <?php
                                                $query = "SELECT * FROM contacts";
                                                $result = $dbh->query($query);
                                                while ($row_obj = $result->fetchObject()) {
                                                    echo '<option value="' . $row_obj->email . '">' . $row_obj->email . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="bcc">Bcc:</label>
                                            <select name="bcc[]" class="chosen-select" data-placeholder=" "  multiple="multiple">
                                                <?php
                                                $query = "SELECT * FROM contacts";
                                                $result = $dbh->query($query);
                                                while ($row_obj = $result->fetchObject()) {
                                                    echo '<option value="' . $row_obj->email . '">' . $row_obj->email . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="body">Body:</label>
                                            <textarea name="body" id="body" class="tinymce"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="attachment">Attachment:</label>
                                            <input type="file" name="attachment"  >
                                        </div>
                                        <input class="btn btn-primary" name="send_mail" type="submit" value="Send Mail">
                                    </form>
                                </div><!--#compose-->
                                <div id="add-contact" class="tab-pane">
                                    <form class="add-contact" action="contact-add.php" method="post">
                                        <div class="form-group">
                                            <label for="firstname">FirstName:</label>
                                            <input id="firstname" name="firstname" class="form-control" placeholder="" type="text">
                                        </div>
                                        <div class="form-group">
                                            <label for="lastname">LastName:</label>
                                            <input id="lastname" name="lastname" class="form-control" placeholder="" type="text">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email:</label>
                                            <input id="email" name="email" class="form-control" placeholder="" type="text">
                                        </div>
                                        <input type="submit" class="btn btn-primary" name="add_contact" value="Add Contact" > 
                                    </form><!--.mail-config-->
                                </div><!--#add-contact-->
                                <div id="contact" class="tab-pane">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>FirstName</th>
                                                <th>LastName</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM contacts";
                                            $query_result = $dbh->query($query);
                                            while ($row_obj = $query_result->fetchObject()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row_obj->firstname; ?></td>
                                                    <td><?php echo $row_obj->lastname; ?></td>
                                                    <td><?php echo $row_obj->email; ?></td>
                                                    <td>
                                                        <a href="contact-edit.php?id=<?php echo $row_obj->id; ?>" title="edit contact">Edit</a> | <a href="contact-delete.php?id=<?php echo $row_obj->id; ?>" title="remove contact">Remove</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div><!--#contact-->
                                <div id="configuration" class="tab-pane">
                                    <?php
                                    $query = "SELECT * FROM configuration LIMIT 1";
                                    $result = $dbh->query($query);
                                    $row_obj = $result->fetchObject();
                                    ?>
                                    <form class="mail-config" action="mail-config.php" method="post">
                                        <div class="form-group">
                                            <label for="smtpsecure">SMTPSecure:</label>
                                            <input type="text" id="smtpsecure" name="smtpsecure" value="<?php echo $row_obj->smtpsecure; ?>" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="host">Host:</label>
                                            <input type="text" id="host" name="host" value="<?php echo $row_obj->host; ?>" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="impat-path">IMAP Path:</label>
                                            <input type="text" id="impat-path" name="imap_path" value="<?php echo $row_obj->imap_path; ?>" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="port">Port:</label>
                                            <input type="text" id="port" name="port" value="<?php echo $row_obj->port; ?>" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="username">Username:</label>
                                            <input type="text" id="username" name="username" value="<?php echo $row_obj->username; ?>" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password:</label>
                                            <input type="password" id="password" name="password" value="<?php echo $row_obj->password; ?>" class="form-control" placeholder="">
                                        </div>
                                        <input type="submit" class="btn btn-primary" name="save_configuration" value="Save Configuration" > 
                                    </form><!--.mail-config-->
                                </div><!--#Configuration-->

                            </div><!--.tab-content-->
                        </div><!--.panel-body-->
                    </div><!--.panel-body-->
                </div><!--.container-->
            </div><!--.row-->
            <?php
        } else {
            ?>
            <div class="vertical-space"><br></div>
            <div class="row login">
                <div class="col-md-4 hidden-sm"></div>
                <div class="col-md-4">
                    <form action="login.php" method="post">
                        <div class="panel panel-default">
                            <div class="panel-heading"><b>Login</b><span class="login-msg"><?php ?></span></div><!--.panel-header-->
                            <div class="panel-body">
                                <div class="form-group"><input type="text" name="username" class="form-control" placeholder="username"></div>
                                <div class="form-group"><input type="password" name="password" class="form-control" placeholder="password"></div>
                                <label><input type="checkbox" name="remember" class="remember" > Remember me</label>
                            </div><!--.panel-body-->
                            <div class="panel-footer clearfix">
                                <input class="btn btn-default pull-right" type="submit" name="login" value="Login">
                            </div><!--.panel-footer-->
                        </div><!--.panel-default-->
                    </form>
                </div>
                <div class="col-mx-4 hidden-sm"></div>
            </div><!--.login-->
            <?php
        }
        ?>
        <script src="assets/js/jquery-3.2.1.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/chosen.jquery.min.js"></script>
        <script src="assets/js/tinymce.min.js"></script>
        <script src="assets/js/scripts.js"></script>
    </body>
</html>

