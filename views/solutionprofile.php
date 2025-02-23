<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once "../includes/parts/header.php";
?>
<link rel="stylesheet" href="../assets/css/skill.css">
</head>
<body>
<?php
    require_once "../includes/functions/functions.php";
    require_once "../config/Db.php";
    require_once "../includes/parts/layout.php";
    require_once "../models/Solution.php";
    require_once "../models/User.php";

    $db = new Db();
    $solutionObj = new Solution($db->conn);

    $solution_id = $_GET['id'] ?? null;
    $solution = $solution_id ? $solutionObj->getSolutionById($solution_id) : null;
    $u = new User($db);
    $user = $u->getUserById($solution['user_id']);
?>

<div class="single_container">
    <div class="row">
        <div class="col-12">
            <h1><?=$user['name']." ".$user['surname'];?></h1>
            <?php if ($solution_id): ?>
                <!-- Formulario de Edición -->
                    <?php sessionStatus(); ?>
                    <input type="hidden" name="id" value="<?= $solution['id']; ?> readonly">
                    <div>
                        <label for="title">Title:</label>
                        <p id="title" ><?= $solution['title']; ?></p>
                    </div>
                    <div class="description">
                        <label for="solution">Solution:</label>
                        <p id="description" name="description"><?= $solution['solution']; ?></p>
                    </div>
            <?php endif; ?>
            <form class="user-details" action="../controllers/chatController.php" method="post">
            <input type="hidden" name="user_id1" value="<?= $solution['user_id']; ?>">
            <input type="hidden" name="user_id2" value="<?= $_SESSION['user_id']; ?>">
            <button id="contact" type="submit" class="contact-button">Contact</button>
            </form>

              <!-- Botones Like y Dislike -->
            <div class="like-dislike-container">
                <form action="../controllers/likeController.php?id=<?=$solution['id']?>" method="POST">
                    <button type="submit" id="like" name="like" class="like-button">Like (<?= $solution['likes']; ?>)</button>
                    <button type="submit" id="dislike" name="dislike" class="dislike-button">Dislike (<?= $solution['dislikes']; ?>)</button>
                </form>
            </div>
            <a id="back" href="index.php">Back to Dashboard</a>

        </div>
    </div>
</div>
</body>
</html>
