<?php
ini_set('display_errors', 1);   
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once "../config/Db.php";
require_once "../models/Solution.php";
session_start(); // Para manejar sesiones y obtener el user_id

$db = new Db();
$conn = $db->connect();
$solutionObj = new Solution($conn);

// Verifica si el usuario está autenticado

$user_id = $_SESSION['user_id']; // Obtener el ID del usuario logueado

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"])) {
        echo "add";
        // Agregar una nueva solución
        $title = $_POST["title"];
        $title = strtoupper($title);
        $solution = $_POST["solution"];

        if ($solutionObj->insertSolution($user_id, $title, $solution)) {
           header("Location: ../views/mysolutions.php?success=added");
           echo "Solution added successfully";
        } else {
           header("Location: ../views/mysolutions.php?error=add_failed");
          echo "Error adding solution";
        }
    } elseif (isset($_POST["update"])) {
        // Editar una solución existente
        $id = $_POST["id"];
        $title = $_POST["title"];
        $solution = $_POST["solution"];

        if ($solutionObj->updateSolution($id, $title, $solution)) {
            header("Location: ../views/solution.php?id=$id");
        } else {
            header("Location: ../views/mysolutions.php?error=update_failed");
        }
    } elseif (isset($_POST["delete"])) {
        // Eliminar una solución
        $id = $_POST["id"];

        if ($solutionObj->deleteSolution($id)) {
            header("Location: ../views/mysolutions.php?success=deleted");
        } else {
            header("Location: ../views/mysolutions.php?error=delete_failed");
        }
    } else {

        header("Location: ../views/mysolutions.php?error=invalid_action");
    }
} else {
    header("Location: ../views/mysolutions.php");
}
exit();
