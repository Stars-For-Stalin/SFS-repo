<!DOCTYPE html>
<html>
<?php 
    $title = "Stars for Stalin - Buy Now Before They're Gone"; 
    include 'include/header.php';
		//include 'include/functions.php';

		/** Get product name to search for **/
		if (isset($_GET['productName'])){
			$name = $_GET['productName'];
		}
?>
<body>
    <div class="container">
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
			$exec_success = sqlsrv_execute($ps);
            $arr = array();
			if($exec_success != false){
				while($product = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC)){
					if(!$found_products){
						$found_products=true;
                        echo(make_tableheader(array("","Product Name","Price")));
					}
					debug_to_console("looping");
					print_product($product);
				}
			} else {
                debug_to_console("SQL query failed.");
            }
			if(!$found_products){
				echo("no results");
			} else {
                echo("</table>");
            }
			disconnect($con);
		}
?>
	</div>
</body>
</html>
