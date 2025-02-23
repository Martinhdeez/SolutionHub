<?php
require_once "../auth/auth.php";
require_once "../config/Db.php";

$db = new Db();
$conn = $db->connect();

// Eliminar la URL de la BD (dejar `socials` como NULL)
$sql = "UPDATE users SET socials = NULL WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);

header("Location: ../views/socials.php");
exit();

