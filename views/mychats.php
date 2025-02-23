<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../auth/auth.php";
require_once "../config/Db.php";
require_once "../models/Chat.php";
require_once "../models/User.php";
require_once "../includes/parts/header.php"; 
?>   

<link rel="stylesheet" href="../assets/css/mychats.css">
</head> 
<body>
<?php
require_once "../includes/functions/functions.php"; 
require_once "../includes/parts/layout.php";

$db = new Db();
$chatModel = new Chat($db);
$userModel = new User($db);

$chats = $chatModel->getChatsByUser($_SESSION['user_id']);
?>
<div class="mychats-wrapper">
    <div class="chats-container">
        <header class="chat-header">
            <h2>My Chats</h2>
        </header>
        <?php foreach ($chats as $chat): 
            $receiver_id = ($chat['user_id1'] == $_SESSION['user_id']) ? $chat['user_id2'] : $chat['user_id1'];
            $user = $userModel->getUserById($receiver_id);
            $unread_count = $chatModel->countUnreadMessagesByChat($chat['id'], $_SESSION['user_id']);
        ?>
            <a href="chat.php?user_id1=<?= $chat['user_id1']; ?>&user_id2=<?= $chat['user_id2']; ?>&chat_id=<?= $chat['id']; ?>" class="chat-link">
                <div class="chat">
                    <div class="watch-info">
                        <div class="watch-details">
                            <h3 class="name"><?= htmlspecialchars($user['name']) . " " . htmlspecialchars($user['surname']); ?></h3>
                            <a href="userprofile.php?id=<?= $receiver_id ?>" class="social-link">See User Profile</a>
                        </div>
                    </div>
                    <!-- Punto rojo dentro del chat -->
                    <?php if ($unread_count > 0): ?>
                        <span class="notification-dot"></span>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
