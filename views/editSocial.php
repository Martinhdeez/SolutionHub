<?php
require_once "../auth/auth.php";
require_once "../config/Db.php";
require_once "../includes/parts/header.php";

$db = new Db();
$conn = $db->connect();

// Obtener el GitHub actual del usuario
$sql = "SELECT socials FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$socials_json = $stmt->fetchColumn();
$socials_array = json_decode($socials_json, true);
$github = $socials_array[0] ?? ''; // Obtener el primer elemento del array

?>
<link rel="stylesheet" href="../assets/css/addsocials.css">
</head>
<?php require_once "../includes/parts/layout.php"; ?>

<div class="addsocial-container">
    <div class="row">
        <div class="col-12">
            <h1>Edit GitHub</h1>
            <form action="../controllers/editSocialController.php" method="POST">
                <div class="form-group">
                    <label for="description">URL</label>
                    <textarea name="description" id="description" class="form-control"><?= htmlspecialchars($github) ?></textarea>
                    <button type="submit" name="editsocial" class="btn-primary">Update GitHub</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
