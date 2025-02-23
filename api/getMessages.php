<?php
require_once "../config/Db.php";
require_once "../models/Chat.php";

header("Content-Type: application/json");

if (!isset($_GET['chat_id'])) {
    echo json_encode(["error" => "Chat ID is required"]);
    exit();
}

$chat_id = $_GET['chat_id'];

$db = new Db();
$chat = new Chat($db);
$messages = $chat->getMessages($chat_id);

echo json_encode($messages);
?>
