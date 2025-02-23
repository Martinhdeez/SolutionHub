<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once "../config/Db.php";
require_once "../models/Skill.php";
require_once "../models/Chat.php";
require_once "../models/User.php"; 
require_once "../includes/parts/header.php";
?>   
<link rel="stylesheet" href="../assets/css/chat.css">
</head> 
<body>
<?php
require_once "../includes/functions/functions.php"; 
require_once "../includes/parts/layout.php";

// Obtener chat y usuarios desde la URL
$user_id1 = $_GET['user_id1'] ?? null;
$user_id2 = $_GET['user_id2'] ?? null;
$chat_id = $_GET['chat_id'] ?? null;

if (!$chat_id || !$user_id1 || !$user_id2) {
    die("Error: Chat not found");
}

// Identificar el usuario autenticado
$session_user_id = $_SESSION['user_id'] ?? null;
if (!$session_user_id || ($session_user_id != $user_id1 && $session_user_id != $user_id2)) {
    die("Error: Unauthorized access.");
}

// Determinar el otro usuario en el chat
$other_user_id = ($session_user_id == $user_id1) ? $user_id2 : $user_id1;

$db = new Db();
$chat = new Chat($db);
$userModel = new User($db);

// Obtener información del otro usuario
$other_user = $userModel->getUserById($other_user_id);

// Enviar mensaje
if (isset($_POST['send']) && !empty($_POST['message'])) {
    $message = trim($_POST['message']);
    $chat->sendMessage($chat_id, $session_user_id, $other_user_id, $message);

    // Redirigir para evitar reenvío del formulario
    header("Location: chat.php?user_id1=$user_id1&user_id2=$user_id2&chat_id=$chat_id");
    exit();
}

$messages = $chat->getMessages($chat_id);
$chat_id = (int) $_GET['chat_id'];
$session_user_id = (int) $_SESSION['user_id'];

$sql = "UPDATE messages SET is_read = 1 WHERE chat_id = $chat_id AND receiver_id = $session_user_id AND is_read = 0";
$db->conn->query($sql);

?>

<div class="chat-container">
    <header class="chat-header">
        <div class="watch-info">
            <div class="watch-details">
                <h3 class="name"><?= htmlspecialchars($other_user['name']." ".$other_user['surname']); ?></h3>
            </div>
        </div>
    </header>

    <section class="chat-window">
        <div class="chat-messages" id="chatMessages">
            <?php foreach ($messages as $message): ?>
                <div class="message <?= ($message['sender_id'] == $session_user_id) ? 'sent' : 'received'; ?>">
                    <p><?= htmlspecialchars($message['message']); ?></p>
                    <span class="time"><?= date("H:i", strtotime($message['timestamp'])); ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <form class="chat-input" method="post">
            <input type="text" id="messageInput" name="message" placeholder="Type a message..." required>
            <button type="submit" name="send">Send</button>
        </form>
    </section>
</div>

<script>
    const chatMessages = document.getElementById("chatMessages");
    const chatId = "<?= $chat_id ?>";
    const userId = "<?= $session_user_id ?>";

    function actualizarChat() {
        fetch(`../api/getMessages.php?chat_id=${chatId}`)
            .then(response => response.json())
            .then(messages => {
                chatMessages.innerHTML = "";
                messages.forEach(message => {
                    const div = document.createElement("div");
                    div.classList.add("message", message.sender_id == userId ? "sent" : "received");
                    div.innerHTML = `
                        <p>${message.message}</p>
                        <span class="time">${new Date(message.updated_at).toLocaleTimeString("es-ES", { hour: "2-digit", minute: "2-digit", hour12: false })}</span>
                    `;
                    chatMessages.appendChild(div);
                });

                chatMessages.scrollTop = chatMessages.scrollHeight;
            });
    }

    setInterval(actualizarChat, 2000);
    actualizarChat();

    document.querySelector(".chat-input").addEventListener("submit", function(event) {
        event.preventDefault();
        const mensajeInput = document.getElementById("messageInput");
        const mensaje = mensajeInput.value.trim();
        if (mensaje === "") return;

        fetch("../api/sendMessage.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                chat_id: chatId,
                sender_id: userId,
                receiver_id: "<?= $other_user_id ?>",
                message: mensaje
            })
        }).then(() => {
            mensajeInput.value = "";
            actualizarChat();
        });
    });

    document.addEventListener("DOMContentLoaded", () => {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    });
</script>

</body>
</html>
