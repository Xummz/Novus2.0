<?php 

	include_once('connection.php');

	class Arquivo {

		public $HasError;
	    public $ErrorMsg;
	    public $ArquivoId;

		private $db;

		public function __construct() {
			$this->db = new Connection();
			$this->db = $this->db->dbConnect();
		}

		public function InsertArquivo($maqId, $file, $descricao, $extensao, $nome) {
			if(!empty($descricao)) {
				try {
					$st = $this->db->prepare("INSERT INTO Arquivo 
												(CadMaqId, File, Descricao, DataInclusao, Extensao, NomeOriginal) 
											VALUES 
												(:maqId, :file, :descricao, CURRENT_TIMESTAMP(), :extensao, :nome)");
					$st->bindParam(':maqId', $maqId);
					$st->bindParam(':file', $file);
					$st->bindParam(':descricao', $descricao);
					$st->bindParam(':extensao', $extensao);
					$st->bindParam(':nome', $nome);
					$st->execute();

					$this->ArquivoId = $this->db->lastInsertId();
					$this->HasError = false;
					return $this;
				} catch (Exception $e) {
					$this->HasError = true;
					$this->ErrorMsg = "Não foi possível adicionar o arquivo à máquina.";
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