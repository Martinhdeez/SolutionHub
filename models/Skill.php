<?php

require_once "../config/Db.php";
class Skill{
    private $conn;
    private $table = 'skills';

    public $id;

    public $user_id;

    public $title;

    public $description;

    public function __construct($db){
        $this->conn = $db->conn;
    } 

    public function create(){
        $sql = "INSERT INTO " . $this->table . " (user_id, title, description) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if($stmt->execute([$this->user_id, $this->title, $this->description])){
            return true;
        }else{
            return "Error: " . $stmt->errorInfo()[2];
        }
    }

    public function getSkillsByUser($user_id){
        $sql = "SELECT * FROM " . $this->table . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSkillById($id){
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(){
        $sql = "UPDATE " . $this->table . " SET title = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if($stmt->execute([$this->title, $this->description, $this->id])){
            return true;
        }else{
            return "Error: " . $stmt->errorInfo()[2];
        }
    }

    public function delete(){
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if($stmt->execute([$this->id])){
            return true;
        }else{
            return "Error: " . $stmt->errorInfo()[2];
        }
    }

    public function getAllSKills(){
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }


}