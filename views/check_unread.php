<?php
require_once "../config/Db.php";
require_once "../models/Chat.php";
session_start();

$db = new Db();
$chat = new Chat($db->conn);
$hasUnread = $chat->hasUnreadMessages($_SESSION['user_id']);

echo json_encode(["hasUnread" => $hasUnread]);
?>
