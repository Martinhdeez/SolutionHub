<?php
require_once "../auth/auth.php";
require_once "../config/Db.php";

$db = new Db();
$conn = $db->connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $github = trim($_POST["description"]);

    // Validar que la URL sea válida
    if (!filter_var($github, FILTER_VALIDATE_URL)) {
        die("URL no válida");
    }

    // Guardar la nueva URL en la base de datos
    $sql = "UPDATE users SET socials = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([json_encode([$github]), $_SESSION['user_id']]);

    header("Location: ../views/socials.php");
    exit();
}

