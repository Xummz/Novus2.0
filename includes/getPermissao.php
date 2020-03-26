<?php 

	include_once('connection.php');

	function HasPermissao($UsuarioId,$TipoPermissaoId) {
		$db = new Connection();
		$db = $db->dbConnect();
		$st = $db->prepare(
			"
			SELECT
				1
			FROM Usuario
			INNER JOIN TipoUsuario ON TipoUsuario.Id = Usuario.TipoUsuarioId
			INNER JOIN TipoPermissaoTipoUsuario ON TipoUsuario.Id = TipoPermissaoTipoUsuario.TipoUsuarioId
			WHERE Usuario.Id = :UsuarioId
			AND TipoPermissaoTipoUsuario.TipoPermissaoId = :TipoPermissaoId
			"
		);
		$st->bindParam(':UsuarioId', $UsuarioId);
		$st->bindParam(':TipoPermissaoId', $TipoPermissaoId);
		$st->execute();
		if ($st->rowCount()==1) {
			return true;
		} else { 
			return false; 
		}
	}	

?>