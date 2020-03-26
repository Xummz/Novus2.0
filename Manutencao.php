<?php

	include_once('includes/verifyLogin.php');

	
	if (empty($_GET['Id'])||!isset($_GET['Id'])) {
		$Id = 0;
	} else {
		$Id = (int)$_GET['Id'];
	}
	if ($Id == 0) {
		header('Location: MaquinasList.php');
	}
?>
<?php 
	require "header.php";
?>
	<main id="MainDiv" class="offset-2">
				<?php 
					if (isset($_POST['salvar'])) {
						include_once("includes/newManutencao.php");
						
						$obj = new Manutencao();
						$resp = $obj->NewManutencao($Id,$_POST['descricao'],$_POST['tipoManutencaoId'],
													$_POST['peca'],$_POST['qtdePeca'],$_POST['dtManutencao']);

						if ($resp->HasError) {
							$_SESSION["MensagemFeedBack"] = $resp->ErrorMsg; 
						} else {
							$_SESSION["MensagemFeedBack"] = 'Manutenção realizada com sucesso!';
						}
						header('Location: MaquinasList.php');
					} else {
						
					}
				?>

		<?php 			
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

			//titulo da página
			include_once('includes/pageGenerateTitulo.php');			
			generateTitulo("Realizar manutenção para a máquina ".$resp->MaqDados["Nome"]);			
		?>

			<div class="customDiv">

				<form action=<?php echo '"Manutencao.php?Id='.$_GET['Id'].'"';?> method="POST">

					<div class="form-group row">
						<label for="input1" class="col-2 col-form-label required">Descrição</label>
						<div class="col-sm-10">
						    <input type="text" class="form-control customInputForm" name='descricao' id="input1" required>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-2 col-form-label required">Tipo de Manutenção</label>
						<div class="col-sm-10">
							<select name="tipoManutencaoId">
								<option value="P">Preventiva</option>
								<option value="C">Corretiva</option>
							</select>
						</div>
					</div>

					<div class="form-group row">
						<label for="input2" class="col-2 col-form-label required">Peça utilizada na Manutenção</label>
						<div class="col-sm-10">
						    <input type="text" class="form-control customInputForm" name='peca' id="input2">
						</div>
					</div>

					<div class="form-group row">
						<label for="input3" class="col-2 col-form-label required">Quantidade utilizada da peça</label>
						<div class="col-sm-10">
						    <input type="number" class="form-control customInputForm" name='qtdePeca' id="input3">
						</div>
					</div>

					<div class="form-group row">
						<label for="input3" class="col-2 col-form-label required">Data da Manutenção</label>
						<div class="col-sm-10">
						    <input type="datetime-local" class="form-control customInputForm" name='dtManutencao' id="input4">
						</div>
					</div>

				  <br>
				  <button type="submit" name="salvar" class="btn btn-primary" style="margin-bottom: 10px;">Realizar Manutenção</button>
				  <a href="MaquinasList.php" class="btn btn-secondary" style="margin-bottom: 10px;">Voltar</a>
				</form>
				
			</div>
	</main>
<?php 
	require "footer.php";
?>