<!DOCTYPE html>
<html lang="en">
<?php
	$title = "Stars for Stalin";
	include 'include/header.php';
?>
<body>
    <div class="container">
        <?php
            // Get product name to search for
            $noproduct = true;
            $link_continueshopping = $root . "listprod.php";
            if(isset($_GET['id'])){
                $id=$_GET['id'];
                if(is_numeric($id)){
                    $con = try_connect();
                    if($con) {
						$sql = "SELECT * from product where productId = ?";
						$ps = sqlsrv_prepare($con, $sql, array(&$id));
						if (sqlsrv_execute($ps)) {
							$product = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC);
							if (!is_null($product)) {
								$noproduct = false;
								$name = $product['productName'];
								$price = $product['productPrice'];
								$title = $name . ": " . $title;
								addjs("document.title=\"$title\";");

								$link_addtocart = get_addcart_url($product);
								$img = $product['productImageURL'];
								$img = "<img src=\"$img\">";
							}
						} else {
							oops("SQL query failed.");
						}
						disconnect($con);
					} else {
						oops("Couldn't connect to database.");
                    }
                }
            }
            if ($noproduct) {
                echo("<h1>Product Not Found!</h1>");
				echo("<h2>Redirecting to product listing in 3 seconds.</h2>");
                addjs("setTimeout(function(){window.location.href=\"$link_continueshopping\";},3000);");
            } else {
                ?>
                <h1><?php echo($name); ?></h1>
                <?php echo($img); ?>
				<?php
				// TODO: Retrieve any image stored directly in database. Note: Call displayImage.php with product id as parameter.
				echo(make_table(
					array(
						make_row(
							array(
								make_cell("<h5>Id</h5>"),
								make_cell("$id")
							)
						),
						make_row(
							array(
								make_cell("<h5>Price</h5>"),
								make_cell("\$$price")
							)
						)
					),
					array("class"=>"table table-bordered")
				));
				echo("<br/>");
				echo(wrap(make_link($link_addtocart,"Add to Cart"),"h5"));
				echo("<br/>");
				echo(wrap(make_link($link_continueshopping,"Continue Shopping"),"h5"));
			}
        ?>
    </div>
</body>
</html>
