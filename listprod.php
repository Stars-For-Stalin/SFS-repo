<?php
	$title = "Get Yours Before They're Gone: Stars For Stalin";
	include 'include/header.php';

	/** Get product name to search for **/
	if (isset($_GET['productName'])) {
		$name = $_GET['productName'];
	}
	if (isset($_GET['categoryName'])) {
		$category = $_GET['categoryName'];
	}
	$con = try_connect();
?>

<body>
    <div class="container">
        <h1>Search for stars:</h1>
        <form class='form-inline' method="get" action="listprod.php">
            <select size="1" name="categoryName">
                <?php
                    if ($con !== false) {
                        $sql = "select * from category;";
						$results = sqlsrv_query($con, $sql, array());
						$options = "<option>All</option>";
						while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
						    $cat = $row['categoryName'];
						    if($cat == $category){
						        $categoryId = (int)$row['categoryId'];
								$options = "<option>$category</option>$options";
                            } else {
						        $options = "$options<option>$cat</option>";
                            }
						}
						echo($options);
                    }
                ?>
            </select>
            <div class='col-8'>
                <input style='width:100%' class='form-control' type="text" name="productName" placeholder='Leave blank for all products' value="<?php echo ($name); ?>">
            </div>
            <div class='col-2'>
                <input class='form-control btn btn-primary' type="submit" value="Submit">
                <input class='form-control btn btn-secondary' id="reset-btn" type="reset" value="Reset">

            </div>
        </form>
    </div>

    <script type="text/javascript">
        document.getElementById('reset-btn').addEventListener("click", function() {
            window.location.href = removeParam("categoryName", removeParam("productName", window.location.href));
        });
    </script>

    <div class="mt-3 container">
        <?php
			$name = "%" . $name . "%";
			if ($con !== false) {
                debug_to_console("query: print " . $name);
                if(is_null($categoryId)) {
					$sql = "SELECT * from product where productName LIKE ?;";
					$ps = sqlsrv_prepare($con, $sql, array(&$name));
				} else {
					$sql = "SELECT * from product where productName LIKE ? and categoryId = ?;";
					$ps = sqlsrv_prepare($con, $sql, array(&$name,&$categoryId));
                }
                if (sqlsrv_execute($ps)) {
                    while ($product = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC)) {
                        if (!$found_products) {
                            $found_products = true;
                            echo (make_tableheader(array(
                                "",
                                "Product Name",
                                "Price"
                            )));
                        }
                        debug_to_console("looping");
                        print_product($product);
                    }
                } else {
                    debug_to_console("SQL query failed.");
                }
                if (!$found_products) {
                    echo ("no results");
                } else {
                    echo ("</table>");
                }
                disconnect($con);
            }
        ?>
    </div>
</body>

</html>