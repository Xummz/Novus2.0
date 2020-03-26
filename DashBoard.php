<?php
	include_once('includes/verifyLogin.php');
?>
<?php 
	require "header.php";
?>
	<main id="MainDiv" class="offset-2">
		
		<?php 
			include_once('includes/connection.php');
			function CountAtrasado() {
				//vermelho
				$db = new Connection();
				$db = $db->dbConnect();
				$st = $db->prepare(
					"
					SELECT 
						COUNT(CadMaq.Id) \"Cont\"
					FROM
						CadMaq
					WHERE
						datediff(CURRENT_DATE(),(SELECT MAX(MovMaq.DtManutencao) FROM MovMaq WHERE MovMaq.CadMaqId = CadMaq.Id)) > (CadMaq.PeriodoManutencaoDays)
					"
				);
				$st->execute();
					echo $st->fetch()["0"];
			}
			function CountPeriodoDeManutencao() {
				//amarelo
				$db = new Connection();
				$db = $db->dbConnect();
				$st = $db->prepare(
					"
					SELECT 
						COUNT(CadMaq.Id) \"Cont\"
					FROM
						CadMaq
					WHERE
						datediff(CURRENT_DATE(),(SELECT MAX(MovMaq.DtManutencao) FROM MovMaq WHERE MovMaq.CadMaqId = CadMaq.Id)) > (CadMaq.PeriodoManutencaoDays - CadMaq.AvisoAntesDays)
						AND datediff(CURRENT_DATE(),(SELECT MAX(MovMaq.DtManutencao) FROM MovMaq WHERE MovMaq.CadMaqId = CadMaq.Id)) < (CadMaq.PeriodoManutencaoDays)
					"
				);
				$st->execute();
					echo $st->fetch()["0"];
			}
			function CountTemTempo() {
				//verde
				$db = new Connection();
				$db = $db->dbConnect();
				$st = $db->prepare(
					"
					SELECT 
						COUNT(CadMaq.Id) \"Cont\"
					FROM
						CadMaq
					WHERE
						datediff(CURRENT_DATE(),(SELECT MAX(MovMaq.DtManutencao) FROM MovMaq WHERE MovMaq.CadMaqId = CadMaq.Id)) < (CadMaq.PeriodoManutencaoDays - CadMaq.AvisoAntesDays)
					"
				);
				$st->execute();
					echo $st->fetch()["0"];
			}

		?>

		
		<script src="js/Chart.js"></script>
		<div style="margin-left: auto; width: 80%; padding-top: 20px; margin-bottom: 20px; text-align: center;">
			<div style="float: left; padding: 0; height: 70px; padding-top: 20px; border-radius: 10px; margin-left: 5px; margin-right: 5px; background-color: RGBA(255, 0, 0, 1)" class="col-3">Atrasados: <?php CountAtrasado();?></div>
			<div title="No Período de Manutenção" style="float: left; padding: 0; height: 70px; padding-top: 20px; border-radius: 10px; margin-left: 5px; margin-right: 5px; background-color: RGBA(255, 255, 0, 1)" class="col-3">Manutenção: <?php CountPeriodoDeManutencao();?></div>
			<div style="float: left; padding: 0; height: 70px; padding-top: 20px; border-radius: 10px; margin-left: 5px; 	margin-right: 5px; background-color: RGBA(0, 255, 0, 1)" class="col-3">Em Dia: <?php CountTemTempo();?></div>
		</div>
		<div style="width: 80%; margin-left: auto; margin-right: auto; padding-top: 60px;">

			<canvas id="myChart"></canvas>
			
		</div>


			<script>

			$(document).ready(
			  function() {
			    var ctx = document.getElementById('myChart');
				var myChart = new Chart(ctx, {
					easing: 'easeOutQuart',
				    type: 'doughnut',
				    data: {
				        labels: ['Atrasados', 'Manutenção', 'Em Dia'],
				        datasets: [{
				            label: '# of Votes',
				            data: [ 
				            		<?php CountAtrasado(); ?>, 
				            		<?php CountPeriodoDeManutencao(); ?>, 
				            		<?php CountTemTempo(); ?>
				            	  ],
				            backgroundColor: [
				                'RGBA(255, 0, 0, 1)',
				                'RGBA(255, 255, 0, 1)',
				                'RGBA(0, 255, 0, 1)'
				            ],
				            borderColor: [
				                'RGBA(255, 0, 0, 1)',
				                'RGBA(255, 255, 0, 1)',
				                'RGBA(0, 255, 0, 1)'
				            ],
				            borderWidth: 1
				        }]
				    }
				    
				});

			    ctx.onclick = function(evt) {
			      var activePoints = myChart.getElementsAtEvent(evt);
			      if (activePoints[0]) {
			        var chartData = activePoints[0]['_chart'].config.data;
			        var idx = activePoints[0]['_index'];

			        var label = chartData.labels[idx];
			        var value = chartData.datasets[0].data[idx];
					
			        $('#view').val(label);
			        $('#refresh').click();
			      }
			    };
			  }
			);
			
			</script>
			<div id="form" style="display: none;">
				<form action=<?php echo 'DashBoard.php' ?> method="GET">
					<input type="text" id="view" name="view" value="1">
					<button type="submit" id="refresh"></button>
				</form>
			</div>

			
				<?php
					if (isset($_GET['view']) && ($_GET['view'] == "Atrasados" || $_GET['view'] == "Manutenção" || $_GET['view'] == "Em Dia")) 
					{
						echo '<div id="table" style="margin-top: 40px; margin-bottom: 40px">';

						include_once('includes/pageGenerateTableList.php');
						include_once('includes/tableCadMaq.php');
						$cabecalho = array();
						$resp = array();

						$object = new CadMaq();
						
						$url = 'MaquinaEdit.php';

						if ($_GET['view'] == "Atrasados") {
							$resp = $object->getCadMaqAtrasados()->maq;
							generateTableList($resp, $cabecalho, $url);
						}
						if ($_GET['view'] == "Manutenção") {
							$resp = $object->getCadMaqManutencao()->maq;
							generateTableList($resp, $cabecalho, $url);
						}
						if ($_GET['view'] == "Em Dia") {
							$resp = $object->getCadMaqEmDia()->maq;
							generateTableList($resp, $cabecalho, $url);
						}

						echo '</div>';
					}
				?>

	</main>
<?php 
	require "footer.php";
?>