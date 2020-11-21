<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>YOUR NAME Grocery</title>
</head>
<body>
	<h1>Search for the products you want to buy:</h1>

	<form method="get" action="listprod.php">
	<input type="text" name="productName" size="50">
	<input type="submit" value="Submit"><input id="reset-but" type="reset" value="Reset"> (Leave blank for all products)
	</form>

	<script type="text/javascript">
		document.getElementById('reset-but').addEventListener("click", function() {
			window.location.href=removeParam("productName",window.location.href);
		});
	</script>

<?php
	include 'include/functions.php';

	/** Get product name to search for **/
	if (isset($_GET['productName'])){
		$name = $_GET['productName'];
		if($name != NULL){
			$name = "%" . $name ."%";
		}

		/** Create and validate connection **/
		$con = try_connect();
		if($con !== false){
			//todo: finish writing query, need to get product with the name $name ('productName')

			if($name == NULL){
				debug("query: print all");
				$sql = "SELECT * from product;";
			} else {
				debug("query: print " . $name);
				$sql = "SELECT * from product where productName LIKE ?;";
			}
			$ps = sqlsrv_prepare($con,$sql,array(&$name));
			$results = sqlsrv_execute($ps);
			
?>
		<div id="product-listing">
<?php
			if($results != false){
				/** Print out the ResultSet **/
				while($product = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC)){
					debug("looping");
					print_product($product);
				}
			} else {
				echo("no results");
			}
			disconnect($con);
		}
	}
?>
		</div>

</body>
</html>