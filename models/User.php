<?php
require_once "../config/Db.php";
// Declaración de la clase user
class User {
    // Propiedades privadas para la conexión a la base de datos y el nombre de la tabla
    private $conn;
    private $table = 'users';
    
    // Propiedades públicas que representan los campos del usuario
    public $id;
    public $name;
    public $surname;
    public $username;
    public $email;
    public $password;
    public $socials;

    // Constructor que define una conexión a la base de datos y se le asigna a $conn
    public function __construct($db) {
        $this->conn = $db->connect();
    }

    // Método para registrar un usuario
    public function register() {
        // Validaciones
        if (strlen($this->username) < 3 || strlen($this->username) > 40) {
            return "Username must be between 3 and 40 characters.";
        }

        if (!preg_match('/^[a-zA-Z0-9._]+$/', $this->username)) {
            return "Username can only contain letters, numbers, dots, and underscores.";
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }

        if (strlen($this->password) < 5) {
            return "Password must be at least 5 characters long.";
        }

        // Hashear la contraseña
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        // Verificar si el usuario ya existe
        $check_sql = "SELECT * FROM " . $this->table . " WHERE username = ? OR email = ?";
        $check_stmt = $this->conn->prepare($check_sql);
        $check_stmt->execute([$this->username, $this->email]);

        if ($check_stmt->rowCount() > 0) {
            return "Username or email already exists.";
        }
        if($this->socials){
            $sql = "INSERT INTO " . $this->table . " (name, surname, username, email, password, socials) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt->execute([$this->name,$this->surname, $this->username, $this->email, $hashed_password, $this->socials])) {
            return true;
        } else {
            return "Error: " . $stmt->errorInfo()[2];
        }
        }
        // Insertar nuevo usuario
        $sql = "INSERT INTO " . $this->table . " (name, surname, username, email, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt->execute([$this->name,$this->surname, $this->username, $this->email, $hashed_password])) {
            return true;
        } else {
            return "Error: " . $stmt->errorInfo()[2];
        }
    }
    // Método para iniciar sesión
    public function login() {
        // Comprobar si existe el nombre de usuario
        $sql = "SELECT id, password FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$this->email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verificar la contraseña
            if (password_verify($this->password, $user['password'])) {
                $this->id = $user['id'];
                return true;
            } else {
                return "Incorrect password.";
            }
        } else {
            return "No account found with that username.";
        }
    }

    public function getUserById($user_id){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC); 
            
            // Verifica si se obtuvo una nota
            if ($user) {
                return $user; 
            } else {
                return ['error' => 'Usuario no encontrada.']; 
            }
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function updateUser($name, $surname, $username, $email, $password,  $user_id){
         
        if ($password) {
                try {
                    $stmt = $this->conn->prepare("UPDATE users SET name = ?, surname = ?, username = ?, email = ?, password = ? WHERE id = ?");
                    if ($stmt->execute([$name, $surname, $username, $email,  $password, $user_id])) {
                        return ['id'=>$user_id, 'name'=> $name, 'surname' => $surname, 'username' => $username ,'email' => $email];
                    }
                    return null;
                } catch (PDOException $e) {
                    return ['error' => $e->getMessage()];
                }
            } else {
                try {
                    $stmt = $this->conn->prepare("UPDATE users SET name = ?, surname = ?,username = ?, email = ? WHERE id = ?");
                    if ($stmt->execute([$name, $surname, $username, $email, $user_id])) {
                        return ['id'=>$user_id, 'name'=> $name, 'surname' => $surname, 'username' => $username ,'email' => $email];
                    }
                    return null;
                } catch (PDOException $e) {
                    return ['error' => $e->getMessage()];
                }
            }
        }
}

