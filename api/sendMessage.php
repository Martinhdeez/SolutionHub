<?php
require_once "../config/Db.php";
require_once "../models/Chat.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['chat_id'], $data['sender_id'], $data['receiver_id'], $data['message'])) {
    echo json_encode(["error" => "Missing parameters"]);
    exit();
}

$chat_id = $data['chat_id'];
$sender_id = $data['sender_id'];
$receiver_id = $data['receiver_id'];
$message = trim($data['message']);

if ($message === "") {
    echo json_encode(["error" => "Message cannot be empty"]);
    exit();
}

$db = new Db();
$chat = new Chat($db);

$chat->sendMessage($chat_id, $sender_id, $receiver_id, $message);
echo json_encode(["success" => "Message sent"]);
?>
