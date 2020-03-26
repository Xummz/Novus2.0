<?php
	include_once('includes/verifyLogin.php');
	include_once('includes/feedbackMessage.php');
	include_once('includes/getPermissao.php');
?>
<?php 
	require "header.php";
?>
	<main id="MainDiv" class="offset-2">

		<!--Botão para adicionar um novo registro-->
		<?php if (HasPermissao($_SESSION["UserId"],"CRIAR")) {
			echo '
		<a href="ContatoRespEdit.php?IsEdit=false&Id=0" class="botaoAdicionar btn btn-outline-secondary" role="button" method= "GET">
			Adicionar
		</a>';
		}
		?>

		<?php 
			
			include_once('includes/pageGenerateTitulo.php');
 			include_once('includes/pageGenerateTableList.php');
			include_once('includes/tableContatoResp.php');
			
			generateTitulo("Lista de Responsáveis");
			

			$cabecalho = array();
			$resp = array();

			$object = new ContatoResp();
			$resp = $object->getContatoResp()->responsaveis;

			$url = 'ContatoRespEdit.php';

			generateTableList($resp, $cabecalho, $url);
		?>
	</main>
<?php 
	require "footer.php";
?>