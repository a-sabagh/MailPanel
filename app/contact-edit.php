<?php
require_once './admin-setup/helper.php';
if (isset($_GET['id'])) {
    $contact_id = $_GET['id'];
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>MailPanel|EditContact</title>
            <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
            <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css"/>
            <link rel="stylesheet" href="assets/css/chosen.min.css"/>
            <link rel="stylesheet" href="assets/css/style.css"/>
            <?php
            include_once 'admin-setup/setup.php';
            include_once 'database.php';
            ?>
        </head>
        <body>
            <div class="vertical-space"></div>
            <div class="row">
                <div class="container">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Mail Panel</b></div><!--.panel-heading-->
                        <div class="panel-body">
                            <form class="edit-contact" action="contact-update.php" method="post">
                                <?php
                                $statement = "SELECT * FROM contacts WHERE id=:id";
                                $stmt = $dbh->prepare($statement);
                                $stmt->bindParam(":id", $contact_id);
                                $stmt->execute();
                                while ($row_obj = $stmt->fetchObject()) {
                                    ?>
                                    <div class="form-group">
                                        <label for="firstname">FirstName:</label>
                                        <input id="firstname" name="firstname" class="form-control" placeholder="" type="text" value="<?php echo $row_obj->firstname; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="lastname">LastName:</label>
                                        <input id="lastname" name="lastname" class="form-control" placeholder="" type="text" value="<?php echo $row_obj->lastname; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input id="email" name="email" class="form-control" placeholder="" type="text" value="<?php echo $row_obj->email; ?>">
                                    </div>
                                    <input type="text" class="hidden" name="id" value="<?php echo $row_obj->id; ?>" >
                                <?php } ?>
                                <input type="submit" class="btn btn-primary" name="save_changes" value="Save Changes" > 
                            </form><!--.mail-config-->
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
} else {
    app_redirect("index.php");
} 
