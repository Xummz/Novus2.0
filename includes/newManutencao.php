<?php 

	include_once('connection.php');

	class Manutencao {

		public $HasError;
	    public $ErrorMsg;
	    
		private $db;

		public function __construct() {
			$this->db = new Connection();
			$this->db = $this->db->dbConnect();
		}

		public function NewManutencao($cadMaqId,$descricao,$tipoManutencaoId,$pecasReposicaoNome,$qtdePecasUtilizadas,$dtManutencao) {
			if(!empty($cadMaqId) && !empty($descricao) && !empty($dtManutencao)) {

				if (!empty($pecasReposicaoNome)) {
					$select = $this->db->prepare("SELECT Id FROM PecasReposicao WHERE Nome = :pecasReposicaoNome ");
					$select->bindParam(':pecasReposicaoNome', $pecasReposicaoNome);
					$select->execute();

					if ($select->rowCount()<>1) {
						$this->HasError = true;
						if ($select->rowCount()==0) {
							$this->ErrorMsg = "Não existe peça com esse nome!";
						} else {
							$this->ErrorMsg = "Existe mais de uma peça com esse nome!";
						}
						return $this;
					}
					$pecasReposicaoId = $select->fetchColumn();

					$select = $this->db->prepare("SELECT QtdeEstoque FROM PecasReposicao WHERE Id = :pecasReposicaoId ");
					$select->bindParam(':pecasReposicaoId', $pecasReposicaoId);
					$select->execute();

					$quantidadeDePecasTotal = $select->fetchColumn();

					if ($quantidadeDePecasTotal - $qtdePecasUtilizadas < 0) {
						$this->HasError = true;						
						$this->ErrorMsg = "O número de peças utilizadas não pode ser maior que a quantidade em estoque!";	
						return $this;
					}					

					$select = $this->db->prepare("UPDATE PecasReposicao 
												SET QtdeEstoque = QtdeEstoque - :qtdePecasUtilizadas
												WHERE Id = :pecasReposicaoId ");
					$select->bindParam(':pecasReposicaoId', $pecasReposicaoId);
					$select->bindParam(':qtdePecasUtilizadas', $qtdePecasUtilizadas);
					$select->execute();
				}

				$st = $this->db->prepare("INSERT INTO MovMaq (CadMaqId,HistMovId,Descricao,DtMovto,TipoManutencaoId,DtManutencao) 
										VALUES (:cadMaqId,'MANUTENCAO',:descricao,CURRENT_TIMESTAMP(),:tipoManutencaoId,:dtManutencao)");
				$st->bindParam(':cadMaqId', $cadMaqId);
				$st->bindParam(':descricao', $descricao);
				$st->bindParam(':tipoManutencaoId', $tipoManutencaoId);
				$st->bindParam(':dtManutencao', $dtManutencao);
				$st->execute();

				if (!empty($pecasReposicaoId) && !empty($qtdePecasUtilizadas)) {
				 	$st = $this->db->prepare("INSERT INTO MovMaqPecasReposicaoManutencao (MovMaqId,PecasReposicaoId,QtdeUtilizada) 
											VALUES (LAST_INSERT_ID(),:pecasReposicaoId,:qtdePecasUtilizadas)");
					$st->bindParam(':pecasReposicaoId', $pecasReposicaoId);
					$st->bindParam(':qtdePecasUtilizadas', $qtdePecasUtilizadas);
					$st->execute();
				 }
				 
			} else {
				$this->HasError = true;
				$this->ErrorMsg = "Há campos sem conteúdo!";
				return $this;
			}
		}		

	}

?>