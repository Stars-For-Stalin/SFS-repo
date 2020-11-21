<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>YOUR NAME Grocery</title>
</head>
<body>
<?php
	include 'include/functions.php';

	/** Get product name to search for **/
	if (isset($_GET['productName'])){
		$name = $_GET['productName'];
	}
?>
	<h1>Search for the products you want to buy:</h1>

	<form method="get" action="listprod.php">
	<input type="text" name="productName" size="50" value="<?php echo($name); ?>">
	<input type="submit" value="Submit"><input id="reset-btn" type="reset" value="Reset"> (Leave blank for all products)
	</form>

	<script type="text/javascript">
		document.getElementById('reset-btn').addEventListener("click", function() {
			window.location.href=removeParam("productName",window.location.href);
		});
	</script>

<?php
	//
	if (isset($_GET['productName'])){
		$name = "%" . $name ."%";
		$con = try_connect();
		if($con !== false){
			debug("query: print " . $name);
			$sql = "SELECT * from product where productName LIKE ?;";
			$ps = sqlsrv_prepare($con,$sql,array(&$name));
			$results = sqlsrv_execute($ps);
			if($results != false){
				while($product = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC)){
					if(!$found_products){
						$found_products=true;
					}
					debug("looping");
					print_product($product);
				}
			}
			if(!$found_products){
				echo("no results");
			}
			disconnect($con);
		}
	}
?>

</body>
</html>