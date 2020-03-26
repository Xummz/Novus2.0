<?php
	session_start();
	if(isset($_SESSION)) {
	// remove all session variables
	session_unset();

	// destroy the session 
	session_destroy();
	}
?>
<!DOCTYPE html>
<html>
	<style>
		.centered {
			position: absolute;
			top: 35%;
			left: 50%;
			-ms-transform: translateX(-50%) translateY(-35%); /* old IE */
			-moz-transform: translateX(-50%) translateY(-35%); /* old firefox */
			-o-transform: translateX(-50%) translateY(-35%); /* old opera */
			-webkit-transform: translateX(-50%) translateY(-35%); /* android, safari, chrome */
			transform: translateX(-50%) translateY(-35%); /*standard */
		}
		.centeredMensagem {
			position: absolute;
			top: 10%;
			left: 50%;
			-ms-transform: translateX(-50%) translateY(-10%); /* old IE */
			-moz-transform: translateX(-50%) translateY(-10%); /* old firefox */
			-o-transform: translateX(-50%) translateY(-10%); /* old opera */
			-webkit-transform: translateX(-50%) translateY(-10%); /* android, safari, chrome */
			transform: translateX(-50%) translateY(-10%); /*standard */
		}
	</style>
<head>
	<title>Login</title>
	<link rel="stylesheet" href="bootstrap/bootstrap.min.css">
</head>
<body style="background-image: url('img/backgroundLogin.jpg'); background-size: cover;">
	<div class="centered" style="width: 22%; padding: 1%; background-color: #00000014; border-radius: 10px;">
		<div><img src="img/logoNovus.png" alt="Logo" style="width: 100%;"></div>
		<br>
		<br>
		<form action="Login.php" method="post">
			<div class="form-group">
				<input type="text" class="form-control" id="formGroupUsuario" name="user" placeholder="Usuário" required>
			</div>
			<div class="form-group">
				<input type="password" class="form-control" id="formGroupSenha" name="password" placeholder="Senha" required>
			</div>
			<button style="margin-top: 20px; width: 100%" type="submit" name="submit" class="btn btn-primary">Entrar</button>
		</form>
	</div>
	<div class="centeredMensagem">
			<br>
			<?php
				include_once('includes/tableUsuario.php');
				if (isset($_POST['submit'])) {

					$name = $_POST['user'];
					$password = $_POST['password'];
					$object = new Usuario();

					$resp = $object->Login($name, $password);

					
						session_start();
					
					if ($resp->HasError) {
						echo $resp->ErrorMsg;
					} else {
						$_SESSION["UserId"] = $resp->UserId;
						header('Location: DashBoard.php');
					}					
				}
			?>
	</div>
</body>
</html>