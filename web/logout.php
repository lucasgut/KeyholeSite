<?
	session_start();
	ob_start();
	unset($_SESSION['loggedin']);
	ob_end_clean();
	header("location: index.php?msg=Successfully logged out");
?>
