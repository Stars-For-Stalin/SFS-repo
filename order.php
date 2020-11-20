<?php
/** Get customer id **/
$custId = null;
if(isset($_GET['customerId'])){
	$custId = $_GET['customerId'];
}
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>YOUR NAME Grocery Order Processing</title>
</head>
<body>

<?php
include 'include/db_credentials.php';
$productList = null;
if (isset($_SESSION['productList'])){
	$productList = $_SESSION['productList'];
}

/**Determine if valid customer id was entered
Determine if there are products in the shopping cart
If either are not true, display an error message**/

$noCustId = !is_numeric($custId) || !isset($custId);
$noProducts = empty($productList);
if ($noCustId) {
	echo "Error: Customer ID is invalid.";
}
else if ($noProducts) {
	echo "Error: Shopping cart is empty.";
}
else {
	/** Make connection and validate **/
	$con = sqlsrv_connect($server, $connectionInfo);
	if ($con === false) {
		die(print_r(sqlsrv_errors(), true));
	}
/** Save order information to database**/

	$sql1 = "SELECT "
	$totalAmount = $_GET['price'];
	$shiptoAddress = $_GET['address'];
	$shiptoCity = $_GET['city'];
	$shiptoState = $_GET['state'];
	$shiptoPostalCode = $_GET['postalCode'];
	$shiptoCountry = $_GET['country'];
	$customerId = $_GET['customerId'];
	$orderDate = date('Y-m-d H:i:s');
	/**Use retrieval of auto-generated keys.**/
	$sql = "INSERT INTO ordersummary (orderDate,totalAmount,shiptoAddress,shiptoCity,shiptoState,shiptoPostalCode,shiptoCountry,customerId) OUTPUT INSERTED.orderId VALUES(?,?,?,?,?,?,?,?)";
	$pstmt = sqlsrv_query($con,$sql,array(&$orderDate,null,&$shiptoAddress,&$shiptocity,&$shiptoState,&$shiptoPostalCode,&$shiptoCountry,&$customerId));
	if(!sqlsrv_fetch($pstmt)){
		die(print_r(sqlsrv_errors(), true));
	}
	$orderId = sqlsrv_get_field($pstmt,0);
}

/** Insert each item into OrderedProduct table using OrderId from previous INSERT **/

/** Update total amount for order record **/

/** For each entry in the productList is an array with key values: id, name, quantity, price **/

/**
	foreach ($productList as $id => $prod) {
		\\$prod['id'], $prod['name'], $prod['quantity'], $prod['price']
		...
	}
**/

/** Print out order summary **/

/** Clear session/cart **/
$_SESSION['productList'] = null;
?>
</body>
</html>

