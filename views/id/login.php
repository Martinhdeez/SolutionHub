<?php

require_once "../../includes/functions/functions.php";
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_SESSION['user_id'])){
	header("Location: ../index.php");
	exit();
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Slide Navbar</title>
	<link rel="stylesheet" type="text/css" href="../../assets/css/login.css">
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
	<div class="main">  	
		<input type="checkbox" id="chk" aria-hidden="true">

			<div class="signup">
			<form action="../../controllers/loginController.php" method="POST">
			<?php sessionStatus(); ?>
			<label for="chk" aria-hidden="true">Sign up</label>
				
                    <input type="text" placeholder="Name" name="name"value="<?=isset($_SESSION['form_data']['name']) ? htmlspecialchars($_SESSION['form_data']['name']) : ''; ?>" required>
                   
					<input type="text" placeholder="Surname" name="surname"value="<?=isset($_SESSION['form_data']['surname']) ? htmlspecialchars($_SESSION['form_data']['surname']) : ''; ?>" required>
                  
					<input type="text" placeholder="Username" name="username" value="<?=isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>" required>
                  
					<input type="text" placeholder="Email" name="email" value="<?=isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>" required>
					
                    <input type="text" placeholder="Github Username" name="github" value="<?=isset($_SESSION['form_data']['github']) ? htmlspecialchars($_SESSION['form_data']['github']) : ''; ?>" >
					<input type="password" placeholder="Password" name="password" required>
					
                    <input type="password" placeholder="Confirm Password" name="confirmPass" required>
                    <button id="register" name="register" type="submit">Sign up</button>
			</form>
			</div>

			<div class="login">
				<form action="../../controllers/loginController.php" method="POST">
				<?php sessionStatus(); ?>
					<label for="chk" aria-hidden="true">Login</label>
					<input type="email" name="email" placeholder="Email"value="<?=isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>" required>
					<input type="password" name="password" placeholder="Password" required="">
					<button id="Enter" name="login" type="submit">Enter</button>
				</form>
			</div>
	</div>
</body>
</html>