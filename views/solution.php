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

    $db = new Db();
    $solutionObj = new Solution($db->conn);

    $solution_id = $_GET['id'] ?? null;
    $solution = $solution_id ? $solutionObj->getSolutionById($solution_id) : null;
?>

<div class="single_container">
    <div class="row">
        <div class="col-12">
            <h1><?= $solution_id ? 'Edit Solution' : 'Add Solution'; ?></h1>
            <?php if ($solution_id): ?>
                <!-- Formulario de Edición -->
                <form action="../controllers/solutionController.php" method="post">
                    <?php sessionStatus(); ?>
                    <input type="hidden" name="id" value="<?= $solution['id']; ?>">
                    <div>
                        <label for="title">Title:</label>
                        <input type="text" name="title" value="<?= $solution['title']; ?>" required>
                    </div>
                    <div>
                        <label for="solution">Solution:</label>
                        <textarea id="description" name="solution" rows="20" required><?= $solution['solution']; ?></textarea>
                    </div>
                    <button type="submit" name="update" class="btn btn-success">Editar</button>
                </form>

                <!-- Formulario de Eliminación -->
                <form action="../controllers/solutionController.php" method="post">
                    <input type="hidden" name="id" value="<?= $solution['id']; ?>">
                    <button type="submit" name="delete" class="btn btn-danger">Borrar</button>
                </form>
            <?php endif; ?>

            <a id="back" name="back" href="mysolutions.php">Back to My solutions</a>

        </div>
    </div>
</div>
</body>
</html>
