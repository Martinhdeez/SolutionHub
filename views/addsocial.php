<?php
require_once "../auth/auth.php";

require_once "../includes/parts/header.php";
?>
<link rel="stylesheet" href="../assets/css/addsocials.css">
</head>
<?php
require_once "../includes/parts/layout.php";
?>
<div class="addsocial-container">
    <div class="row">
        <div class="col-12">
            <h1>Add GitHub</h1>
            <form action="../controllers/addSocialController.php" method="POST">
                <div class="form-group">
                <label for="description">URL</label>
                <textarea name="description" id="description" class="form-control"></textarea>
                <button type="submit" name="addsocial" class="btn-primary">Add GitHub</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>