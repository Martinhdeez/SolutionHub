<?php
class Solution {
    private $table = 'solutions';
    private $conn;

    public $id;
    public $user_id;
    public $title;
    public $solution;
    public $created_at;
    public $updated_at;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener una solución por ID
    public function getSolutionById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insertar una nueva solución
    public function insertSolution($user_id, $title, $solution) {
        $sql = "INSERT INTO {$this->table} (user_id, title, solution) 
                VALUES (:user_id, :title, :solution)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':solution', $solution, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Actualizar una solución existente
    public function updateSolution($id, $title, $solution) {
        $sql = "UPDATE {$this->table} 
                SET title = :title, solution = :solution 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', strtoupper($title), PDO::PARAM_STR);
        $stmt->bindParam(':solution', $solution, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Eliminar una solución
    public function deleteSolution($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Obtener todas las soluciones de un usuario
    public function getSolutionsByUser($user_id) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSolutions(){
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function updateLikeDislike($user_id, $solution_id, $type) {
        // Selecciona el campo correspondiente (like o dislike)
        $column = ($type == 'like') ? 'likes' : 'dislikes';

        $stmt = $this->conn->prepare("SELECT * FROM likes WHERE user_id = ? AND solution_id = ?");
        $stmt->execute([$user_id, $solution_id]);
        $like = $stmt->fetch(PDO::FETCH_ASSOC);
        if($like){
            return;
        }
        
        // Actualiza el contador en la base de datos
        $query = "UPDATE solutions SET $column = $column + 1 WHERE id = :solution_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':solution_id', $solution_id);
        
        if ($stmt->execute()) {
            $query = "INSERT INTO likes (user_id, solution_id, type) VALUES (:user_id, :solution_id, :type)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':solution_id', $solution_id);
            $stmt->bindParam(':type', $type);
            $stmt->execute();
            return true; // Operación exitosa
        } else {
            return false; // Falló la actualización
        }
    }
}
