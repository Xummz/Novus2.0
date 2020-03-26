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
						include_once("includes/tablePecasReposicao.php");
						if ($Id == 0) {
							//Insert
							$obj = new PecasReposicao();
							$resp = $obj->InsertPecasReposicao($_POST['nome'],$_POST['descricao'],$_POST['estoque']);

							if ($resp->HasError) {
								$_SESSION["MensagemFeedBack"] = $resp->ErrorMsg; 
							} else {
								$_SESSION["MensagemFeedBack"] = 'Peça criada com sucesso!';
							}
							header('Location: PecasList.php');
						} elseif ($Id <> 0 && $IsEdit) {
							//Update
							$obj = new PecasReposicao();
							$resp = $obj->UpdatePecasReposicao($Id,$_POST['nome'],$_POST['descricao'],$_POST['estoque']);
							if ($resp->HasError) {
								$_SESSION["MensagemFeedBack"] = $resp->ErrorMsg; 
							} else {
								$_SESSION["MensagemFeedBack"] = 'Peça alterada com sucesso!';
							}
							header('Location: PecasList.php');
						}

					} else {
						
					}
				?>

		<?php 
			if ($Id != 0) {
				include_once('includes/tablePecasReposicao.php');
				try {
					
					$object = new PecasReposicao();
					$resp = $object->getPecasReposicaoById($Id);
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
				generateTitulo("Adicionar Peça de Reposição");
			} else if ($Id <> 0 && $IsEdit) {
				generateTitulo("Editar Peça de Reposição ".$resp->pecaBuscada["Nome"]);
			} else if ($Id <> 0 && !$IsEdit) {
				generateTitulo("Peça de Reposição ".$resp->pecaBuscada["Nome"]);
			}
		?>
			<div class="customDiv">
				<form action=<?php echo '"PecasEdit.php?IsEdit='.$_GET['IsEdit'].'&Id='.$_GET['Id'].'"'; ?> method="POST">

				  <div class="form-group row">
				    <label for="input1" class="col-2 col-form-label required">Nome</label>
				    <div class="col-sm-10">
					    <input type="text" 
					      	<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
					      	class="form-control customInputForm" name='nome' id="input1" 
					      	value=<?php 
								      	if ($Id <> 0) {
								      		echo '"'.$resp->pecaBuscada["Nome"].'"'; 
								      	} else {
								      		echo '""'; 
								      	}
					      			?>
					    >
				    </div>
				  </div>

				  <div class="form-group row">
				    <label for="input2" class="col-2 col-form-label required">Descrição</label>
				    <div class="col-sm-10">
						<input type="text" 
							<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
							class="form-control customInputForm" name='descricao' id="input2" 
							value=<?php 
										if ($Id <> 0) {
											echo '"'.$resp->pecaBuscada["Descrição"].'"'; 
										} else {
											echo '""'; 
										}
									?>
						>
				    </div>
				  </div>

				  <div class="form-group row">
				    <label for="input2" class="col-2 col-form-label required">Estoque de Peças</label>
				    <div class="col-sm-10">
						<input type="number" 
							<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
							class="form-control customInputForm" name='estoque' id="input3" 
							value=<?php 
										if ($Id <> 0) {
											echo '"'.$resp->pecaBuscada["Quantidade em Estoque"].'"'; 
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
				  <a href="PecasList.php" class="btn btn-secondary" style="margin-bottom: 10px;">Voltar</a>
				</form>
				
			</div>
	</main>
<?php 
	require "footer.php";
?>