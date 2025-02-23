<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "../config/Db.php";
require_once "../models/Solution.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Db();
    $solutionObj = new Solution($db->conn);
    if (isset($_POST['like'])) {
        $solutionObj->updateLikeDislike($_SESSION['user_id'], $_GET['id'] ,'like');
    }
    if (isset($_POST['dislike'])) {
        $solutionObj->updateLikeDislike($_SESSION['user_id'],$_GET['id'] ,'dislike');
    }
    $solution_id = $_GET['id'];
}
header("Location: ../views/solutionprofile.php?id=$solution_id");
exit();
?>
