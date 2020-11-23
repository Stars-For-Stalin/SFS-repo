<?php
    session_start();
    /** Get customer id **/
    if (isset($_SESSION['customerId'])) {
        $custId = $_SESSION['customerId'];
        if (!isset($_SESSION['save_password'])) {
            unset($_SESSION['customerId']);
            unset($_SESSION['save_password']);
        }
    }
    if (isset($_SESSION['productList'])) {
        $productList = $_SESSION['productList'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<?php
    $title = "Stars For Stalin - Order Summary";
    include 'include/header.php';
?>

<body>
	<div class='container'>
		<?php
            /**Determine if valid customer id was entered
             * Determine if there are products in the shopping cart
             * If either are not true, display an error message**/
            if (isset($custId) && isset($productList)) {
                $con = try_connect();
                if ($con != false) {
                    if (is_numeric($custId) && !empty($productList)) {
                        $sql0 = "SELECT customerId FROM customer WHERE customerId = ?";
                        $preparedStatement = sqlsrv_prepare($con, $sql0, array(&$custId));
                        $exec_success = sqlsrv_execute($preparedStatement);
                        if ($exec_success != false) {
                            while ($row = sqlsrv_fetch_array($preparedStatement, SQLSRV_FETCH_ASSOC)) {
                                $foundCustId = true;
                            }
                        } else {
                            debug_to_console("SQL query failed.");
                        }
                        /** Save order information to database**/
                        if ($foundCustId) {
                            $sql1 = "SELECT * FROM customer WHERE customerId = ?;";
                            $preparedStatement1 = sqlsrv_prepare($con, $sql1, array(&$custId));
                            $exec_success = sqlsrv_execute($preparedStatement1);
                            if ($exec_success != false) {
                                while ($row1 = sqlsrv_fetch_array($preparedStatement1, SQLSRV_FETCH_ASSOC)) {
                                    $shiptoAddress = $row1['address'];
                                    $shiptoCity = $row1['city'];
                                    $shiptoState = $row1['state'];
                                    $shiptoPostalCode = $row1['postalCode'];
                                    $shiptoCountry = $row1['country'];
                                }
                            } else {
                                debug_to_console("SQL query failed.");
                            }

                            $orderDate = date('Y-m-d H:i:s');
                            /**Use retrieval of auto-generated keys.**/
                            $sql = "INSERT INTO ordersummary (orderDate,totalAmount,shiptoAddress,shiptoCity,shiptoState,shiptoPostalCode,shiptoCountry,customerId) OUTPUT INSERTED.orderId VALUES(?,?,?,?,?,?,?,?)";
                            $pstmt = sqlsrv_query($con, $sql, array(&$orderDate, null, &$shiptoAddress, &$shiptoCity, &$shiptoState, &$shiptoPostalCode, &$shiptoCountry, &$custId));
                            if (!sqlsrv_fetch($pstmt)) {
                                die(print_r(sqlsrv_errors(), true));
                            }
                            $orderId = sqlsrv_get_field($pstmt, 0);

                            /** Insert each item into OrderedProduct table using OrderId from previous INSERT **/
                            foreach ($productList as $id => $prod) {
                                $sql2 = "INSERT INTO orderproduct VALUES(?,?,?,?)";
                                $pstmt2 = sqlsrv_prepare($con, $sql2, array(&$orderId, &$prod['id'], &$prod['quantity'], &$prod['price']));
                                $results2 = sqlsrv_execute($pstmt2);
                            }

                            /** Update total amount for order record **/
                            $totalAmount = 0;
                            debug_to_console("productList exists");
                            $orderList = array();
                            foreach ($productList as $productId => $prod) {
                                $id = $prod['id'];
                                $quantity = $prod['quantity'];
                                $sql = "SELECT productPrice from product where productId = ?;";
                                $ps = sqlsrv_prepare($con, $sql, array(&$productId));
                                $results = sqlsrv_execute($ps);
                                debug_to_console("looping productList");
                                if ($results != false) {
                                    debug_to_console("SQL query successful");
                                    while ($product = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC)) {
                                        if (!$found_products) {
                                            $found_products = true;
                                        }
                                        debug_to_console("looping [hopefully] single row");
                                        $price = $product['productPrice'];
                                        array_push($orderList,array(
                                            'name' => $prod['name'],
                                            'quantity' => $prod['quantity'],
                                            'price' => $price * $quantity
                                        ));
                                        $totalAmount += $quantity * $price;
                                    }
                                }
                                if (!$found_products) {
                                    echo "No products found.";
                                }
                                //$totalAmount += $prod['quantity']*$prod['price'];
                            }
                            $sql3 = "UPDATE ordersummary SET totalAmount=? WHERE orderId=?";
                            $pstmt3 = sqlsrv_prepare($con, $sql3, array(&$totalAmount, &$orderId));
                            $results3 = sqlsrv_execute($pstmt3);

                            /** Print out order summary **/
                            $sql4 = "SELECT * FROM ordersummary WHERE orderId = ?";
                            $preparedStatement4 = sqlsrv_prepare($con, $sql4, array(&$orderId));
                            $results4 = sqlsrv_execute($preparedStatement4);

                            if (!$results4) {
								debug_to_console("SQL query failed.");
								die();
                            }
                            $row = sqlsrv_fetch_array($preparedStatement4, SQLSRV_FETCH_ASSOC);
                            echo("<h1>Your Order Summary</h1>");
                            echo('<table class="table table-bordered"><tr><th>Order Id</th><th>Order Date</th><th>Total Amount</th><th>Address</th><th>City</th><th>State</th><th>Postal Code</th><th>Country</th><th>Customer Id</th>');
                            echo("<tr><td>" . $row['orderId'] . "</td>");
                            echo("<td>" . date_format($row['orderDate'], 'Y-m-d H:i:s') . "</td>");
                            echo("<td>" . "$" . number_format($row['totalAmount'], 2) . "</td>");
                            echo("<td>" . $row['shiptoAddress'] . "</td>");
                            echo("<td>" . $row['shiptoCity'] . "</td>");
                            echo("<td>" . $row['shiptoState'] . "</td>");
                            echo("<td>" . $row['shiptoPostalCode'] . "</td>");
                            echo("<td>" . $row['shiptoCountry'] . "</td>");
                            echo("<td>" . $row['customerId'] . "</td></tr>");
                            echo("</table>");
                            echo(make_tableheader(array("Product Name","Quantity","Price")));
                            foreach ($orderList as $id => $prod) {
                                echo ("<tr><td>" . $prod['name'] . "</td>");
                                echo ("<td>" . $prod['quantity'] . "</td>");
                                echo ("<td>$" . number_format($prod['price'],2) . "</td>");
                            }
                            echo("</table>");
                            /** Clear session/cart **/
                            $_SESSION['productList'] = null;

                        }
                    }
                    /** Close connection **/
                    disconnect($con);
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
		?>
	</div>
</body>

</html>