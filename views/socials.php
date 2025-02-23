<?php
require_once '../config/Db.php';
require_once '../includes/parts/header.php';
require_once '../includes/parts/layout.php';
require_once '../auth/auth.php'; // Incluir autenticación

$db = new Db();
$conn = $db->connect(); // Conectar a la BD

// Obtener el user_id del usuario autenticado si no se pasa por GET
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : $_SESSION['user_id'];
$auth_user_id = $_SESSION['user_id']; // ID del usuario autenticado
    
// Consultar la columna `socials`
$sql = "SELECT socials FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$socials_json = $stmt->fetchColumn(); // Obtener solo la columna `socials`

// Decodificar JSON a array
$socials_array = json_decode($socials_json, true);

// Verificar si el usuario tiene redes sociales registradas
$hasSocials = !empty($socials_array) && is_array($socials_array);
?>

<link rel="stylesheet" href="../assets/css/socials.css">

<div class="socials-container">
    <h2>GitHub</h2>
    <?php if ($hasSocials): ?>
        <ul>
            <?php foreach ($socials_array as $social): ?>
                <div class="link-style">
                    <li>
                        <a href="<?= htmlspecialchars($social) ?>" target="_blank"><?= htmlspecialchars($social) ?></a>
                    </li>
                </div>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="comment-style">
            <p>This user has no GitHub connected</p>
        </div>
    <?php endif; ?>
    
    <!-- Mostrar botones solo si el usuario autenticado está viendo su propio perfil -->
    <?php if ($user_id === $auth_user_id): ?>
        <?php if (!$hasSocials): ?>
            <a href="addsocial.php" class="btn-primary">Add GitHub</a>
        <?php else: ?>
            <div class="btn-group">
                <button onclick="window.location.href='editSocial.php'" class="btn-primary">Edit GitHub</button>
                <form action="../controllers/removeSocialController.php" method="POST">
                    <button type="submit" class="btn-primary">Delete GitHub</button>
                </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
