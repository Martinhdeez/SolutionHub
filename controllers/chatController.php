<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Iniciar la sesión

require_once "../config/Db.php";
require_once "../models/Chat.php";

if($_SERVER['REQUEST_METHOD'] == "POST") {

    try {
        $db = new Db();

        $user_id1 = $_POST['user_id1'];
        $user_id2 = $_POST['user_id2'];
        // Corregir la consulta SQL con los operadores AND
        $stmt = $db->conn->prepare("SELECT * FROM chats WHERE user_id1 = ? AND user_id2 = ? OR user_id1 = ? AND user_id2 = ?");
        $stmt->execute([$user_id1, $user_id2, $user_id2, $user_id1]);

        if ($stmt->rowCount() > 0) {
            $chat = $stmt->fetch(PDO::FETCH_ASSOC);
            $chat_id = $chat['id'];
            echo "Chat exists\n";
            header("Location: ../views/chat.php?user_id1=$user_id1&user_id2=$user_id2&chat_id=$chat_id");
            exit();
        }

        if($user_id1 == $user_id2){
            header("Location: ../views/myskills.php");
            exit();
        }

    
        $chat = new Chat($db);

        $chat->user_id1 = $_POST['user_id1'];
        $chat->user_id2 = $_POST['user_id2'];

        if($chat->createChat()){
            $_SESSION['success'] = "Chat created successfully";
            header("Location: ../views/chat.php?user_id1=" . $_POST['user_id1'] . "&user_id2=" . $_POST['user_id2']."&chat_id=".$db->conn->lastInsertId());
            exit();
        }else{
            $_SESSION['error'] = "Error creating chat";
            header("Location: ../views/userprofile.php?id=" . $_POST['user_id1']);
            exit();
        }

    }catch (PDOException $e) {
        // Manejo de errores de base de datos
        echo "Database error: " . $e->getMessage();
    }
}