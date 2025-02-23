<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    require_once "../includes/parts/header.php";
?>  
<link rel="stylesheet" href="../assets/css/insertSolution.css">
</head>
<body>
<?php
    require_once "../includes/functions/functions.php";
    require_once "../config/Db.php";
    require_once "../includes/parts/layout.php";

    $db = new Db();
?>

<div class="insert-solution-container">
    <div class="row">
        <div class="col-12">
            <h1>Insert New Solution</h1>
            <form action="../controllers/solutionController.php" method="post" class="insert-solution-form">
                <?php sessionStatus(); ?>
                <div>
                    <label for="title">Title:</label>
                    <input type="text" name="title" required>
                </div>
                <div>
                    <label for="solution">Solution:</label>
                    <textarea name="solution" rows="15" required></textarea>
                </div>
                <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">
                <button type="submit" name="add" class="btn-submit">Submit</button>
            </form>
            <a id="back" name="back" href="mysolutions.php">Back to My Solutions</a>

        </div>
    </div>
</div>
</body>
</html>
