<?php
	// Remove the user from the session to log them out	
    session_start();
	$_SESSION['authenticatedUser'] = null;
	header('Location: index.php');	
?>

