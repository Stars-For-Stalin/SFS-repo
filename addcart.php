<?php
// Get the current list of products
	session_start();
	include_once("include/functions.php");
	$productList = null;
	if (isset($_SESSION['productList'])) {
		$productList = $_SESSION['productList'];
	} else {    // No products currently in list.  Create a list.
		$productList = array();
	}

	/*
	 * */
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$con = try_connect();
		if ($con) {
			$sql = "select productName,productPrice from product where productId = ?;";
			$ps = sqlsrv_prepare($con,$sql,array($id));
			if(sqlsrv_execute($ps)){
				if($product = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC)){
					$name = $product['productName'];
					$price = $product['productPrice'];
					$cid = get_custId($_SESSION['authenticatedUser']);
					debug_to_console($product);
					if (isset($productList[$id])) {
						$quantity = $productList[$id]['quantity'] + 1;
						$productList[$id]['quantity'] = $quantity;
						if(isset($_SESSION['authenticatedUser'])){
							$sql2 = "UPDATE incart SET productName=?,quantity=?,price=? WHERE customerId = ? and productId = ?";
							$ps = sqlsrv_prepare($con,$sql2,array($name,$quantity,$price,$cid,$id));
							sqlsrv_execute($ps);
						}
					} else {
						$productList[$id] = array("id" => $id, "name" => $name, "price" => $price, "quantity" => 1);
						if(isset($_SESSION['authenticatedUser'])){
							$sql2 = "INSERT INTO incart(customerId,productId,productName,quantity,price) VALUES (?,?,?,?,?);";
							$ps = sqlsrv_prepare($con,$sql2,array($cid,$id,$name,1,$price));
							sqlsrv_execute($ps);
						}
					}
				} else {
					oops();
					echo("<br><h1>That product does not exist!</h1>");
					echo("<h4 class='text-center'>Redirecting to product listing in 5 seconds.</h4>");
					jsredirect($root . "listprod.php", 5000);
					die();
					//can't find product
				}
			} else {
				oops("SQL query failed");
			}
			disconnect($con);
		} else {
			oops("Couldn't connect to database.");
			echo("<h4 class='text-center'>Redirecting to product listing in 5 seconds.</h4>");
			jsredirect($root . "listprod.php", 5000);
		}
	} else {
		header('Location: listprod.php');
	}

	$_SESSION['productList'] = $productList;
	header('Location: showcart.php');
?>