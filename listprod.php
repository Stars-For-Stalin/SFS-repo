<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<title>Stars for Stalin - Buy Now Before They're Gone</title>
</head>
<body>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	<div class="container">
<?php
		include 'include/functions.php';

		/** Get product name to search for **/
		if (isset($_GET['productName'])){
			$name = $_GET['productName'];
		}
?>
		<h1>Search for stars:</h1>

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
		$name = "%" . $name ."%";
		$con = try_connect();
		if($con !== false){
			debug_to_console("query: print " . $name);
			$sql = "SELECT * from product where productName LIKE ?;";
			$ps = sqlsrv_prepare($con,$sql,array(&$name));
			$results = sqlsrv_execute($ps);
			if($results != false){
				while($product = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC)){
					if(!$found_products){
						$found_products=true;
                        echo(make_tableheader(array("","Product Name","Price")));
					}
					debug_to_console("looping");
					print_product($product);
				}
			}
			if(!$found_products){
				echo("no results");
			}
			disconnect($con);
		}
?>
	</div>
</body>
</html>
