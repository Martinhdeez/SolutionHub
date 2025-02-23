<?php
ini_set('display_errors', 1);   
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 require_once "../config/Db.php";
 require_once "../models/Skill.php";

if(isset($_POST['update'])){
    $db = new Db();
    $skill = new Skill($db);

    $skill->id = $_POST['id'];
    $skill->title = $_POST['title'];
    $skill->description = $_POST['description'];

    if($skill->update()){
        $_SESSION['success'] = "Skill updated successfully";
    }else{
        $_SESSION['error'] = "Error: " . $skill->update();
    }
    header("Location: ../views/skill.php?id=" . $_POST['id']);
    exit();
}

if(isset($_POST['delete'])){
    $db = new Db();
    $skill = new Skill($db);

    $skill->id = $_POST['id'];

    if($skill->delete()){
        $_SESSION['success'] = "Skill deleted successfully";
    }else{
        $_SESSION['error'] = "Error: " . $skill->delete();
    }
    header("Location: ../views/myskills.php");
    exit();
}
?>
