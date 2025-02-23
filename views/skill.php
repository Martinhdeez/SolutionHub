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
    require_once "../models/Skill.php";

    $skill_id = $_GET['id'];

    $db = new Db();

    $skillObj = new Skill($db);

    $skill = $skillObj->getSkillById($skill_id);
?>

<div class="single_container">
    <div class="row">
        <div class="col-12">
            <h1>My Skill</h1>
            <form action="../controllers/skillController.php" method="post">
                <?php sessionStatus(); ?>
                <input type="hidden" name="id" value="<?= $skill['id']; ?>">
                <div>
                    <label for="title">Title:</label>
                    <input type="text" name="title" value="<?= $skill['title']; ?>">
                </div>
                <div>
                    <label for="description">Description:</label>
                    <input type="textarea" name="description" value="<?= $skill['description']; ?>"> 
                </div>
                <div>
                    <p><span id="azul">Updated at:</span> <?= date("F j, Y, g:i A", strtotime($skill['updated_at'])) ?></p>    
                </div>
                <div>
                    <p><span id="azul">Created at:</span> <?= date("F j, Y, g:i A", strtotime($skill['created_at'])) ?></p>
                </div>

            <button type="submit" name="update">Save</button>
            </form>
            <form action="../controllers/skillController.php" method="post">
                <input type="hidden" name="id" value="<?= $skill['id']; ?>">
                <button type="submit" name="delete">Delete</button>
            </form>
            <a id="back" name="back" href="myskills.php">Back to My skills</a>
        </div>

    </div>

    
</div>
</body>
</html>
