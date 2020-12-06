<?php
	session_start();
	$auth_user = $_SESSION['authenticatedUser'];
	$authenticated = $auth_user == null ? false : true;

	if (!$authenticated) {
		$loginMessage = "You have not been authorized to access the URL " . $_SERVER['REQUEST_URI'];
		$_SESSION['loginMessage'] = $loginMessage;
		header('Location: login.php?redirect=' . $_SERVER['REQUEST_URI']);
	} elseif (isset($auth_admin_only) && $auth_user != "admin") {
		$loginMessage = "You must be logged in as admin to access the page " . $_SERVER['REQUEST_URI'];
		$_SESSION['loginMessage'] = $loginMessage;
		header('Location: login.php?redirect=' . $_SERVER['REQUEST_URI']);
	}
?>
