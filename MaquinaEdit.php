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
						include_once("includes/tableCadMaq.php");
						if ($Id == 0) {
							//Insert
							$obj = new CadMaq();
							$resp = $obj->InsertCadMaq($_POST['nome'],$_POST['descricao'],$_POST['caracteristicas'],
														$_POST['patrimonio'],$_POST['periodoManutencao'],$_POST['avisoAntes'],
														$_POST['enderecoEmailAviso'],$_POST['contatoNome']);

							if ($resp->HasError) {
								$_SESSION["MensagemFeedBack"] = $resp->ErrorMsg; 
							} else {
								$_SESSION["MensagemFeedBack"] = 'Máquina criada com sucesso!';
							}
							header('Location: MaquinasList.php');
						} elseif ($Id <> 0 && $IsEdit) {
							//Update
							$obj = new CadMaq();
							$resp = $obj->UpdateCadMaq($Id,$_POST['nome'],$_POST['descricao'],$_POST['caracteristicas'],
														$_POST['patrimonio'],$_POST['periodoManutencao'],$_POST['avisoAntes'],
														$_POST['enderecoEmailAviso']);
							if ($resp->HasError) {
								$_SESSION["MensagemFeedBack"] = $resp->ErrorMsg; 
							} else {
								$_SESSION["MensagemFeedBack"] = 'Máquina alterada com sucesso!';
							}
							header('Location: MaquinasList.php');
						}

					} else {
						
					}
				?>

		<?php 
			if ($Id != 0) {
				include_once('includes/tableCadMaq.php');
				try {
					
					$object = new CadMaq();
					$resp = $object->getCadMaqById($Id);
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
				generateTitulo("Adicionar Máquina");
			} else if ($Id <> 0 && $IsEdit) {
				generateTitulo("Editar Máquina ".$resp->MaqDados["Nome"]);
			} else if ($Id <> 0 && !$IsEdit) {
				generateTitulo("Máquina ".$resp->MaqDados["Nome"]);
			}
		?>
			<div class="customDiv">
				<form action=<?php echo '"MaquinaEdit.php?IsEdit='.$_GET['IsEdit'].'&Id='.$_GET['Id'].'"'; ?> method="POST">

				  <div class="form-group row">
				    <label for="input1" class="col-2 col-form-label required">Nome</label>
				    <div class="col-sm-10">
					    <input type="text" 
					      	<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
					      	class="form-control customInputForm" name='nome' id="input1" 
					      	value=<?php 
								      	if ($Id <> 0) {
								      		echo '"'.$resp->MaqDados["Nome"].'"'; 
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
											echo '"'.$resp->MaqDados["Descrição"].'"'; 
										} else {
											echo '""'; 
										}
									?>
						>
				    </div>
				  </div>

				  <div class="form-group row">
				    <label for="input2" class="col-2 col-form-label required">Características</label>
				    <div class="col-sm-10">
						<input type="text" 
							<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
							class="form-control customInputForm" name='caracteristicas' id="input3" 
							value=<?php 
										if ($Id <> 0) {
											echo '"'.$resp->MaqDados["Características"].'"'; 
										} else {
											echo '""'; 
										}
									?>
						>
				    </div>
				  </div>

				  <div class="form-group row">
				    <label for="input2" class="col-2 col-form-label required">Patrimônio</label>
				    <div class="col-sm-10">
						<input type="text"
							<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
							class="form-control customInputForm" name='patrimonio' id="input4" 
							value=<?php 
										if ($Id <> 0) {
											echo '"'.$resp->MaqDados["Patrimônio"].'"'; 
										} else {
											echo '""'; 
										}
									?>
						>
				    </div>
				  </div>

				  <div class="form-group row">
				    <label for="input2" class="col-2 col-form-label required">Período de Manutenção (em dias)</label>
				    <div class="col-sm-10">
						<input type="number" 
							<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
							class="form-control customInputForm" name='periodoManutencao' id="input5" 
							value=<?php 
										if ($Id <> 0) {
											echo '"'.$resp->MaqDados["Período de Manutenção (em dias)"].'"'; 
										} else {
											echo '""'; 
										}
									?>
						>
				    </div>
				  </div>

				  <div class="form-group row">
				    <label for="input2" class="col-2 col-form-label required">Tempo, em dias, antes de mandar email de aviso</label>
				    <div class="col-sm-10">
						<input type="number" 
							<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
							class="form-control customInputForm" name='avisoAntes' id="input6" 
							value=<?php 
										if ($Id <> 0) {
											echo '"'.$resp->MaqDados["Tempo, em dias, antes de mandar email de aviso"].'"'; 
										} else {
											echo '""'; 
										}
									?>
						>
				    </div>
				  </div>

				  <div class="form-group row">
				    <label for="input2" class="col-2 col-form-label required">Endereço de Email para enviar o aviso</label>
				    <div class="col-sm-10">
						<input type="email" 
							<?php if ($Id <> 0 && !$IsEdit) {echo 'readonly';} ?> 
							class="form-control customInputForm" name='enderecoEmailAviso' id="input7" 
							value=<?php 
										if ($Id <> 0) {
											echo '"'.$resp->MaqDados["Endereço de Email para enviar o aviso"].'"'; 
										} else {
											echo '""'; 
										}
									?>
						>
				    </div>
				  </div>
				  <?php if ($Id == 0) { 
					  echo '<div class="form-group row">
					    <label for="input2" class="col-2 col-form-label required">Nome do Responsável</label>
					    <div class="col-sm-10">
							<input type="text" '; 
								if ($Id <> 0 && !$IsEdit) {echo 'readonly';} echo 
								'class="form-control customInputForm" name=\'contatoNome\' id="input8" 
								value='; 
											if ($Id <> 0) {
												echo '"'.$resp->MaqDados["Nome do Responsável"].'"'; 
											} else {
												echo '""'; 
											}
								echo 
							'>
					    </div>
					  </div>';
					  }
				  ?>
				  

				  <?php 
				  	if ($Id != 0) {

						echo '<h4 style="margin-top:20px;">Peças</h4>';

				  		include_once('includes/pageGenerateTableList.php');
				  		$cabecalho = array();
						$resp = array();

						$object = new CadMaq();
						$resp = $object->getPecasByCadMaqId($Id)->pecas;

						$url = 'Pecas';

						generateTableList($resp, $cabecalho, $url);




				  		echo '<h4 style="margin-top:20px;">Responsáveis</h4>';

				  		include_once('includes/pageGenerateTableList.php');
				  		$cabecalho = array();
						$resp = array();

						$object = new CadMaq();
						$resp = $object->getResponsaveisByCadMaqId($Id)->responsaveis;

						$url = 'Responsaveis';

						generateTableList($resp, $cabecalho, $url);




						echo '<h4 style="margin-top:20px;">Arquivos</h4>';

				  		include_once('includes/pageGenerateTableList.php');
				  		$cabecalho = array();
						$resp = array();

						$object = new CadMaq();
						$resp = $object->getArquivosByCadMaqId($Id)->arquivos;

						$url = 'Arquivos';

						generateTableList($resp, $cabecalho, $url);

				  	}
				  ?>

				  <br>
				  <?php if (($Id <> 0 && $IsEdit) || $Id == 0) {
				  	echo '<button type="submit" name="salvar" class="btn btn-primary" style="margin-bottom: 10px;">Salvar</button>';
				  } ?>
				  <a href="MaquinasList.php" class="btn btn-secondary" style="margin-bottom: 10px;">Voltar</a>
				</form>
				
			</div>
	</main>
<?php 
	require "footer.php";
?>