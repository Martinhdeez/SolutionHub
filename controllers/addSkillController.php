<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../auth/auth.php";
require_once "../config/Db.php";
require_once "../models/Skill.php";

    if(isset($_POST['addskill'])){
      
        $db = new Db();
        $skill = new Skill($db);

        $skill->user_id = $_SESSION['user_id'];
        $skill->title = $_POST['title'];
        $skill->title = strtoupper($skill->title);
        $skill->description = $_POST['description'];

        if($skill->create()){
            $_SESSION['success'] = "Skill added successfully";
        }else{
            $_SESSION['error'] = "Error: " . $skill->create();
            echo "Error: " . $skill->create();
        }
    }

    header("Location: ../views/myskills.php");
    exit();
