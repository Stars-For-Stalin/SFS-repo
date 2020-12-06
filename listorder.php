<?php
	$title = "Stars For Stalin - All Orders";
	include 'include/header.php';
?>

<body>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <div class="container">
        <h1>Order List</h1>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">Order Id</th>
                <th scope="col">Order Date</th>
                <th scope="col">Customer Id</th>
                <th scope="col">Customer Name</th>
                <th scope="col">Total Amount</th>
            </tr>
            </thead>

			<?php

				/** Create connection, and validate that it connected successfully **/
				$con = try_connect();
				if ($con != false) {



					/** Write query to retrieve all order headers **/
					$sql = "SELECT * FROM ordersummary JOIN customer ON ordersummary.customerId = customer.customerId;";
					$results = sqlsrv_query($con, $sql, array());

					if ($results != false) {
						while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
							echo (make_row(
								array(
									make_cell($row['orderId'], array("scope" => "row"), 'th'),
									make_cell(date_format($row['orderDate'], 'Y-m-d H:i:s')),
									make_cell($row['customerId']),
									make_cell($row['firstName'] . " " . $row['lastName']),
									make_cell("$" . number_format($row['totalAmount'], 2))
								)
							));

							/*
								echo ('<tr>');
								echo ('<th scope="row">' . $row['orderId'] . '</th>');
								echo ('<td>' . date_format($row['orderDate'], 'Y-m-d H:i:s') . '</td>');
								echo ('<td>' . $row['customerId'] . '</td>');
								echo ('<td>' . $row['firstName'] . " " . $row['lastName'] . '</td>');
								echo ('<td>' . "$" . number_format($row['totalAmount'], 2) . '</td>');
								echo ('</tr>');
							*/

							/* Query for individual order */
							$sql2 = "SELECT * FROM orderproduct WHERE orderId = ?;";
							$preparedStatement = sqlsrv_prepare($con, $sql2, array(&$row['orderId']));
							$result2 = sqlsrv_execute($preparedStatement);

							echo ('<tr><td colspan="3"></td><td colspan="2"><table class="table table-bordered"><thead><tr><th>Product Id</th> <th>Quantity</th> <th>Price</th></tr>');


							while ($row2 = sqlsrv_fetch_array($preparedStatement, SQLSRV_FETCH_ASSOC)) {
								echo (make_row(
									array(
										make_cell($row2['productId']),
										make_cell($row2['quantity']),
										make_cell("$" . format_price($row2['price']))
									)
								));

								/*
								echo ('<tr>');
								echo ('<td>' . $row2['productId'] . '</td>');
								echo ('<td>' . $row2['quantity'] . '</td>');
								echo ('<td>' . "$" . number_format($row2['price'], 2) . '</td>');
								echo ('</tr>');
								*/
							}

							echo ('</table></td></tr>');
						}
					}
					/** Close connection **/
					disconnect($con);
				}
			?>
        </table>
    </div>
</body>
<?php include 'include/footer.php'; ?>