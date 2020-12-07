<?php
	// Remove the user from the session to log them out
	session_start();
	unset($_SESSION['authenticatedUser']);

	$_SESSION['loginMessage'] = "You've succesfully logged out!";

	header('Location: /account/login.php');
