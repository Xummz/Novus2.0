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
						include_once("includes/tableArquivo.php");
						$obj = new Arquivo();

						$copiaArray = array();
						$copiaText = $_FILES['file']['name'];
						$copiaArray = explode(".", $copiaText);

						$extension = ".".$copiaArray[count($copiaArray)-1];
						
						$resp = $obj->InsertArquivo($Id,0,$_POST['descricao'],$extension, $_FILES['file']['name']);
						$arqId = $resp->ArquivoId;						

						if ($resp->HasError) {
							$_SESSION["MensagemFeedBack"] = $resp->ErrorMsg;
						} else if ($resp->ArquivoId = 0 or $resp->ArquivoId = "") {
							$_SESSION["MensagemFeedBack"] = "Não foi possível adicionar o arquivo à máquina.";
						} else {
							$_SESSION["MensagemFeedBack"] = 'Arquivo adicionado com sucesso!';

								
							    if( $_FILES['file']['name'] != "" ) {
							    	
								    $path=$arqId.$extension;
								    
								    $pathto="includes/arquivos/".$path;
								    move_uploaded_file( $_FILES['file']['tmp_name'],$pathto) or die( "Could not copy file!");
								}
								else {
								    die("No file specified!");
								}
						   
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
			generateTitulo("Adicionar arquivo à máquina ".$resp->MaqDados["Nome"]);			
		?>

			<div class="customDiv">

				<form action=<?php echo '"ArquivoMaquina.php?Id='.$_GET['Id'].'"';?> method="POST" enctype="multipart/form-data">

					
					<div class="form-group row">
						<label for="input1" class="col-2 col-form-label required">Arquivo</label>
						<div class="col-sm-10">
						    <input type="file" class="customInputForm" name="file" id="file">
						</div>
					</div>


					<div class="form-group row">
						<label for="input1" class="col-2 col-form-label required">Descrição</label>
						<div class="col-sm-10">
						    <input type="text" class="form-control customInputForm" name='descricao' id="input2">
						</div>
					</div>

				  <br>
				  <button type="submit" name="salvar" class="btn btn-primary" style="margin-bottom: 10px;">Adicionar Arquivo</button>
				  <a href="MaquinasList.php" class="btn btn-secondary" style="margin-bottom: 10px;">Voltar</a>
				</form>
				
			</div>
	</main>
<?php 
	require "footer.php";
?>