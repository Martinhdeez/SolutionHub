<?php
    ini_set('display_errors', 1);   
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once "../auth/auth.php";
    require_once "../config/Db.php";
    require_once "../models/Solution.php";

    require_once "../includes/parts/header.php";
?>
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="../assets/css/myskills.css">
    
</head>
<?php
    require_once "../includes/parts/layout.php";

    $db = new Db();
    $solutionO = new Solution($db->conn);

    $solutions = $solutionO->getSolutionsByUser($_SESSION['user_id']);
?>
    <div class="skill_container">
        <div class="row">
            <div class="col-12">
                <h1>My Solutions</h1>
                <a href="InsertSolution.php" class="btn btn-primary">Add Solution</a>
                <?php foreach($solutions as $solution): ?>
                        <div><a href="solution.php?id=<?=$solution['id']?>"><?= $solution['title'];?></a></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
