<?php

	session_start();
	if (isset($_SESSION["zalogowany"])==true){
	session_unset();
	header('Location: log.php');}
	if (isset($_SESSION["zalogowan"])==true){
		session_unset();
	header('Location: install.php');}

?>
<script>
	window.history.back();
</script>