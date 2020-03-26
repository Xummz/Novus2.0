<?php 
	
	function retornaNomeGuia($url) {
		$guias = array();
		$guias["/pages/DashBoard.php"] = "Dashboard";
		$guias["/pages/UsuariosList.php"] = "Lista de Usuários";


		foreach ($guias as $value) {
			if (array_search($value, $guias)==$url) {
				return $value;
			}
		}
		return "Novus";
	}

?>