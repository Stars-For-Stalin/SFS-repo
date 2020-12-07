<?php
	$path = $_SERVER['DOCUMENT_ROOT'];
	$auth_admin_only=true; //value doesn't actually matter
	include($path.'/include/auth.php');
	$title = 'Administrator Page: Stars For Stalin';
	include($path.'/include/header.php');
?>

<body>
<div class='container'>
	<?php
		$con = try_connect();

		if ($con == false) {
			debug_to_console(sqlsrv_errors());
			die('Error connecting to DB');
		}

		$sql = 'SELECT CAST(orderDate AS DATE) as orderDate, SUM(totalAmount) as totalAmount FROM ordersummary GROUP BY CAST(orderDate AS DATE)';
		$results = sqlsrv_query($con, $sql, array());
		$tbrows = array(make_tableheader(array('Order Date', 'Total Order Amount')));
		if ($results != false) {
			while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
				$tbrow = make_row(array(
					make_cell(date_format($row['orderDate'], 'Y-m-d')),
					make_cell("$" . format_price($row['totalAmount']))
				));
				array_push($tbrows, $tbrow);
			}
		}

		echo('<h2>Administrator Sales Report by Day</h2>');
		echo (make_table($tbrows));

		$sql1 = 'SELECT * FROM customer';
		$results1 = sqlsrv_query($con, $sql1, array());
		$tbrows1 = array(make_tableheader(array('Customer Id', 'First Name', 'Last Name', 'Email', 'Phone', 'Address', 'City', 'State', 'Postal Code', 'Country', 'User Id', 'Password')));
		if ($results1 != false) {
			while ($row1 = sqlsrv_fetch_array($results1, SQLSRV_FETCH_ASSOC)) {
				$tbrow1 = make_row(array(
					make_cell($row1['customerId']),
					make_cell($row1['firstName']),
					make_cell($row1['lastName']),
					make_cell($row1['email']),
					make_cell($row1['phonenum']),
					make_cell($row1['address']),
					make_cell($row1['city']),
					make_cell($row1['state']),
					make_cell($row1['postalCode']),
					make_cell($row1['country']),
					make_cell($row1['userid']),
					make_cell($row1['password'])
				));
				array_push($tbrows1, $tbrow1);
			}
		}

		echo('<h2>Customers</h2>');
		echo (make_table($tbrows1));

		$submit = $_POST['submit'];
		$productName = $_POST['productName_entered'];
		$categoryId = $_POST['categoryId_entered'];
		$productDesc = $_POST['productDesc_entered'];
		$productPrice = $_POST['productPrice_entered'];

		$check = 0;

		if  ((!empty($productName))&&(!empty($categoryId))&&(!empty($productDesc))&&(!empty($productPrice))) {
			$sql2 = "INSERT INTO product (productName, categoryId, productDesc, productPrice) VALUES (?, ?, ?, ?)";
			$prepared_sql2 = sqlsrv_prepare($con, $sql2, array(&$productName, &$categoryId, &$productDesc, &$productPrice));
			$result_sql2 = sqlsrv_execute($prepared_sql2);
			$check = 1;
		}

		$submit1 = $_POST['submit1'];
		$productName1 = $_POST['productName1_entered'];
		$categoryId1 = $_POST['categoryId1_entered'];
		$productDesc1 = $_POST['productDesc1_entered'];
		$productPrice1 = $_POST['productPrice1_entered'];

		$check1 = 0;
		$count = 0;

		$sql3 = "SELECT * FROM product WHERE productName = ?";
		$prepared_sql3 = sqlsrv_prepare($con, $sql3, array(&$productName1));
		$result_sql3 = sqlsrv_execute($prepared_sql3);
		if(!$result_sql3) {
			die('Error connecting to DB');
		}
		if ($result_sql3 || !empty($result_sql3)) {
			while ($row = sqlsrv_fetch_array($prepared_sql3, SQLSRV_FETCH_ASSOC)) {
				$count = 1;
			}
		}
		if   ((!empty($productName1))&&(!empty($categoryId1))&&(!empty($productDesc1))&&(!empty($productPrice1))) {
			if ($count == 1) {
				$sql4 = "UPDATE product SET categoryId = ?, productDesc = ?, productPrice = ? WHERE productName = ?";
				$prepared_sql4 = sqlsrv_prepare($con, $sql4, array(&$categoryId1, &$productDesc1, &$productPrice1, &$productName1));
				$result_sql4 = sqlsrv_execute($prepared_sql4);
				$check1 = 1;
			}
		}

		$submit2 = $_POST['submit2'];
		$productName2 = $_POST['productName2_entered'];

		$check2 = 0;
		$count2 = 0;

		$sql5 = "SELECT * FROM product WHERE productName = ?";
		$prepared_sql5 = sqlsrv_prepare($con, $sql5, array(&$productName2));
		$result_sql5 = sqlsrv_execute($prepared_sql5);
		if(!$result_sql5) {
			die('Error connecting to DB');
		}
		if ($result_sql5 || !empty($result_sql5)) {
			while ($row1 = sqlsrv_fetch_array($prepared_sql5, SQLSRV_FETCH_ASSOC)) {
				$count2 = 1;
			}
		}
		if   ((!empty($productName2))) {
			if ($count2 == 1) {
				$sql6 = "DELETE FROM product WHERE productName = ?";
				$prepared_sql6 = sqlsrv_prepare($con, $sql6, array(&$productName2));
				$result_sql6 = sqlsrv_execute($prepared_sql6);
				$check2 = 1;
			}
		}

		disconnect($con);
	?>
	<div class="card">
		<article class="card-body">
		<h4 class="card-title mb-4 mt-1">Enter New Product</h4>
			<form action="" method="POST">
				<div class="form-group">
					<label>Product name: </label>
						<input type="text" name="productName_entered" class="form-control" value='<?php echo $productName;?>'/><?php if (!$submit) {if (empty($productName)) { echo "* This field cannot be left empty<br>"; } } ?>
				</div>
				<div class="form-group">
					<label>Category Id: </label>
						<input type="text" name="categoryId_entered" class="form-control" value='<?php echo $categoryId;?>'/><?php if (!$submit) {if (empty($categoryId)) { echo "* This field cannot be left empty<br>"; } } ?>
				</div>
				<div class="form-group">
					<label>Product Description: </label>
						<input type="text" name="productDesc_entered" class="form-control" value='<?php echo $productDesc;?>'/><?php if (!$submit) {if (empty($productDesc)) { echo "* This field cannot be left empty<br>"; } } ?>
				</div>
				<div class="form-group">
					<label>Product Price: </label>
						<input type="text" name="productPrice_entered" class="form-control" value='<?php echo $productPrice;?>'/><?php if (!$submit) {if (empty($productPrice)) { echo "* This field cannot be left empty<br>"; } } ?>
				</div>
				<div class="form-group">
					<button type="submit" name="submit" class="btn btn-primary btn-block"> Submit </button>
				</div>
			</form>
		</article>
	</div>
	<?php
		if ($check == 1) {
			echo "You have been successfully entered a new product<br>";
		}
	?>
	<br><br>
	<div class="card">
		<article class="card-body">
		<h4 class="card-title mb-4 mt-1">Update Product by Product Name</h4>
			<form action="" method="POST">
				<div class="form-group">
					<label>Product name: </label>
						<input type="text" name="productName1_entered" class="form-control" value='<?php echo $productName1;?>'/><?php if (!$submit1) {if ($count == 0) { echo "* There are no products with this product name. Please enter a real product name";   } } ?>
						<?php if (!$submit1) {if (empty($productName1)) { echo "* This field cannot be left empty";   } } ?>
				</div>
				<div class="form-group">
					<label>Category Id: </label>
						<input type="text" name="categoryId1_entered" class="form-control" value='<?php echo $categoryId1;?>'/><?php if (!$submit1) {if (empty($categoryId1)) { echo "* This field cannot be left empty";   } } ?>
				</div>
				<div class="form-group">
					<label>Product Description: </label>
						<input type="text" name="productDesc1_entered" class="form-control" value='<?php echo $productDesc1;?>'/><?php if (!$submit1) {if (empty($productDesc1)) { echo "* This field cannot be left empty";   } } ?>
				</div>
				<div class="form-group">
					<label>Product Price: </label>
						<input type="text" name="productPrice1_entered" class="form-control" value='<?php echo $productPrice1;?>'/><?php if (!$submit1) {if (empty($productPrice1)) { echo "* This field cannot be left empty";   } } ?>
				</div>
				<div class="form-group">
					<button type="submit" name="submit1" class="btn btn-primary btn-block"> Submit </button>
				</div>
			</form>
		</article>
	</div>
	<?php
		if ($check1 == 1) {
			echo "You have been successfully updated a product<br>";
		}
	?>
	<br><br>
	<div class="card">
		<article class="card-body">
		<h4 class="card-title mb-4 mt-1">Delete Product by Product Name</h4>
			<form action="" method="POST">
				<div class="form-group">
					<label>Product name: </label>
						<input type="text" name="productName2_entered" class="form-control" value='<?php echo $productName2;?>'/><?php if (!$submit2) {if ($count2 == 0) { echo "* There are no products with this product name. Please enter a real product name";   } } ?>
						<?php if (!$submit2) {if (empty($productName2)) { echo "* This field cannot be left empty";   } } ?>
				</div>
				<div class="form-group">
					<button type="submit" name="submit2" class="btn btn-primary btn-block"> Submit </button>
				</div>
			</form>
		</article>
	</div>
	<?php
		if ($check2 == 1) {
			echo "You have been successfully deleted a product<br>";
		}
	?>
	<br><br><br>
</div>
</body>
<?php include($path."/include/footer.php"); ?>