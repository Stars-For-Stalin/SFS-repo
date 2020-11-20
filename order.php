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
$con =  try_connect();
if ($con != false) {
$sql0 = "SELECT customerId FROM customer WHERE customerId = ?;"
$preparedStatement = sqlsrv_prepare($con, $sql0, &$custId);
$results = sqlsrv_execute($preparedStatement);
$custIdInTable = false;
	if($results != false){
		while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
			if($row['customerId'] == $custId) {
				$custIdInTable = true;
			}
		}
	}

	$noCustId = !is_numeric($custId) || !$custIdInTable;
	$noProducts = empty($productList);
	if ($noCustId) {
		echo "Error: Customer ID is invalid.";
	}
	else if ($noProducts) {
		echo "Error: Shopping cart is empty.";
	}
	else {
		/** Save order information to database**/

		$sql1 = "SELECT * FROM customer WHERE customerId = ?;"
		$preparedStatement1 = sqlsrv_prepare($con, $sql1, &$custId);
		$results1 = sqlsrv_execute($preparedStatement1);
		if($results1 != false){
			while ($row1 = sqlsrv_fetch_array($results1, SQLSRV_FETCH_ASSOC)) {
				$shiptoAddress = $row1['address'];
				$shiptoCity = $row1['city'];
				$shiptoState = $row1['state'];
				$shiptoPostalCode = $row1['postalCode'];
				$shiptoCountry = $row1['country'];
			}
		}

		$orderDate = date('Y-m-d H:i:s');
		/**Use retrieval of auto-generated keys.**/
		$sql = "INSERT INTO ordersummary (orderDate,totalAmount,shiptoAddress,shiptoCity,shiptoState,shiptoPostalCode,shiptoCountry,customerId) OUTPUT INSERTED.orderId VALUES(?,?,?,?,?,?,?,?)";
		$pstmt = sqlsrv_query($con,$sql,array(&$orderDate,null,&$shiptoAddress,&$shiptocity,&$shiptoState,&$shiptoPostalCode,&$shiptoCountry,&$custId));
			if(!sqlsrv_fetch($pstmt)){
				die(print_r(sqlsrv_errors(), true));
			}
		$orderId = sqlsrv_get_field($pstmt,0);

		/** Insert each item into OrderedProduct table using OrderId from previous INSERT **/
		foreach ($productList as $id => $prod) {
			$sql2 = "INSERT INTO orderproduct VALUES(?,?,?,?)";
			$pstmt2 = sqlsrv_prepare($con,$sql2,array(&$orderId,&$prod['id'],&$prod['quantity'],&$prod['price']));
			$results2 = sqlsrv_execute($pstmt2);
		}

		/** Update total amount for order record **/
		$totalAmount = 0;
		foreach ($productList as $id => $prod) {
			$totalAmount += $prod['price'] * $prod['quantity'];
		}
		$sql3 = "UPDATE ordersummary SET totalAmount=? WHERE orderId=$orderId";
		$pstmt3 = sqlsrv_prepare($con,$sql2,array(&$orderId,&$prod['id'],&$prod['quantity'],&$prod['price']));
	}

	/** Print out order summary **/
	$sql4 = "SELECT * FROM ordersummary WHERE orderId = ?";
	$preparedStatement4 = sqlsrv_prepare($con, $sql4, &$orderId);
	$results4 = sqlsrv_query($preparedStatement4);

	if($results4 != false){
		while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
			echo ("<tr><td>" . $row['orderId'] .
				"</td><td>" . date_format($row['orderDate'], 'Y-m-d H:i:s') .
				"</td><td>" . "$" . number_format($row['customerId']) .
				"</td><td>" . $row['shiptoAdress'] .
				"</td><td>" . $row['shiptoCity'] .
				"</td><td>" . $row['shiptoState'] .
				"</td><td>" . $row['shiptoPostalCode'] .
				"</td><td>" . $row['shiptoCountry'] .
				"</td><td>" . $row['customerId'] .
				"</td></tr>");
		}
	}

	/** Clear session/cart **/
	$_SESSION['productList'] = null;

	/** Close connection **/
	disconnect($con);
}

/** For each entry in the productList is an array with key values: id, name, quantity, price **/

/**foreach ($productList as $id => $prod) {
		\\$prod['id'], $prod['name'], $prod['quantity'], $prod['price']
		...
	}**/

?>
</body>
</html>

