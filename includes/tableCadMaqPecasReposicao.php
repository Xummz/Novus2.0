<?php 

	include_once('connection.php');

	class CadMaqPecasReposicao {

		public $HasError;
	    public $ErrorMsg;
	    
		private $db;

		public function __construct() {
			$this->db = new Connection();
			$this->db = $this->db->dbConnect();
		}

		public function InsertCadMaqPecasReposicao($cadMaqId, $pecasReposicaoNome, $qtdeMinima) {

			if(!empty($cadMaqId) && !empty($pecasReposicaoNome) && !empty($qtdeMinima)) {
				
				try {
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

					$st = $this->db->prepare("INSERT INTO CadMaqPecasReposicao 
												(CadMaqId, PecasReposicaoId, QtdeMinima) 
											VALUES 
												(:cadMaqId, :pecasReposicaoId, :qtdeMinima)");
					$st->bindParam(':cadMaqId', $cadMaqId);
					$st->bindParam(':pecasReposicaoId', $select->fetchColumn());
					$st->bindParam(':qtdeMinima', $qtdeMinima);
					$st->execute();

					$this->HasError = false;
					return $this;
				} catch (Exception $e) {
					$this->HasError = true;
					$this->ErrorMsg = "Não foi possível vincular a peça à máquina.";
					return $this;
				}
			} else {
				$this->HasError = true;
				$this->ErrorMsg = "Há campos sem conteúdo!";
				return $this;
			}
		}
		

	}

?>