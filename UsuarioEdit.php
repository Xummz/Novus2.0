<?php

	include_once('includes/verifyLogin.php');

	if (empty($_GET['IsEdit']) || !isset($_GET['IsEdit'])) {
		$IsEdit = (bool)"0";
	} else {
		if (strtolower($_GET['IsEdit']) == "true" || $_GET['IsEdit'] == "1" || strtolower($_GET['IsEdit']) == "on") {
			$IsEdit = (bool)"1";
		} else {
			$IsEdit = (bool)"0";
		}
	}
	if (empty($_GET['Id'])||!isset($_GET['Id'])) {
		$Id = 0;
	} else {
		$Id = (int)$_GET['Id'];
	}
?>
<?php 
	require "header.php";
?>
	<main id="MainDiv" class="offset-2">
				<?php 
					if (isset($_POST['salvar'])) {
						include_once("includes/tableUsuario.php");
						if ($Id == 0) {
							//Insert
							$obj = new Usuario();
							$resp = $obj->InsertUsuario($_POST['usuario'],$_POST['senha'],$_POST['nome'],
														$_POST['email'],$_POST['tipoUsuario']);
							if ($resp->HasError) {
								session_start();
								$_SESSION["MensagemFeedBack"] = $resp->ErrorMsg; 
							} else {
								session_start();
								$_SESSION["MensagemFeedBack"] = 'Usuário criado com sucesso!';
							}
							header('Location: UsuariosList.php');
						} elseif ($Id <> 0 && $IsEdit) {
							//Update
							$obj = new Usuario();
							$resp = $obj->UpdateUsuario($Id,$_POST['usuario'],$_POST['nome'],
														$_POST['email'],$_POST['tipoUsuario'],$_POST['senha']);
							if ($resp->HasError) {
								session_start();
								$_SESSION["MensagemFeedBack"] = $resp->ErrorMsg; 
							} else {
								session_start();
								$_SESSION["MensagemFeedBack"] = 'Usuário alterado com sucesso!';
							}
							header('Location: UsuariosList.php');
						}

					} else {
						
					}
				?>
		<?php 
			if ($Id != 0) {
				include_once('includes/tableUsuario.php');
				try {
					
					$object = new Usuario();
					$resp = $object->getUsuarioNome($Id);
					if ($resp->HasError) {
						throw new Exception($resp->ErrorMsg);
					}
					$objectDados = new Usuario();
					$respDados = $objectDados->getUsuarioById($Id);
					if ($respDados->HasError) {
						throw new Exception($respDados->ErrorMsg);
					}
				} catch (Exception $e) {
					include_once('includes/pageGenerateTitulo.php');
					generateTitulo($e->getMessage());
					die();
				}
			}

			//titulo da página
			include_once('includes/pageGenerateTitulo.php');
			if ($Id == 0) {
				generateTitulo("Adicionar Usuário");
			} else if ($Id <> 0 && $IsEdit) {
				generateTitulo("Editar Usuário ".$resp->UserNome);
			} else if ($Id <> 0 && !$IsEdit) {
				generateTitulo("Usuário ".$resp->UserNome);
			}
		?>
			<div class="customDiv">
				<form action=<?php echo '"UsuarioEdit.php?IsEdit='.$_GET['IsEdit'].'&Id='.$_GET['Id'].'"'; ?> method="POST">

				  <div class="form-group row">
				    <label for="input1" class="col-2 col-form-label required">Usuário</label>
				    <div class="col-sm-10">
					    <input type="text" 
					      	<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
					      	class="form-control customInputForm" name='usuario' id="input1" 
					      	value=<?php 
								      	if ($Id <> 0) {
								      		echo '"'.$respDados->UserDados["Usuário"].'"'; 
								      	} else {
								      		echo '""'; 
								      	}
					      			?>
					    >
				    </div>
				  </div>

				  <div class="form-group row">
				    <label for="input2" class="col-2 col-form-label required">Nome</label>
				    <div class="col-sm-10">
						<input type="text" 
							<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
							class="form-control customInputForm" name='nome' id="input2" 
							value=<?php 
										if ($Id <> 0) {
											echo '"'.$respDados->UserDados["Nome"].'"'; 
										} else {
											echo '""'; 
										}
									?>
						>
				    </div>
				  </div>

				  <div class="form-group row">
				    <label for="input2" class="col-2 col-form-label required">Email</label>
				    <div class="col-sm-10">
						<input type="text" 
							<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
							class="form-control customInputForm" name='email' id="input3" 
							value=<?php 
										if ($Id <> 0) {
											echo '"'.$respDados->UserDados["Email"].'"'; 
										} else {
											echo '""'; 
										}
									?>
						>
				    </div>
				  </div>

					<div class="form-group row">
						<label class="col-2 col-form-label required">Tipo de Usuário</label>
						<div class="col-sm-10">
							<select name="tipoUsuario" <?php if ($Id <> 0 && !$IsEdit) {echo ' disabled';} ?>>
								<option value="VISITANTE" 									
									<?php if (($Id == 0) || ($respDados->UserDados["TipoUsuarioId"] == "VISITANTE")) {echo ' selected';} ?> 
								>Visitante</option>
								<option value="FUNCIONARIO" 
									<?php if ($Id != 0) {if ($respDados->UserDados["TipoUsuarioId"] == "FUNCIONARIO") {echo ' selected';}} ?> 
								>Funcionário</option>
								<option value="ADMIN" 
									<?php if ($Id != 0) {if ($respDados->UserDados["TipoUsuarioId"] == "ADMIN") {echo ' selected';}} ?> 
								>Administrador</option>
							</select>
						</div>
					</div>

				  <?php 
				 	if ($Id == 0 || ($Id <> 0 && $Id == $_SESSION["UserId"])) {
				 		echo 
							'<div class="form-group row">
								<label for="input2" class="col-2 col-form-label required">Senha</label>
								<div class="col-sm-10">
									<input type="text" ';
										if ($Id <> 0 && !$IsEdit) {echo 'readonly ';}
										echo 
										'class="form-control customInputForm" name=\'senha\' id="input4" 
										value=';
													if ($Id <> 0) {
														echo '"'.$respDados->UserDados["Senha"].'"'; 
													} else {
														echo '""'; 
													}
										echo 
									'>
								</div>
							</div>';
					} else {
						//nada
					}
				  ?>

				  <br>
				  <?php if (($Id <> 0 && $IsEdit) || $Id == 0) {
				  	echo '<button type="submit" name="salvar" class="btn btn-primary" style="margin-bottom: 10px;">Salvar</button>';
				  } ?>				  
				  <a href="UsuariosList.php" class="btn btn-secondary" style="margin-bottom: 10px;">Voltar</a>
				</form>
				
			</div>
	</main>
<?php 
	require "footer.php";
?>