<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>YOUR NAME Grocery Order List</title>
</head>

<body>

	<h1>Order List</h1>

	<table border="1">
		<thead>
			<tr>
				<th>Order Id</th>
				<th>Order Date</th>
				<th>Customer Id</th>
				<th>Customer Name</th>
				<th>Total Amount</th>
			</tr>
		</thead>

		<tbody>

			<?php
			include 'include/db_credentials.php';

			/** Create connection, and validate that it connected successfully **/
			$con = sqlsrv_connect($server, $connectionInfo);
			if ($con === false) {
				die(print_r(sqlsrv_errors(), true));
			}

			/** Write query to retrieve all order headers **/
			$sql = "SELECT * FROM ordersummary JOIN customer ON ordersummary.customerId = customer.customerId;";
			$results = sqlsrv_query($con, $sql, array());

			while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
				echo ("<tr><td>" . $row['orderId'] .
					"</td><td>" . date_format($row['orderDate'], 'Y-m-d H:i:s') .
					"</td><td>" . $row['customerId'] .
					"</td><td>" . $row['firstName'] . " " . $row['lastName'] .
					"</td><td>" . "$" . number_format($row['totalAmount'], 2) .
					"</td></tr>");

				/* Query for individual order */
				$sql2 = "SELECT * FROM orderproduct WHERE orderId = ?;";
				$preparedStatement = sqlsrv_prepare($con, $sql2, array(&$row['orderId']));
				$result2 = sqlsrv_execute($preparedStatement);

				echo ('<tr align="right"><td colspan="5"><table border="1"><thead><tr><th>Product Id</th> <th>Quantity</th> <th>Price</th></tr></thead>');
				echo ('<tbody>');

				while ($row2 = sqlsrv_fetch_array($preparedStatement, SQLSRV_FETCH_ASSOC)) {
					echo ("<tr><td>" . $row2['productId'] .
						"</td><td>" . $row2['quantity'] .
						"</td><td>" . "$" . number_format($row2['price'], 2) .
						"</td></tr>");
				}

				echo ('</tbody></table></td></tr>');
			}

			/** Close connection **/
			sqlsrv_close($con);
			?>
		</tbody>
	</table>
</body>

</html>