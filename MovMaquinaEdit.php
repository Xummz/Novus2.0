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
			if ($Id != 0) {
				include_once('includes/tableMovMaq.php');
				try {
					
					
					$objectDados = new MovMaq();
					$respDados = $objectDados->getMovMaqById($Id);
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
			generateTitulo("Log da Máquina ".$respDados->MaqDados["NomeMaq"]);
		?>
			<div class="customDiv">
				<form>
				  <div class="form-group row">
				    <label for="input1" class="col-2 col-form-label">Nome da Máquina</label>
				    <div class="col-sm-10">
					    <input type="text" readonly class="form-control customInputForm" name='nome' id="input1" 
					      	value=<?php echo "'".$respDados->MaqDados["NomeMaq"]."'"; ?>
					    >
				    </div>
				  </div>
				  <div class="form-group row">
				    <label for="input2" class="col-2 col-form-label">Descrição da Máquina</label>
				    <div class="col-sm-10">
						<input type="text" readonly class="form-control customInputForm" name='descricao' id="input2" 
							value=<?php echo "'".$respDados->MaqDados["DescMaq"]."'"; ?>
						>
				    </div>
				  </div>
				  <div class="form-group row">
				    <label for="input1" class="col-2 col-form-label">Descrição do Log</label>
				    <div class="col-sm-10">
					    <input type="text" readonly class="form-control customInputForm" name='descmov' id="input3" 
					      	value=<?php echo "'".$respDados->MaqDados["DescMov"]."'"; ?>
					    >
				    </div>
				  </div>
				  <div class="form-group row">
				    <label for="input2" class="col-2 col-form-label">Data do Log</label>
				    <div class="col-sm-10">
						<input type="text" readonly class="form-control customInputForm" name='dtmov' id="input4" 
							value=<?php echo "'".$respDados->MaqDados["DtMov"]."'"; ?>
						>
				    </div>
				  </div>
				  <div class="form-group row">
				    <label for="input2" class="col-2 col-form-label">Descrição do Histórico</label>
				    <div class="col-sm-10">
						<input type="text" readonly class="form-control customInputForm" name='deschist' id="input4" 
							value=<?php echo "'".$respDados->MaqDados["DescHist"]."'"; ?>
						>
				    </div>
				  </div>
				  <?php if ($respDados->MaqDados["HistMovId"] == "MANUTENCAO") {
					  		echo '<div class="form-group row">
								    <label for="input2" class="col-2 col-form-label">Data da Manutenção</label>
								    <div class="col-sm-10">
										<input type="text" readonly class="form-control customInputForm" name=\'dtManutencao\' id="input5" 
											value=\''.$respDados->MaqDados["DtManutencao"].'\'
										>
								    </div>
								  </div>';
				  		}
				  ?>
				  
				  <br>
				  <a href="MovMaquinasList.php" class="btn btn-secondary" style="margin-bottom: 10px;">Voltar</a>
				</form>
			</div>
	</main>
<?php 
	require "footer.php";
?>