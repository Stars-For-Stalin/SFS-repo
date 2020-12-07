<?php
// Get the current list of products
	session_start();
	if (isset($_GET['deleteCart'])) {
		unset($_SESSION['productList']);
		header("Location: showcart.php");
	}

	$title = 'Your Shopping Cart: Stars For Stalin';
	include 'include/header.php';
	$con = try_connect();
	if($_SESSION['authenticatedUser']) {
		$cid = get_custId($_SESSION['authenticatedUser']);
		if ($con) {
			$sql = "SELECT * from incart WHERE customerId = ?";
			$ps = sqlsrv_prepare($con,$sql,array($cid));
			if(sqlsrv_execute($ps)){
				while ($cart_item = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC)) {
                    $name = $cart_item['productName'];
                    $pid = $cart_item['productId'];
                    $quantity = $cart_item['quantity'];
                    $price = $cart_item['price'];
					$_SESSION['productList'][$pid] = array("id" => $pid, "name" => $name, "price" => $price, "quantity" => $quantity);
				}
			}
		} else {
			oops("Couldn't connect to database. Can't restore cart from DB");
		}
	}
?>

<body>
	<div class='container'>
		<form method='get' action="modifycart.php">
			<?php
				if (isset($_SESSION['productList'])) {
					$productList = $_SESSION['productList'];
					echo ("<h1>Your Shopping Cart</h1>");
					echo (make_tableheader(array('Product Id', 'Product Name', 'Quantity', 'Price', 'Subtotal')));

					$total = 0;
					foreach ($productList as $id => $prod) {
					    $name = $prod['name'];
					    $quantity = $prod['quantity'];
					    $price = $prod['price'];
						$price_str = format_price($price);
					    $subtotal = $quantity * $price;
					    $subtotal_str = format_price($subtotal);
					    $remove_btn = "<input class='form-control btn btn-md btn-danger' id='remove-$id' type='submit' value='remove'>";

					    $cells = array();
						array_push($cells,make_cell($id));
						array_push($cells,make_cell($name));
						$quantity_row =
							"<div class='row pl-3'>" .
                                "<input class='col-4 form-control' id='quant-$id' type='number' min='0' value='$quantity' name='prod_$id'>".
							    "<div class='col-8'>$remove_btn</div>".
							"</div>";
						$attr = array("style" => "width:21%");
						array_push($cells,make_cell($quantity_row, $attr, "td"));
						$attr = array("class" => "text-right");
						array_push($cells,make_cell("$$price_str", $attr, "td"));
						array_push($cells,make_cell("$$subtotal_str", $attr, "td"));

						echo(make_row($cells));
						addjs("document.getElementById('remove-$id').addEventListener('click', function() {document.getElementById('quant-$id').setAttribute('value',0);});");
						$total = $total + $subtotal;
					}
					$total = format_price($total);
					echo ("<tr><td colspan='4' class='text-right'><b>Order Total</b></td><td class='text-right'>$$total</td></tr>");
					echo ("</table>");
				} else {
					echo ("<H1>Your shopping cart is empty!</H1>");
				}
			?>
			<div class="row">
				<div class="col-lg-8 col-sm-6">
					<a class="btn btn-secondary btn-md" href="listprod.php">Continue Shopping</a>
				</div>
				<div class="col-lg-2 col-sm-3">
					<?php
						if (isset($_SESSION['productList']))
							echo ('<input style="width:100%" type="submit" class="btn btn-info btn-md" value="Update Cart">');
					?>
				</div>
				<div class="col-lg-2 col-sm-3">
					<?php
						if (isset($_SESSION['productList']))
							echo ('<a style="width:100%" class="btn btn-primary btn-md" href="checkout.php">Check Out</a>');
					?>
				</div>
			</div>
		</form>
	</div>
</body>

<?php include 'include/footer.php'; ?>