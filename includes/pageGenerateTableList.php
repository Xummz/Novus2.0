<?php 
//recebe a lista com os dados a preencher nas linhas em $linha;
//e recebe uma lista com os nomes do cabealhos em $cabecalho
include_once('includes/getPermissao.php');

function generateTableList($linha, $cabecalho, $url) {
	if (empty($linha)) {
		echo '<div class="customTable">Não há nenhum registro.</div>';
	} else {
		echo 
		'
		<div class="customTable"'; if ($url == "Responsaveis" || $url == "Arquivos" || $url == "Pecas") {echo ' style="margin-left:0;"';} echo'>
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						'?>
						<?php echo '
							<tr>
								<th scope="col" style="vertical-align: middle;">'?><?php 
								if (!empty($cabecalho)) {	
									echo implode('</th><th scope="col" style="vertical-align: middle;">', $cabecalho); 
								} else {
									$correnteValores = array();
									$correnteValores = current($linha);
									if ($url != "Responsaveis" && $url != "Pecas") {
										$correnteValores["Ações"]=""; //usado apenas a "Key" do array
									}
									unset($correnteValores["Id"]);
									echo implode('</th><th scope="col" style="vertical-align: middle;">', array_keys($correnteValores)); 
									unset($correnteValores);
								}
								?><?php echo'</th>
							</tr>
						'?>					
						<?php echo'
					</tr>
				</thead>
				<tbody>
					'?>
					<?php foreach ($linha as $row) { 
						array_map('htmlentities', $row); 
					?>
					<?php echo '
						<tr>
							<td>'?><?php 
							$colunasRegistro = array();
							$colunasRegistro = $row;
							unset($colunasRegistro["Id"]);
							echo implode('</td><td>', $colunasRegistro); ?>
							<?php 
							if ($url == 'MovMaquinaEdit.php') {
								if (HasPermissao($_SESSION["UserId"],"CONSULTAR")) {
								echo'</td>
								<td>
								<a title="Consultar" style="text-decoration:none; color:#343a40;" href="'.$url.'?Id='.$row['Id'].'">
									<span class="icon oi oi-magnifying-glass" aria-hidden="true"></span>
									</a>
								</td>
							</tr>
								';}
							} else {

								if ($url == "Arquivos") {
									if (HasPermissao($_SESSION["UserId"],"CONSULTAR")) {
									echo'</td>
									<td>
									<a title="Baixar Arquivo" style="text-decoration:none; color:#343a40;" href="includes/downloadArquivo.php?download_file='.urlencode($row['Id']).$row['Extensão'].'&nome='.$row['Nome Original'].'">
										<span class="icon oi oi-file" aria-hidden="true"></span>
										</a>';
									}
								}

								if ($url != "Responsaveis" && $url != "Arquivos" && $url != "Pecas") {
									
									if (HasPermissao($_SESSION["UserId"],"CONSULTAR")) {
									echo'</td>
									<td>
									<a title="Consultar" style="text-decoration:none; color:#343a40;" href="'.$url.'?IsEdit=false&Id='.$row['Id'].'">
										<span class="icon oi oi-magnifying-glass" aria-hidden="true"></span>
										</a>';
									}
									if (HasPermissao($_SESSION["UserId"],"EDITAR")) {
										echo '
									<a title="Editar" style="text-decoration:none; color:#343a40;" href="'.$url.'?IsEdit=true&Id='.$row['Id'].'">
										<span class="icon oi oi-pencil" aria-hidden="true"></span>
										</a>';
									}
									if ($url == 'MaquinaEdit.php') {
										if (HasPermissao($_SESSION["UserId"],"EDITAR")) {
										echo '<a title="Adicionar Arquivo" style="text-decoration:none; color:#343a40;" href="ArquivoMaquina.php?Id='.$row['Id'].'">
										<span class="icon oi oi-paperclip" aria-hidden="true"></span>
										</a>';
										}
										if (HasPermissao($_SESSION["UserId"],"EDITAR")) {
										echo '<a title="Vincular Peça de Reposição" style="text-decoration:none; color:#343a40;" href="CadMaqPecasReposicao.php?Id='.$row['Id'].'">
										<span class="icon oi oi-box" aria-hidden="true"></span>
										</a>';
										}
										if (HasPermissao($_SESSION["UserId"],"EDITAR")) {
										echo '<a title="Realizar Manutenção" style="text-decoration:none; color:#343a40;" href="Manutencao.php?Id='.$row['Id'].'">
										<span class="icon oi oi-wrench" aria-hidden="true"></span>
										</a>';
										}
										if (HasPermissao($_SESSION["UserId"],"EDITAR")) {
										echo '<a title="Vincular Responsável" style="text-decoration:none; color:#343a40;" href="CadMaqContatoResp.php?Id='.$row['Id'].'">
										<span class="icon oi oi-person" aria-hidden="true"></span>
										</a>';
										}
								}
							}
							echo '
							</td>
						</tr>
							';
							}?>
					<?php 
						} 
					?>
					<?php echo'
				</tbody>
			</table>
		</div>
		';
	}
}

?>
