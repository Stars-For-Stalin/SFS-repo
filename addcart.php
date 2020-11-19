<?php
// Get the current list of products
session_start();
$productList = null;
if (isset($_SESSION['productList'])){
	$productList = $_SESSION['productList'];
} else{ 	// No products currently in list.  Create a list.
	$productList = array();
}

// Add new product selected
// Get product information
if(isset($_GET['id']) && isset($_GET['name']) && isset($_GET['price'])){
	$id = $_GET['id'];
	$name = $_GET['name'];
	$price = $_GET['price'];
} else {
	header('Location: listprod.php');
}

// Update quantity if add same item to order again
if (isset($productList[$id])){
	$productList[$id]['quantity'] = $productList[$id]['quantity'] + 1;
} else {
	$productList[$id] = array( "id"=>$id, "name"=>$name, "price"=>$price, "quantity"=>1 );
}

$_SESSION['productList'] = $productList;
header('Location: showcart.php');
?>