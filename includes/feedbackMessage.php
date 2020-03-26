<?php 



	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	if(isset($_SESSION["MensagemFeedBack"]) && !empty($_SESSION['UserId'])) {
		echo '<div id="FeedBack" style="text-align: center;position: absolute;width: 100%; pointer-events:none; transition: opacity 0.5s ease-out"><div class="offset-2" style="background-color: #0778d4;width: 50%;margin-right: auto;margin-left: auto;margin-top: 10px;border: thin solid #cecece; border-radius: 5px;">'.$_SESSION["MensagemFeedBack"].'</div></div>';
		echo '<script>
				function hideFeedback() {
					document.getElementById("FeedBack").style.opacity= 0;
				}
				setTimeout(hideFeedback, 3000);
			  </script>';
		unset($_SESSION['MensagemFeedBack']);
	}




?>