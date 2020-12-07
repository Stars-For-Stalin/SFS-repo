<?php
	// Remove the user from the session to log them out
	session_start();
	unset($_SESSION['authenticatedUser']);

	$_SESSION['loginMessage'] = "You've succesfully logged out!";
	if(isset($_GET['redirect'])) {
		$location = $_GET['redirect'];
		header("Location: $location");
	} else {
		header('Location: /account/login.php');
	}
