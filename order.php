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
<?php
	$title="YOUR NAME Grocery Order Processing";
	include 'include/header.php';
?>
<body>

<?php
$productList = null;
if (isset($_SESSION['productList'])){
	$productList = $_SESSION['productList'];
}

/**Determine if valid customer id was entered
Determine if there are products in the shopping cart
If either are not true, display an error message**/

$con =  try_connect();
if ($con != false) {

$numCustId = !is_numeric($custId);
$sql0 = "SELECT customerId FROM customer WHERE customerId = ?";
$preparedStatement = sqlsrv_prepare($con, $sql0, array(&$custId));
$results = sqlsrv_execute($preparedStatement);
$custIdInTable = false;
	if($results != false){
		while ($row = sqlsrv_fetch_array($preparedStatement, SQLSRV_FETCH_ASSOC)) {
			if($row['customerId'] == $custId) {
				$custIdInTable = true;
			}
		}
	}

	$noCustId = $numCustId || !$custIdInTable;
	$noProducts = empty($productList);
	if ($noCustId) {
		echo "Error: Customer ID is invalid.";
	}
	else if ($noProducts) {
		echo "Error: Shopping cart is empty.";
	}
	else {
		/** Save order information to database**/

		$sql1 = "SELECT * FROM customer WHERE customerId = ?;";
		$preparedStatement1 = sqlsrv_prepare($con, $sql1, array(&$custId));
		$results1 = sqlsrv_execute($preparedStatement1);
		if($results1 != false){
			while ($row1 = sqlsrv_fetch_array($preparedStatement1, SQLSRV_FETCH_ASSOC)) {
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
		debug_to_console("productList exists");
		foreach ($productList as $id => $prod) {
			/*
			$id = $prod['id'];
			$quantity = $prod['quantity'];
			$sql = "SELECT productPrice from product where productId = ?;";
			$ps = sqlsrv_prepare($con,$sql,array(&$id));
			$results = sqlsrv_execute($ps);
			debug_to_console("looping productList");
			if($results != false){
				debug_to_console("SQL query successful");
				while($product = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC)){
					if(!$found_products){
						$found_products=true;
					}
					debug_to_console("looping [hopefully] single row");
					$price = $product['productPrice'];
					$totalAmount += $quantity*$price;
				}
			} 
			if(!$found_products){
				echo "No products found.";
			} */
			$totalAmount += $prod['quantity']*$prod['price'];
		}
		$sql3 = "UPDATE ordersummary SET totalAmount=? WHERE orderId=?";
		$pstmt3 = sqlsrv_prepare($con,$sql3,array(&$totalAmount,&$orderId));
		$results3 = sqlsrv_execute($pstmt3);

		/** Print out order summary **/
		$sql4 = "SELECT * FROM ordersummary WHERE orderId = ?";
		$preparedStatement4 = sqlsrv_prepare($con, $sql4, array(&$orderId));
		$results4 = sqlsrv_execute($preparedStatement4);

		if($results4 != false){
			while ($row = sqlsrv_fetch_array($preparedStatement4, SQLSRV_FETCH_ASSOC)) {
				echo("<h1>Your Order Summary</h1>");
				echo('<table class="table"><tr><th>Order Id</th><th>Order Date</th><th>Total Amount</th><th>Address</th><th>City</th><th>State</th><th>Postal Code</th><th>Country</th><th>Customer Id</th>');
				echo("<tr><td align=\"right\">". $row['orderId'] . "</td>");
				echo("<td>" . date_format($row['orderDate'], 'Y-m-d H:i:s') . "</td>");
				echo("<td align=\"right\">" . "$" . number_format($row['totalAmount'], 2) . "</td>");
				echo("<td align=\"right\">" . $row['shiptoAddress'] . "</td>");
				echo("<td align=\"right\">" . $row['shiptoCity'] . "</td>");
				echo("<td align=\"right\">" . $row['shiptoState'] . "</td>");
				echo("<td align=\"right\">" . $row['shiptoPostalCode'] . "</td>");
				echo("<td align=\"right\">" . $row['shiptoCountry'] . "</td>");
				echo("<td align=\"right\">" . $row['customerId'] . "</td></tr>");

				echo("<table><tr><th>Product Id</th><th>Product Name</th><th>Quantity</th>");
				foreach ($productList as $id => $prod) {
					echo("<tr><td>". $prod['id'] . "</td>");
					echo("<td>" . $prod['name'] . "</td>");
					echo("<td align=\"center\">". $prod['quantity'] . "</td>");
				}
			}
		}

		/** Clear session/cart **/
		$_SESSION['productList'] = null;
	}

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

