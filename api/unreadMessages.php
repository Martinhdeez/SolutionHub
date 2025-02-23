<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../config/Db.php";
require_once "../models/Chat.php";

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['unread_count' => 0]);
    exit;
}

$db = new Db();
$chatModel = new Chat($db);

$unreadCount = $chatModel->countUnreadMessages($_SESSION['user_id']);

echo json_encode(['unread_count' => $unreadCount]);
?>
