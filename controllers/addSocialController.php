<?php
session_start(); // Iniciar sesión
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/Db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new Db();
        $conn = $db->connect();

        $user_id = intval($_SESSION['user_id']); // Obtener el ID del usuario desde la sesión
        $new_social_url = trim($_POST['description']); // Tomar la URL del formulario
        $new_social_platform = "Otro"; // Nombre genérico para la plataforma


        // Validación básica
        if (empty($new_social_url)) {
            $_SESSION['error'] = "La URL no puede estar vacía.";
            header("Location: ../views/addsocial.php");
            exit();
        }

        // Obtener las redes sociales actuales
        $sql = "SELECT socials FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        $socials_json = $stmt->fetchColumn();
        
        $socials = $socials_json ? json_decode($socials_json, true) : [];
        
        // Agregar nueva red social con clave automática
        $socials[] = $new_social_url;
        $updated_socials_json = json_encode($socials);

        // Actualizar en la base de datos
        $update_sql = "UPDATE users SET socials = ?, updated_at = NOW() WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->execute([$updated_socials_json, $user_id]);

        $_SESSION['success'] = "Red social agregada con éxito.";

        header("Location: getIADatesController.php?url=" . $new_social_url);
        exit();

       // header("Location: ../views/socials.php?user_id=" . $user_id);
       // exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
        header("Location: ../views/addsocial.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Método no permitido.";
    header("Location: ../views/userprofile.php");
    exit();
}
