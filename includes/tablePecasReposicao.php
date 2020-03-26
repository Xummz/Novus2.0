<?php 

	include_once('connection.php');

	class PecasReposicao {

		public $HasError;
	    public $ErrorMsg;
	    public $pecas = array();
	    public $pecaBuscada = array();

		private $db;

		public function __construct() {
			$this->db = new Connection();
			$this->db = $this->db->dbConnect();
		}

		function getPecasReposicao() {
			$st = $this->db->prepare("SELECT 
										Id \"Id\", 
										Nome \"Nome\", 
										Descricao \"Descrição\",
										QtdeEstoque \"Quantidade em Estoque\"
									FROM PecasReposicao");
			if ($st->execute()) {
				while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
					$this->pecas[] = $row;
				}
				if (empty($this->pecas)) {
					$this->HasError = true;
					$this->ErrorMsg = "Erro - Lista de Peças de Reposição vazia.";
					return $this;
				} else {
					$this->HasError = false;
					return $this;
				}
			}
		}

		public function getPecasReposicaoById($Id) {
			if(!empty($Id)) {
				$st = $this->db->prepare("SELECT 
											Nome \"Nome\", 
											Descricao \"Descrição\",
											QtdeEstoque \"Quantidade em Estoque\"
										FROM PecasReposicao 
										WHERE Id = ?");
				$st->bindParam(1, $Id);
				$st->execute();

				if ($st->rowCount()==1) {
					$this->HasError = false;
					$this->pecaBuscada = $st->fetch(PDO::FETCH_ASSOC);
					return $this;
				} else {
					$this->HasError = true;
					$this->ErrorMsg = "Erro - Não existe máquina com esse Id ($Id).";
					return $this;
				}
			} else {
				$this->HasError = true;
				$this->ErrorMsg = "Erro - Preencha o campo 'Id' na função 'getMaquinaDados(Id)'.";
				return $this;
			}
		}

		public function InsertPecasReposicao($nome, $descricao, $qtdeEstoque) {
			if(!empty($nome) && !empty($descricao) && !empty($qtdeEstoque)) {
				try {
					$st = $this->db->prepare("INSERT INTO PecasReposicao 
												(Nome, Descricao, QtdeEstoque) 
											VALUES 
												(:nome, :descricao, :qtdeEstoque)");
					$st->bindParam(':nome', $nome);
					$st->bindParam(':descricao', $descricao);
					$st->bindParam(':qtdeEstoque', $qtdeEstoque);
					$st->execute();

					$this->HasError = false;
					return $this;
				} catch (Exception $e) {
					$this->HasError = true;
					$this->ErrorMsg = "Não foi possível cadastrar a nova Peça.";
					return $this;
				}
			} else {
				$this->HasError = true;
				$this->ErrorMsg = "Há campos sem conteúdo!";
				return $this;
			}
		}

		public function UpdatePecasReposicao($Id, $nome, $descricao, $qtdeEstoque) {
			if(!empty($Id) && !empty($nome) && !empty($descricao) && !empty($qtdeEstoque)) {
				try {
					$st = $this->db->prepare("UPDATE PecasReposicao 
											SET Nome = :nome, 
												Descricao = :descricao,
												QtdeEstoque = :qtdeEstoque
											WHERE Id = :Id");
					$st->bindParam(':Id', $Id);
					$st->bindParam(':nome', $nome);
					$st->bindParam(':descricao', $descricao);
					$st->bindParam(':qtdeEstoque', $qtdeEstoque);
					$st->execute();


					$this->HasError = false;
					return $this;
				} catch (Exception $e) {
					$this->HasError = true;
					$this->ErrorMsg = "Não foi possível alterar o cadastro da Peça.";
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