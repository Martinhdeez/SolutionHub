<?php


require_once '../config/Db.php';
require_once '../models/Skill.php';
require_once '../models/User.php';
require_once '../models/Solution.php';
require_once '../includes/parts/header.php';
?>

<!-- Incluir CSS de DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="../assets/css/userprofile.css">
</head>

<?php
require_once '../includes/parts/layout.php';

$db = new Db();

$user_id = $_GET['id'];



$userObj = new User($db);
$user = $userObj->getUserById($user_id);
// Obtener todos los repositorios y lenguajes del usuario
$query = "SELECT userinfo.repositorio_url, languages.name as language_name
          FROM userinfo
          LEFT JOIN languages ON userinfo.language_id = languages.id
          WHERE userinfo.user_id = ?";
$stmt = $db->conn->prepare($query);
$stmt->execute([$user_id]);
$user_repos = $stmt->fetchAll(PDO::FETCH_ASSOC);


$skillObj = new Skill($db);
$skills = $skillObj->getSkillsByUser($user_id);

$solutionObj = new Solution($db->conn);
$solutions = $solutionObj->getSolutionsByUser($user_id);
?>


<div class="main-content">

        <h1 class="name-h1"><?=$user['name']. ' ' . $user['surname'] ?></h1>
        <!-- Información del usuario -->
        <div class="user-info-container">
            <h2>Información del Usuario</h2>
            <p><strong>Nombre:</strong> <?= $user['name'] . ' ' . $user['surname']; ?></p>
            <p><strong>Email:</strong> <?= $user['email']; ?></p>
            <p><strong>Registrado desde:</strong> <?= date("F j, Y", strtotime($user['created_at'])); ?></p>

            <!-- Mostrar repositorios si existen -->
            <?php if (!empty($user_repos)): ?>
                <h3>Repositorios</h3>
                <ul class="repos-list">
                    <?php foreach ($user_repos as $repo): ?>
                        <li class="repo">
                            <strong>Lenguaje:</strong> <?= $repo['language_name'] ?? 'Desconocido'; ?> <br>
                            <strong>Repositorio de GitHub:</strong> 
                            <a href="<?= 'https://github.com/' . ltrim(rtrim($repo['repositorio_url'], '/'), '/') . '.git'; ?>" target="_blank">
                                <?= 'https://github.com/' . ltrim(rtrim($repo['repositorio_url'], '/'), '/') . '.git'; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Este usuario no tiene repositorios registrados.</p>
            <?php endif; ?>
        </div>

        <table id="skillsTable" class="display">
            <thead id="thead">
                <tr><th>Skill</th></tr>
            </thead>
            <tbody>
            <?php foreach($skills as $skill): ?>
                <tr>
                    <td>
                        <a href="../views/userprofile.php?id=<?=$skill['user_id']; ?>" class="skill-link">
                            <div class="skill-content">
                                <div class="skill-title"><?=$skill['title']; ?></div>
                                <div class="skill-description"><?=$skill['description'];?></div>
                                <div class="date"><?= date("F j, Y, g:i A", strtotime($skill['updated_at']))?></div>
                            </div>
                        </a>
                    </td>
                </tr>
            <?php endforeach;
            foreach($solutions as $solution): ?>
                <tr>
                    <td>
                        <a href="../views/solutionprofile.php?id=<?=$solution['id']; ?>" class="skill-link">
                        <div class="skill-content">
                            <div class="skill-title"><?=$solution['title']; ?></div>
                            <div class="skill-description"><?=$solution['solution'];?></div>
                            <div class="date"><?= date("F j, Y, g:i A", strtotime($solution['updated_at']))?></div>
                        </div>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php if($solution): ?>
        <form class="user-details" action="../controllers/chatController.php" method="post">
            <input type="hidden" name="user_id1" value="<?= $solution['user_id']; ?>">
            <input type="hidden" name="user_id2" value="<?= $_SESSION['user_id']; ?>">
            <button id="contact" type="submit" class="contact-button">Contact</button>
        </form>
        <?php elseif($skill): ?>
        <form class="user-details" action="../controllers/chatController.php" method="post">
            <input type="hidden" name="user_id1" value="<?= $skill['user_id']; ?>">
            <input type="hidden" name="user_id2" value="<?= $_SESSION['user_id']; ?>">
            <button id="contact" type="submit" class="contact-button">Contact</button>
        </form>
        <?php endif; ?>

            <a id="back" href="index.php">Back to Dashboard</a>
          
</div>

<!-- Incluir jQuery y DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#skillsTable').DataTable();
    });
</script>

</body>

</html>