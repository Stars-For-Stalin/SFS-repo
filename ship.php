<?php
include 'include/header.php';

$id = $_GET['id'];
?>

<body>

	<div class='container'>
		<?php

		$con = try_connect();

		if ($con == false)
			die('Error connection to DB');

		// TODO: Get order id      
		// TODO: Check if valid order id
		if (is_numeric($id)) {
			$sql1 = 'SELECT * FROM ordersummary WHERE orderId = ?';
			$ps1 = sqlsrv_prepare($con, $sql1, array($id));
			$result1 = sqlsrv_execute($ps1);
			if (!is_null(sqlsrv_fetch_array($ps1, SQLSRV_FETCH_ASSOC))) {
				// TODO: Start a transaction 
				sqlsrv_begin_transaction($con);

				// TODO: Retrieve all items in order with given id
				$sql2 = 'SELECT * FROM orderproduct WHERE orderId = ?';
				$ps2 = sqlsrv_prepare($con, $sql2, array($id));
				$result2 = sqlsrv_execute($ps2);

				$order_products = array();
				while ($row = sqlsrv_fetch_array($ps2, SQLSRV_FETCH_ASSOC)) {
					$order_products[$row['productId']] = $row['quantity'];
				}

				// TODO: Create a new shipment record.
				$sql3 = 'INSERT INTO shipment (shipmentDate, shipmentDesc, warehouseId) VALUES (?,?,?)';
				$ps3 = sqlsrv_prepare($con, $sql3, array(date('Y-m-d H:i:s'), null, 1));
				$result3 = sqlsrv_execute($ps3);

				// TODO: For each item verify sufficient quantity available in warehouse 1.
				// TODO: If any item does not have sufficient inventory, cancel transaction and rollback. Otherwise, update inventory for each item.
				$sql4 = 'SELECT * FROM productinventory WHERE productId = ? AND warehouseId = 1';
				$sql5 = 'UPDATE productinventory SET quantity = quantity - ? WHERE productId = ?';
				$insufficient_qty = false;
				foreach ($order_products as $prodId => $quantity) {
					$ps4 = sqlsrv_prepare($con, $sql4, array($prodId));
					$result4 = sqlsrv_execute($ps4);
					$row4 = sqlsrv_fetch_array($ps4, SQLSRV_FETCH_ASSOC);

					if ($row4['quantity'] >= $quantity) {
						echo ('Ordered Product: ' . $prodId . ' Qty: ' . $quantity);
						echo (' Pre inv: ' . $row4['quantity'] . ' Post inv: ' . ($row4['quantity']  -  $quantity));
						echo ('<br>');
						$ps5 = sqlsrv_prepare($con, $sql5, array($quantity, $prodId));
						$result5 = sqlsrv_execute($ps5);
					} else {
						$insufficient_qty = true;
					}
				}

				// TODO: Make sure to commit or rollback active transaction
				if (!$insufficient_qty) {
					sqlsrv_commit( $con );
					echo ('Success');
				} else {
					sqlsrv_rollback( $con );
					echo ('Insufficient Quantity');
				}
			} else {
				echo ("Order doesn't exsist");
			}
		} else {
			echo ('Invalid ID');
		}

		disconnect($con);
		?>

		<h2><a href="shop.html">Back to Main Page</a></h2>
	</div>
</body>

</html>