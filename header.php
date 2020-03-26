<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	
	<?php include_once("includes/nomeGuia.php") ?>
	<title><?php echo retornaNomeGuia($_SERVER['REQUEST_URI']); ?></title>

	<!--Custom CSS:-->
	<link rel="stylesheet" href="style/style.css">

	<link rel="stylesheet" href="bootstrap/bootstrap.min.css">
	<!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">-->
	
	<!--<link rel="stylesheet" href="jquery/jquery-3.4.1.min.js">-->
	<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>

	<link rel="stylesheet" href="icons/open-iconic-bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css" integrity="sha256-BJ/G+e+y7bQdrYkS2RBTyNfBHpA9IuGaPmf9htub5MQ=" crossorigin="anonymous" />

</head>
<body>

	<header>
		<div class="topNav">
			<div class="leftDiv">
				<a onclick="toggleNav()" class="customMenu">				
					<span class="icon oi oi-menu" aria-hidden="true"></span>				
				</a>

				<img src="img/logoNovus.png" alt="logo" class="logoStyle">
			</div>
			<div class="rightDiv">
				<a href="Login.php" class="btn btn-outline-danger" role="button">Logout</a>
			</div>
		</div>

		<div id="mySidenav" class="sidenav font">
			<ul>
				<a href="DashBoard.php"><li class="firstLi"><span class="icon oi oi-home" aria-hidden="true"></span>Home</li></a>
				<a href="DashBoard.php"><li><span class="icon oi oi-pie-chart" aria-hidden="true"></span>DashBoard</li></a>
				<a href="ContatoRespList.php"><li><span class="icon oi oi-spreadsheet" aria-hidden="true"></span>Responsáveis</li></a>
				<a href="UsuariosList.php"><li><span class="icon oi oi-spreadsheet" aria-hidden="true"></span>Usuários</li></a>
				<a href="PecasList.php"><li><span class="icon oi oi-spreadsheet" aria-hidden="true"></span>Peças</li></a>
				<a href="MaquinasList.php"><li><span class="icon oi oi-spreadsheet" aria-hidden="true"></span>Máquinas</li></a>
				<a href="MovMaquinasList.php"><li><span class="icon oi oi-list" aria-hidden="true"></span>Log de Atividades</li></a>
			</ul>
		</div>
	</header>