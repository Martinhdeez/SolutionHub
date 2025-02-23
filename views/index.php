<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../config/Db.php';
    require_once '../models/Skill.php';
    require_once '../models/User.php';
    require_once '../includes/functions/functions.php';
    require_once '../models/Solution.php';
    require_once '../includes/parts/header.php';
?>

<!-- Incluir CSS de DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="../assets/css/index.css">
</head>

<?php
    require_once '../includes/parts/layout.php';

    $db = new Db();
    $skillObj = new Skill($db);

    $skills = $skillObj->getAllSKills();
    $u = new User($db);

    $s = new Solution($db->conn);
    $solutions = $s->getAllSolutions();
    if($skills):
?> 

    <div class="skill_container">
        <h1>SOLUTION HUB</h1>
        <table id="skillsTable" class="display">
            <thead id="thead">
                <tr><th>Skill</th></tr>
            </thead>
            <tbody>
            <?php foreach($skills as $skill): 
                $user = $u->getUserById($skill['user_id'])?>
                <tr>
                    <td>
                        <a href="../views/userprofile.php?id=<?=$skill['user_id']; ?>" class="skill-link">
                            <div class="skill-content">
                                <div class="skill-title"><?=$skill['title']; ?></div>
                                <div class="skill-description"><?=$skill['description'];?></div>
                                <div class="date"><?= date("F j, Y, g:i A", strtotime($skill['updated_at']))?></div>
                                <div class="skill-name"><?=$user['name']." " .$user['surname'] ?></div>
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
    </div>

<?php endif; ?>

<!-- Incluir jQuery y los scripts de DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#skillsTable').DataTable(); // Inicializa la tabla con DataTables
    });
</script>

</body>
</html>
