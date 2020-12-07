<?php
	session_start();
	$title = 'Grocery CheckOut Line: Stars For Stalin';

	if (isset($_SESSION['authenticatedUser'])) {
		header('Location: order.php');
	} else {
		$_SESSION['loginMessage'] = 'Please login to complete checkout!';
		header('Location: account/login.php?redirect=checkout.php');
	}