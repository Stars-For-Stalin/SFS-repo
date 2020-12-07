<?php
session_start();

if (isset($_SESSION["authenticatedUser"])) {
	$user = $_SESSION["authenticatedUser"];
} else {
	header('Location: login.php?redirect=checkout.php');
}
if (isset($_SESSION['productList'])) {
	$productList = $_SESSION['productList'];
}

$title = "Order Summary: Stars For Stalin";
include 'include/header.php';
?>

<body>
	<div class='container'>
		<?php
		/**Determine if valid customer id was entered
		 * Determine if there are products in the shopping cart
		 * If either are not true, display an error message**/
		$con = try_connect();
		if ($con == false) {
			die('Error connecting to DB');
		}

		$sql_get_custId = 'SELECT customerId FROM customer WHERE userid = ?';
		$preparedStatement_get_custId = sqlsrv_prepare($con, $sql_get_custId, array(&$user));
		$result_get_custId = sqlsrv_execute($preparedStatement_get_custId);
		if ($result_get_custId || !empty($result_get_custId)) {
			while ($row = sqlsrv_fetch_array($preparedStatement_get_custId, SQLSRV_FETCH_ASSOC)) {
				$custId = $row['customerId'];
			}
		}

		if (isset($custId) && isset($productList)) {
			if (is_numeric($custId) && !empty($productList)) {
				/** Calculate total amount for order record **/
				$sql = "SELECT * from product where productId IN (?);";
				$args = get_array_of_inner_keys($productList, "id");
				$sql_args = str_pad("", 2 * count($args) - 1, '?,');
				$sql = str_replace("?", $sql_args, $sql);
				$ps = sqlsrv_prepare($con, $sql, $args);
				if (sqlsrv_execute($ps)) {
					$totalAmount = 0.0;
					$orderList = array();
					while ($product = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC)) {
						$id = $product['productId'];
						$quantity = $productList[$id]['quantity'];
						$price = $quantity * $product['productPrice'];
						$totalAmount += $price;
						array_push($orderList, array(
							'id' => $id,
							'price' => $price,
							'name' => $product['productName'],
							'quantity' => $quantity
						));
					}
				} else {
					goto sqlerror;
				}

				/** Get Customer information **/
				$sql = "SELECT * FROM customer WHERE customerId = ?";
				$ps = sqlsrv_prepare($con, $sql, array(&$custId));
				if (!sqlsrv_execute($ps)) {
					goto sqlerror;
				}
				$shipData = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC);
				if (is_null($shipData)) {
					debug_to_console("order.php had a problem looking up the customer ID. This shouldn't be happening.");
					oops();
					die();
				}

				/** Save order information to database**/
				$sql = "INSERT INTO ordersummary (orderDate,totalAmount,shiptoAddress,shiptoCity,shiptoState,shiptoPostalCode,shiptoCountry,customerId) OUTPUT INSERTED.orderId VALUES(?,?,?,?,?,?,?,?)";
				$ps = sqlsrv_query(
					$con,
					$sql,
					array(
						date('Y-m-d'), $totalAmount, &$shipData['address'],
						&$shipData['city'], &$shipData['state'], &$shipData['postalCode'],
						&$shipData['country'], $custId
					)
				);
				if (!sqlsrv_fetch($ps)) {
					goto sqlerror;
				}
				/**Use retrieval of auto-generated keys.**/
				$orderId = sqlsrv_get_field($ps, 0);

				/** Insert each item into OrderedProduct table using OrderId from previous INSERT **/
				$sql = "";
				$sql_base = "INSERT INTO orderproduct VALUES(?,?,?,?);";
				$args = array();
				foreach ($orderList as $id => $prod) {
					$sql = $sql . $sql_base;
					array_push($args, $orderId);
					array_push($args, $prod['id']);
					array_push($args, $prod['quantity']);
					array_push($args, $prod['price']);
				}
				if (empty($sql)) {
					debug_to_console("major problem, empty sql statement");
					oops();
					die();
				}
				$ps = sqlsrv_prepare($con, $sql, $args);
				if (!sqlsrv_execute($ps)) {
					goto sqlerror;
				}

				/** Print out order summary **/
				$sql = "SELECT * FROM ordersummary WHERE orderId = ?";
				$ps = sqlsrv_prepare($con, $sql, array(&$orderId));
				if (!sqlsrv_execute($ps)) {
					goto sqlerror;
				}
				$orderData = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC);
				if (is_null($orderData)) {
					debug_to_console("order.php couldn't retrieve the order. This shouldn't happen.");
					goto sqlerror;
				}
				print_order_summary($orderData, $orderList);
				/** Clear session/cart **/
				if (!$debugging) {
					$_SESSION['productList'] = null;
					$cid = get_custId($_SESSION['authenticatedUser']);
					$sql = "DELETE from incart WHERE customerId = ?";
					$ps = sqlsrv_prepare($con, $sql, array($cid));
					if (!sqlsrv_execute($ps)) {
						oops("SQL update failed.");
					}
				}
			} elseif (!is_numeric($custId)) {
				echo ("Error: Invalid Customer ID.");
			} else {
				echo ("Error: Shopping cart is empty.");
			}
		} elseif (!isset($custId)) {
			echo ("Error: Not logged in.");
		} else {
			echo ("Error: Shopping cart is empty.");
		}
		/** Close connection **/
		disconnect($con);
		if (false) {
			sqlerror:
			disconnect($con);
			debug_to_console("SQL query failed");
			debug_to_console(sqlsrv_errors());
			oops();
		}
		?>
	</div>
</body>

</html>