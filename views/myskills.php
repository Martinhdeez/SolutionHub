<?php
    ini_set('display_errors', 1);   
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once "../auth/auth.php";
    require_once "../config/Db.php";
    require_once "../models/Skill.php";

    require_once "../includes/parts/header.php";
?>
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="../assets/css/myskills.css">
    
</head>
<?php
    require_once "../includes/parts/layout.php";

    $db = new Db();
    $skill = new Skill($db);

    $skills = $skill->getSkillsByUser($_SESSION['user_id']);
?>
    <div class="skill_container">
        <div class="row">
            <div class="col-12">
                <h1>My Skills</h1>
                <a href="addskill.php" class="btn btn-primary">Add Skill</a>
                <?php foreach($skills as $skill): ?>
                        <div><a href="skill.php?id=<?=$skill['id']?>"><?= $skill['title'];?></a></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
