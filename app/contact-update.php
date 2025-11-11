<?php
require_once './helper.php';

if (isset($_POST['save_changes'])) {
    include_once 'database.php';
    $id = strip_tags(htmlspecialchars($_POST['id']));
    $firstname = strip_tags(htmlspecialchars($_POST['firstname']));
    $lastname = strip_tags(htmlspecialchars($_POST['lastname']));
    $email = strip_tags(htmlspecialchars($_POST['email']));
    $statement = "UPDATE contacts SET firstname=:firstname , lastname=:lastname , email=:email WHERE id=:id ";
    $stmt = $dbh->prepare($statement);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":firstname", $firstname);
    $stmt->bindParam(":lastname", $lastname);
    $stmt->bindParam(":email", $email);
    $result = $stmt->execute();
    if ($result) {
        app_redirect("index.php");
    } else {
        echo "Error with saving your changes";
    }
} else {
    app_redirect("index.php");
}
