<?php
    require_once "../config/Db.php";
    require_once "../models/User.php";
    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if(isset($_POST['register'])){
         // Crear una instancia de la clase Database y establecer la conexión
        $db = new Db();
            // Crear una instancia del modelo User
            $user = new User($db);
            // Asignar los valores del formulario a las propiedades del modelo User
            $user->name = trim($_POST['name']);
            $user->surname = $_POST['surname'];
            $user->username = $_POST['username'];
            $user->email = $_POST['email'];
            $user->socials = isset($_POST['github']) ? $_POST['github'] : null;
            $user->password = trim($_POST['password']);
            $confirmPass = trim($_POST['confirmPass']);

            // Almacenar los datos del formulario en la sesión
            $_SESSION['form_data'] = [
                'name' => $_POST['name'],
                'surname' => $_POST['surname'],
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'github' => $_POST['github']
            ];

            // Verificar si las contraseñas coinciden
            if ($user->password === $confirmPass) {
                // Llamar al método register del modelo User
                $result = $user->register();
                
                // Verificar si el registro fue exitoso
                if ($result === true) {
                    unset($_SESSION['form_data']);
                    if(isset($_POST['github'])){

                        $_SESSION['user_id'] = $db->conn->lastInsertId();
                        $_SESSION['email'] = $user->email;

                        header("Location: ../controllers/getIADatesController.php?url=https://github.com/".$_POST['github']);
                        exit();
                    }
                   // Limpiar los datos del formulario de la sesión
                   
                    // Redirigir al usuario a la página de inicio de sesión con un mensaje de éxito
                    $_SESSION['success'] = "You has been registered successfully, now you can login.";
                    header("Location: ../views/id/login.php");
                    exit();
                } else {
                    // Almacenar el mensaje de error en la sesión
                    $_SESSION['error'] = $result;
                }
            } else {
                $_SESSION['error'] = "Las contraseñas no coinciden.";
            }

    header("Location: ../views/id/login.php"); // Redirigir de vuelta a la página de registro para mostrar el mensaje
    exit();
    }


    if(isset($_POST["login"])){
        // Crear una instancia de la clase Database y establecer la conexión
    
        $db =  new Db();
    if ($db) { // Asegurarse de que la conexión se estableció correctamente
        // Crear una instancia del modelo User
        $user = new User($db);
        // Asignar los valores del formulario a las propiedades del modelo User
        $user->email = trim($_POST['email']);
        $user->password = trim($_POST['password']);

        // Guardar los datos del formulario en la sesión
        $_SESSION['form_data'] = [
            'username' => $_POST['email']
        ];

        // Llamar al método login del modelo User
        $result = $user->login();

        // Verificar si el login fue exitoso
        if ($result === true) {
            // Limpiar los datos del formulario de la sesión
            unset($_SESSION['form_data']);
            // Almacenar datos en sesión
            $_SESSION['user_id'] = $user->id;
            $_SESSION['email'] = $user->email;
            
            // Redirigir al usuario a la página de inicio
            header("Location: ../views/index.php");
            exit();
        } else {
            // Almacenar el mensaje de error en la sesión
            $_SESSION['error'] = $result;
        }
    } else {
        $_SESSION['error'] = "Failed to connect to the database.";
    }
    // Redirigir de vuelta a la página de login para mostrar el mensaje de error
    header("Location: ../views/id/login.php");
    exit();
    }
?>
