<?php
	include_once('includes/verifyLogin.php');
	include_once('includes/feedbackMessage.php');
?>
<?php 
	require "header.php";
?>
	<main id="MainDiv" class="offset-2">

		<?php include_once('includes/pageGenerateTitulo.php'); generateTitulo("Log de Atividades das Máquinas"); ?>
		
		<div style="width: 85%; margin-left: auto; margin-right: auto;">
			
			<?php 
					if(!isset($_GET["pesquinaMaqNome"])) {
						$_GET["pesquinaMaqNome"] = "";
					}
					if(!isset($_GET["tipoMov"])) {
						$_GET["tipoMov"] = "";
					} 
			?>
				
			<form action=<?php echo 'MovMaquinasList.php?MaqNome='.$_GET["pesquinaMaqNome"].'&TpMov='.$_GET["tipoMov"].'';?> method="GET">

			<div class="form-group row">
				<label for="input1" class="col-2 col-form-label">Pesquisar Máquina</label>
				<div class="col-sm-10">
				    <input type="text" class="form-control customInputForm" name='pesquinaMaqNome' id="input1" 
				    value=<?php if (isset($_GET["pesquinaMaqNome"])) {echo '"'.$_GET["pesquinaMaqNome"].'"';} ?>>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-2 col-form-label">Tipo de Log</label>
				<div class="col-sm-10">
					<select name="tipoMov">
						<option value="" 
							<?php if (!isset($_GET["tipoMov"])) {echo ' selected';} ?> 
						>-</option>
						<option value="ALTERACAO" 
							<?php if (isset($_GET["tipoMov"]) && ($_GET["tipoMov"] == "ALTERACAO")) {echo ' selected';} ?> 
						>Alteração</option>
						<option value="CRIACAO" 
							<?php if (isset($_GET["tipoMov"]) && ($_GET["tipoMov"] == "CRIACAO")) {echo ' selected';} ?> 
						>Criação</option>
						<option value="MANUTENCAO" 
							<?php if (isset($_GET["tipoMov"]) && ($_GET["tipoMov"] == "MANUTENCAO")) {echo ' selected';} ?> 
						>Manutenção</option>
					</select>
				</div>
			</div>
				<button type="submit" value="salvar" class="btn btn-secondary" style="margin-bottom: 10px;">Pesquisar</button>
			</form>


		</div>

	<?php 
		if (isset($_GET['salvar']) || isset($_GET["pesquinaMaqNome"]) || isset($_GET["tipoMov"])) {

			
 			include_once('includes/pageGenerateTableList.php');
			include_once('includes/tableMovMaq.php');
			
			$cabecalho = array();

			$resp = array();
			$object = new MovMaq();
			$resp = $object->getMovMaqCustom($_GET['pesquinaMaqNome'],$_GET['tipoMov'])->maq;

			$url = 'MovMaquinaEdit.php';


			

			generateTableList($resp, $cabecalho, $url);

		} else {
			
				
	 			include_once('includes/pageGenerateTableList.php');
				include_once('includes/tableMovMaq.php');
				
				$cabecalho = array();

				$resp = array();

				$object = new MovMaq();
				$resp = $object->getMovMaq()->maq;

				$url = 'MovMaquinaEdit.php';


				

				generateTableList($resp, $cabecalho, $url);
			
		}
	?>
		
	</main>
<?php 
	require "footer.php";
?>