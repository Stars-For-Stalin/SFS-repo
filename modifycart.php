<?php
session_start();
header('Location: showcart.php');
$productList = $_SESSION['productList'];

if (!empty($_GET)) {
	foreach ($_GET as $key => $value) {
		$key = intval(str_replace('prod_', '', $key));
		if (isset($productList[$key])) {
			echo ($value);

			if (empty($value) || $value < 0) {
				unset($productList[$key]);
			} else {
				$productList[$key]['quantity'] = $value;
			}
		}
	}

	if (empty($productList)) {
		unset($_SESSION['productList']);
	} else {
		$_SESSION['productList'] = $productList;
	}

	if(isset($_SESSION['authenticatedUser'])) {
		include_once("include/functions.php");
		$con = try_connect();
		if($con) {
			$cid = get_custId($_SESSION['authenticatedUser']);
			$sql_base = "UPDATE incart SET productName=?,quantity=?,price=? WHERE customerId = ? and productId = ?;";
			$sql = "";
			$args = array();
			foreach ($productList as $id => $prod) {
				$sql .= $sql_base;
				array_push($args, $prod['name']);
				array_push($args, $prod['quantity']);
				array_push($args, $prod['price']);
				array_push($args, $cid);
				array_push($args, $prod['id']);
			}
			if (!empty($sql)) {
				$ps = sqlsrv_prepare($con, $sql, $args);
				if (!sqlsrv_execute($ps)) {
					oops("SQL update failed.");
				}
			}
		} else {
			oops("Couldn't connect to database.");
			echo("<h4 class='text-center'>Redirecting to cart view in 5 seconds.</h4>");
			jsredirect($root . "showcart.php", 5000);
		}
	}
}
