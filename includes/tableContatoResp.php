<?php 

	include_once('connection.php');

	class ContatoResp {

		public $HasError;
	    public $ErrorMsg;
	    public $RespDados = array();
	    public $responsaveis = array();

		private $db;

		public function __construct() {
			$this->db = new Connection();
			$this->db = $this->db->dbConnect();
		}

		function getContatoResp() {
			$st = $this->db->prepare("SELECT 
										Id \"Id\", 
										Nome \"Nome\", 
										Email \"Email\",
										Telefone \"Telefone\",
										InfoAdicional \"Informações Adicionais\"
									FROM ContatoResp");
			if ($st->execute()) {
				while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
					$this->responsaveis[] = $row;
				}
				if (empty($this->responsaveis)) {
					$this->HasError = true;
					$this->ErrorMsg = "Erro - Lista de Responsáveis vazia.";
					return $this;
				} else {
					$this->HasError = false;
					return $this;
				}
			}
		}

		public function getContatoRespById($Id) {
			if(!empty($Id)) {
				$st = $this->db->prepare("SELECT 
											Id \"Id\", 
											Nome \"Nome\", 
											Email \"Email\",
											Telefone \"Telefone\",
											InfoAdicional \"Informações Adicionais\"
										FROM ContatoResp
										WHERE Id = ?");
				$st->bindParam(1, $Id);
				$st->execute();

				if ($st->rowCount()==1) {
					$this->HasError = false;
					$this->RespDados = $st->fetch(PDO::FETCH_ASSOC);
					return $this;
				} else {
					$this->HasError = true;
					$this->ErrorMsg = "Erro - Não existe máquina com esse Id ($Id).";
					return $this;
				}
			} else {
				$this->HasError = true;
				$this->ErrorMsg = "Erro - Preencha o campo 'Id' na função 'getContatoRespById(Id)'.";
				return $this;
			}
		}

		public function InsertContatoResp($ContatoNome, $ContatoEmail, $ContatoTelefone, $ContatoInfoAdicional) {
			if(!empty($ContatoNome) && !empty($ContatoEmail) && !empty($ContatoTelefone)) {
				try {
					//criando o Contato
					$st = $this->db->prepare("INSERT INTO ContatoResp 
												(Nome, Email, Telefone, InfoAdicional) 
											VALUES 
												(:ContatoNome, :ContatoEmail, :ContatoTelefone, :ContatoInfoAdicional)");

					$st->bindParam(':ContatoNome', $ContatoNome);
					$st->bindParam(':ContatoEmail', $ContatoEmail);
					$st->bindParam(':ContatoTelefone', $ContatoTelefone);
					$st->bindParam(':ContatoInfoAdicional', $ContatoInfoAdicional);
					$st->execute();


					$this->HasError = false;
					return $this;
				} catch (Exception $e) {
					$this->HasError = true;
					$this->ErrorMsg = "Não foi possível cadastrar o novo Responsável.";
					return $this;
				}
			} else {
				$this->HasError = true;
				$this->ErrorMsg = "Há campos sem conteúdo!";
				return $this;
			}
		}

		public function UpdateContatoResp($Id, $ContatoNome, $ContatoEmail, $ContatoTelefone, $ContatoInfoAdicional) {
			if(!empty($Id) && !empty($ContatoNome) && !empty($ContatoEmail) && !empty($ContatoTelefone)) {
				try {
					$st = $this->db->prepare("UPDATE ContatoResp 
											SET Nome = :ContatoNome, 
												Email = :ContatoEmail,
												Telefone = :ContatoTelefone,
												InfoAdicional = :ContatoInfoAdicional
											WHERE Id = :Id");
					$st->bindParam(':Id', $Id);
					$st->bindParam(':ContatoNome', $ContatoNome);
					$st->bindParam(':ContatoEmail', $ContatoEmail);
					$st->bindParam(':ContatoTelefone', $ContatoTelefone);
					$st->bindParam(':ContatoInfoAdicional', $ContatoInfoAdicional);
					$st->execute();

					$this->HasError = false;
					return $this;
				} catch (Exception $e) {
					$this->HasError = true;
					$this->ErrorMsg = "Não foi possível alterar o cadastro do Responsável.";
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