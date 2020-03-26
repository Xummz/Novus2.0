<?php 

	include_once('connection.php');

	class Usuario {

		public $HasError;
	    public $ErrorMsg;
	    public $UserId;
	    public $UserNome;
	    public $UserDados = array();
	    public $Users = array();
	    public $HasPermissao;

		private $db;

		public function __construct() {
			$this->db = new Connection();
			$this->db = $this->db->dbConnect();
		}

		public function Login($name, $password) {
			if(!empty($name) and !empty($password)) {
				$st = $this->db->prepare("SELECT Id FROM Usuario WHERE Usuario=? AND Senha=?");
				$st->bindParam(1, $name);
				$st->bindParam(2, $password);
				$st->execute();

				if ($st->rowCount()==1) {
					$this->HasError = false;
					$this->UserId = $st->fetchColumn();
					return $this;
				} else {
					$this->HasError = true;
					$this->ErrorMsg = "Usuário/Senha errados.";
					return $this;
				}

			} else {
				$this->HasError = true;
				$this->ErrorMsg = "Preencha os campos 'Usuário' e 'Senha'.";
				return $this;
			}
		}

		public function getUsuarioNome($Id) {
			if(!empty($Id)) {
				$st = $this->db->prepare("SELECT Usuario FROM Usuario WHERE Id=?");
				$st->bindParam(1, $Id);
				$st->execute();

				if ($st->rowCount()==1) {
					$this->HasError = false;
					$this->UserNome = $st->fetchColumn();
					return $this;
				} else {
					$this->HasError = true;
					$this->ErrorMsg = "Erro - Não existe usuário com esse Id ($Id).";
					return $this;
				}
			} else {
				$this->HasError = true;
				$this->ErrorMsg = "Erro - Preencha o campo 'Id' na função 'getUsuarioNome(Id)'.";
				return $this;
			}
		}

		public function getUsuarioById($Id) {
			if(!empty($Id)) {
				$st = $this->db->prepare("SELECT Usuario \"Usuário\", Nome \"Nome\", 
												Email \"Email\", TipoUsuarioId \"TipoUsuarioId\", Senha \"Senha\" 
										FROM Usuario WHERE Id=?");
				$st->bindParam(1, $Id);
				$st->execute();

				if ($st->rowCount()==1) {
					$this->HasError = false;
					$this->UserDados = $st->fetch(PDO::FETCH_ASSOC);
					return $this;
				} else {
					$this->HasError = true;
					$this->ErrorMsg = "Erro - Não existe usuário com esse Id ($Id).";
					return $this;
				}
			} else {
				$this->HasError = true;
				$this->ErrorMsg = "Erro - Preencha o campo 'Id' na função 'getUsuarioDados(Id)'.";
				return $this;
			}
		}

		public function getUsuario() {
			$st = $this->db->prepare("SELECT Id \"Id\", Usuario \"Usuário\", Nome \"Nome\", Email \"Email\" FROM Usuario");			
			if ($st->execute()) {
				while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
					$this->users[] = $row;
				}
				if (empty($this->users)) {
					$this->HasError = true;
					$this->ErrorMsg = "Erro - Lista de Usuários vazia.";
				} else {
					$this->HasError = false;
					return $this;
				}
			}
		}

		public function getUsuarioPermissao($UsuarioId,$TipoPermissaoId) {
			if(!empty($UsuarioId) && !empty($TipoPermissaoId)) {
				$st = $this->db->prepare("SELECT 1 
										FROM Usuario
										INNER JOIN TipoUsuario ON Usuario.TipoUsuarioId = TipoUsuario.Id
										INNER JOIN TipoPermissaoTipoUsuario ON TipoUsuario.Id = TipoPermissaoTipoUsuario.TipoUsuarioId
										INNER JOIN TipoPermissao ON TipoPermissao.Id = TipoPermissaoTipoUsuario.TipoPermissaoId
										WHERE Usuario.Id = :UsuarioId
										AND TipoPermissao.Id = :TipoPermissaoId"
									);
				$st->bindParam(':UsuarioId', $UsuarioId);
				$st->bindParam(':TipoPermissaoId', $TipoPermissaoId);
				$st->execute();
				if ($st->rowCount()==1) {
					$this->HasError = false;
					$this->HasPermissao = true;
					return $this;
				} else {
					$this->HasError = false;
					$this->HasPermissao = false;
					return $this;
				}
			} else {
				$this->HasError = true;
				$this->ErrorMsg = "Erro - Há parâmetros não preenchidos!.";
				return $this;
			}
		}

		public function InsertUsuario($usuario, $senha, $nome, $email, $tipoUsuarioId) {
			if(!empty($usuario) && !empty($senha) && !empty($nome) && !empty($email) && !empty($tipoUsuarioId)) {
				try {
					$st = $this->db->prepare("INSERT INTO Usuario 
													(Usuario, Senha, Nome, Email, TipoUsuarioId) 
											VALUES 
													(:usuarioNome,:usuarioSenha,:nome,:email,:tipoUsuarioId)");
					$st->bindParam(':usuarioNome', $usuario);
					$st->bindParam(':usuarioSenha', $senha);
					$st->bindParam(':nome', $nome);
					$st->bindParam(':email', $email);
					$st->bindParam(':tipoUsuarioId', $tipoUsuarioId);
					$st->execute();
					$this->HasError = false;
					return $this;
				} catch (Exception $e) {
					$this->HasError = true;
					$this->ErrorMsg = "Não foi possível criar o novo usuário.";
					return $this;
				}
			} else {
				$this->HasError = true;
				$this->ErrorMsg = "Há campos sem conteúdo!";
				return $this;
			}
		}

		public function UpdateUsuario($Id, $usuario, $nome, $email, $tipoUsuarioId, $senha) {
			if(!empty($Id) && !empty($usuario) && !empty($nome) && !empty($email)) {
				try {
					if (!empty($senha)) {
						$st = $this->db->prepare("UPDATE Usuario 
												SET Usuario = :usuarioNome,
													Nome = :nome,
													Email = :email,
													TipoUsuarioId = :tipoUsuarioId,
													Senha = :senha
												WHERE Id = :Id");
						$st->bindParam(':Id', $Id);
						$st->bindParam(':usuarioNome', $usuario);
						$st->bindParam(':nome', $nome);
						$st->bindParam(':email', $email);
						$st->bindParam(':tipoUsuarioId', $tipoUsuarioId);
						$st->bindParam(':senha', $senha);
						$st->execute();
					} else {
						$st = $this->db->prepare("UPDATE Usuario 
												SET Usuario = :usuarioNome,
													Nome = :nome,
													Email = :email,
													TipoUsuarioId = :tipoUsuarioId
												WHERE Id = :Id");
						$st->bindParam(':Id', $Id);
						$st->bindParam(':usuarioNome', $usuario);
						$st->bindParam(':nome', $nome);
						$st->bindParam(':email', $email);
						$st->bindParam(':tipoUsuarioId', $tipoUsuarioId);
						$st->execute();
					}
					$this->HasError = false;
					return $this;
				} catch (Exception $e) {
					$this->HasError = true;
					$this->ErrorMsg = "Não foi possível alterar o usuário.";
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