<?php
	session_start();
	$title = 'Grocery CheckOut Line: Stars For Stalin';

	if (isset($_SESSION['authenticatedUser'])) {
		$cid = get_custId($_SESSION['authenticatedUser']);
		if ($con) {
			$sql = "DELETE from incart WHERE customerId = ?";
			$ps = sqlsrv_prepare($con, $sql, array($cid));
			if (!sqlsrv_execute($ps)) {
				oops("SQL update failed.");
			}
		} else {
			oops("Couldn't connect to database. Can't restore cart from DB");
		}
		header('Location: order.php');
	} else {
		$_SESSION['loginMessage'] = 'Please login to complete checkout!';
		header('Location: login.php?redirect=checkout.php');
	}