<?php
// Get the current list of products
session_start();
if (isset($_GET['deleteCart'])) {
	unset($_SESSION['productList']);
	header("Location: showcart.php");
}

$title = 'Your Shopping Cart';
include 'include/header.php'
?>

<body>
	<div class='container'>
		<form method='get' action="modifycart.php">
			<?php
			$productList = null;
			if (isset($_SESSION['productList'])) {
				$productList = $_SESSION['productList'];
				echo ("<h1>Your Shopping Cart</h1>");

				echo (make_tableheader(array('Product Id', 'Product Name', 'Quantity', 'Price', 'Subtotal')));

				$total = 0;
				foreach ($productList as $id => $prod) {
					echo ("<tr><td>" . $prod['id'] . "</td>");
					echo ("<td>" . $prod['name'] . "</td>");

					echo (make_cell(
						'<input class="form-control" type="number" min="0" value="' . $prod['quantity'] . '" name="prod_' . $prod['id'] . '">',
						'td',
						array('style' => 'width:10%')
					));
					$price = $prod['price'];

					echo ("<td align=\"right\">$" . number_format($price, 2) . "</td>");
					echo ("<td align=\"right\">$" . number_format($prod['quantity'] * $price, 2) . "</td></tr>");
					echo ("</tr>");
					$total = $total + $prod['quantity'] * $price;
				}
				echo ("<tr><td colspan=\"4\" align=\"right\"><b>Order Total</b></td><td align=\"right\">$" . number_format($total, 2) . "</td></tr>");
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

</html>