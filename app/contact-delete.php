<?php
require_once './admin-setup/helper.php';
if(isset($_GET['id'])){
    include_once 'database.php';
    $contact_id = $_GET['id'];
    $statement = "DELETE FROM contacts WHERE id=:id";
    $stmt = $dbh->prepare($statement);
    $stmt->bindParam(":id" , $contact_id);
    $result = $stmt->execute();
    if($result){
        app_redirect("index.php");
    }else{
        echo "Error with deleting contact";
    }
}else{
    app_redirect("index.php");
}