<?php 

	include_once('connection.php');

	class MovMaq {

		public $HasError;
	    public $ErrorMsg;
	    public $MaqDados = array();
	    public $maq = array();

		private $db;

		public function __construct() {
			$this->db = new Connection();
			$this->db = $this->db->dbConnect();
		}

		function getMovMaq() {
			$st = $this->db->prepare("SELECT 
									MovMaq.Id \"Id\",
									CadMaq.Nome \"Nome da Máquina\",
									CadMaq.Descricao \"Descrição da Máquina\",
									MovMaq.DtMovto \"Data do Log\",
									MovMaq.Descricao \"Descrição do Log\",
									HistMov.Descricao \"Descrição do Histórico\" 
								FROM CadMaq
								INNER JOIN MovMaq ON CadMaq.Id = MovMaq.CadMaqId
								INNER JOIN HistMov ON HistMov.Id = MovMaq.HistMovId
								LEFT JOIN MovCadMaq ON MovMaq.Id = MovCadMaq.MovMaqId
								ORDER BY MovMaq.DtMovto DESC");

			if ($st->execute()) {
				while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
					$this->maq[] = $row;
				}
				if (empty($this->maq)) {
					$this->HasError = true;
					$this->ErrorMsg = "Erro - Lista de Logs de Máquinas vazia.";
					return $this;
				} else {
					$this->HasError = false;
					return $this;
				}
			}
		}

		function getMovMaqById($Id) {
			if(!empty($Id)) {
				$st = $this->db->prepare("SELECT 
											CASE WHEN MovCadMaq.Id IS NULL THEN CadMaq.Nome
											ELSE MovCadMaq.Nome END \"NomeMaq\", 
											CASE WHEN MovCadMaq.Id IS NULL THEN CadMaq.Nome
											ELSE MovCadMaq.Descricao END \"DescMaq\" ,
											MovMaq.Descricao \"DescMov\" ,
											HistMov.Descricao \"DescHist\" ,
											MovMaq.DtMovto \"DtMov\",
											MovMaq.DtManutencao \"DtManutencao\",
											HistMov.Id \"HistMovId\"
										FROM CadMaq
										INNER JOIN MovMaq ON CadMaq.Id = MovMaq.CadMaqId
										INNER JOIN HistMov ON HistMov.Id = MovMaq.HistMovId
										LEFT JOIN MovCadMaq ON MovCadMaq.MovMaqId = MovMaq.Id
										WHERE MovMaq.Id = ?");
				$st->bindParam(1, $Id);
				$st->execute();

				if ($st->rowCount()==1) {
					$this->HasError = false;
					$this->MaqDados = $st->fetch(PDO::FETCH_ASSOC);
					return $this;
				} else {
					$this->HasError = true;
					$this->ErrorMsg = "Erro - Não existe Log com esse Id ($Id).";
					return $this;
				}
			} else {
				$this->HasError = true;
				$this->ErrorMsg = "Erro - Preencha o campo 'Id' na função 'getMaquinaDadosMov(Id)'.";
				return $this;
			}
		}
		
		function getMovMaqCustom($maqNome,$tipoHistId) {
			$st = $this->db->prepare("SELECT 
									MovMaq.Id \"Id\", 
									CASE WHEN MovCadMaq.Id IS NULL 
									THEN CadMaq.Nome
									ELSE MovCadMaq.Nome END \"Nome da Máquina\", 
									CASE WHEN MovCadMaq.Id IS NULL 
									THEN CadMaq.Descricao
									ELSE MovCadMaq.Descricao END \"Descrição da Máquina\" ,
									MovMaq.DtMovto \"Data do Log\",
									MovMaq.Descricao \"Descrição do Log\" ,
									HistMov.Descricao \"Descrição do Histórico\" 
								FROM CadMaq
								INNER JOIN MovMaq ON CadMaq.Id = MovMaq.CadMaqId
								INNER JOIN HistMov ON HistMov.Id = MovMaq.HistMovId
								LEFT JOIN MovCadMaq ON MovMaq.Id = MovCadMaq.MovMaqId
								WHERE (MovCadMaq.Nome like '%$maqNome%' OR CadMaq.Nome like '%$maqNome%')
								AND (HistMov.Id = '$tipoHistId' OR '$tipoHistId' = '')
								ORDER BY MovMaq.DtMovto DESC");
			
			$st->execute();
			if ($st->rowCount()==0) {
				$this->HasError = false;
				return $this;
			}
				

			if ($st->rowCount()>0) {
				while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
					$this->maq[] = $row;
				}
				if (empty($this->maq)) {
					$this->HasError = true;
					$this->ErrorMsg = "Erro - Lista de Logs de Máquinas vazia.";
					return $this;
				} else {
					$this->HasError = false;
					return $this;
				}
			}
		}

	}

?>