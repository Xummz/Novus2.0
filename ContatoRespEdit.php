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
						
						include_once("includes/tableContatoResp.php");
						
						if ($Id == 0) {
							//Insert
							$obj = new ContatoResp();
							$resp = $obj->InsertContatoResp($_POST['nome'],$_POST['email'],
														$_POST['telefone'],$_POST['infoAdd']);
							if ($resp->HasError) {
								$_SESSION["MensagemFeedBack"] = $resp->ErrorMsg; 
							} else {
								$_SESSION["MensagemFeedBack"] = 'Resposável criada com sucesso!';
							}
							header('Location: ContatoRespList.php');
						} elseif ($Id <> 0 && $IsEdit) {
							//Update
							$obj = new ContatoResp();
							$resp = $obj->UpdateContatoResp($Id,$_POST['nome'],$_POST['email'],
														$_POST['telefone'],$_POST['infoAdd']);
							if ($resp->HasError) {
								$_SESSION["MensagemFeedBack"] = $resp->ErrorMsg; 
							} else {
								$_SESSION["MensagemFeedBack"] = 'Responsável alterado com sucesso!';
							}
							header('Location: ContatoRespList.php');
						}

					} else {
						
					}
				?>

		<?php 
			if ($Id != 0) {
				include_once('includes/tableContatoResp.php');
				try {
					
					$object = new ContatoResp();
					$resp = $object->getContatoRespById($Id);
					if ($resp->HasError) {
						throw new Exception($resp->ErrorMsg);
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
				generateTitulo("Adicionar Responsável");
			} else if ($Id <> 0 && $IsEdit) {
				generateTitulo("Editar Responsável ".$resp->RespDados["Nome"]);
			} else if ($Id <> 0 && !$IsEdit) {
				generateTitulo("Responsável ".$resp->RespDados["Nome"]);
			}
		?>
			<div class="customDiv">
				<form action=<?php echo '"ContatoRespEdit.php?IsEdit='.$IsEdit.'&Id='.$Id.'"'; ?> method="POST">

					<div class="form-group row">
					    <label for="input1" class="col-2 col-form-label required">Nome</label>
					    <div class="col-sm-10">
						    <input type="text" 
						      	<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
						      	class="form-control customInputForm" name='nome' id="input1" 
						      	value=<?php 
									      	if ($Id <> 0) {
									      		echo '"'.$resp->RespDados["Nome"].'"'; 
									      	} else {
									      		echo '""'; 
									      	}
						      			?>
						    >
					    </div>
					</div>

					<div class="form-group row">
					    <label for="input1" class="col-2 col-form-label required">Email</label>
					    <div class="col-sm-10">
						    <input type="email" 
						      	<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
						      	class="form-control customInputForm" name='email' id="input2" 
						      	value=<?php 
									      	if ($Id <> 0) {
									      		echo '"'.$resp->RespDados["Email"].'"'; 
									      	} else {
									      		echo '""'; 
									      	}
						      			?>
						    >
					    </div>
					</div>

					<div class="form-group row">
					    <label for="input1" class="col-2 col-form-label required">Telefone</label>
					    <div class="col-sm-10">
						    <input type="text" 
						      	<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
						      	class="form-control customInputForm" name='telefone' id="input3" 
						      	value=<?php 
									      	if ($Id <> 0) {
									      		echo '"'.$resp->RespDados["Telefone"].'"'; 
									      	} else {
									      		echo '""'; 
									      	}
						      			?>
						    >
					    </div>
					</div>

					<div class="form-group row">
					    <label for="input1" class="col-2 col-form-label">Informações Adicionais</label>
					    <div class="col-sm-10">
						    <input type="text" 
						      	<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
						      	class="form-control customInputForm" name='infoAdd' id="input4" 
						      	value=<?php 
									      	if ($Id <> 0) {
									      		echo '"'.$resp->RespDados["Informações Adicionais"].'"'; 
									      	} else {
									      		echo '""'; 
									      	}
						      			?>
						    >
					    </div>
					</div>

				  

				  <br>
				  <?php if (($Id <> 0 && $IsEdit) || $Id == 0) {
				  	echo '<button type="submit" name="salvar" class="btn btn-primary" style="margin-bottom: 10px;">Salvar</button>';
				  } ?>
				  <a href="ContatoRespList.php" class="btn btn-secondary" style="margin-bottom: 10px;">Voltar</a>
				</form>
				
			</div>
	</main>
<?php 
	require "footer.php";
?>