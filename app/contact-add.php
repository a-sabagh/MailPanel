<?php 
require_once './admin-setup/helper.php';
include_once 'database.php';
if(isset($_POST["add_contact"])){
    $firstname = strip_tags(htmlspecialchars($_POST['firstname']));
    $lastname = strip_tags(htmlspecialchars($_POST['lastname']));
    $email = strip_tags(htmlspecialchars($_POST['email']));
    $statement = "INSERT INTO contacts(firstname , lastname , email ) VALUES(:firstname , :lastname , :email )";
    $stmt = $dbh->prepare($statement);
    $stmt->bindParam(":firstname" , $firstname);
    $stmt->bindParam(":lastname" , $lastname);
    $stmt->bindParam(":email" , $email);
    $result = $stmt->execute();
    if($result){
        app_redirect("index.php");
    }else{
        echo "Error in adding contact";
    }
}else{
    app_redirect("index.php");
}