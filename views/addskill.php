<?php
require_once "../auth/auth.php";

require_once "../includes/parts/header.php";
?>
<link rel="stylesheet" href="../assets/css/addskill.css">
</head>
<?php
require_once "../includes/parts/layout.php";
?>
<div class="addskill-container">
    <div class="row">
        <div class="col-12">
            <h1>Add Skill</h1>
            <form action="../controllers/addSkillController.php" method="POST">
                <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control">
                </div>
                <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
                </div>
                <button type="submit" name="addskill" class="btn btn-primary">Add Skill</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>