

/*JavaScript para fechar/abrir o menu de navigação lateral*/


function toggleNav() {
	if (!document.getElementById("MainDiv").className.match(/(?:^|\s)offset-2(?!\S)/)) {
		document.getElementById("mySidenav").style.width = "16.666667%";
		
		document.getElementById("MainDiv").className = "offset-2";			    
	} else {
		document.getElementById("mySidenav").style.width = "0%";

		document.getElementById("MainDiv").className = "fullWidthMain";
	}
}