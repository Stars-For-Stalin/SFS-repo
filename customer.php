<?php
include 'auth.php';
$user = $_SESSION['authenticatedUser'];

$title = 'Customer Page';
include 'include/header.php';
?>

<body>
	<div class='container'>
		<?php
		// TODO: Print Customer information
		$con = try_connect();
		if ($con !== false) {
			$sql = "SELECT * FROM customer WHERE userid = ?;";
			$preparedStatement = sqlsrv_prepare($con, $sql, array(&$user));
			$result = sqlsrv_execute($preparedStatement);
			$row = sqlsrv_fetch_array($preparedStatement, SQLSRV_FETCH_ASSOC);
			echo "
				<table class='table table-bordered'>
				<tr>
				<th>Id</th>
				<td>" . $row['customerId'] . "</td>
				</tr>
				<tr>
				<th>First Name</th>
				<td>" . $row['firstName'] . "</td>
				</tr>
				<tr>
				<th>Last Name</th>
				<td>" . $row['lastName'] . "</td>
				</tr>
				<tr>
				<th>Email</th>
				<td>" . $row['email'] . "</td>
				</tr>
				<tr>
				<th>Phone</th>
				<td>" . $row['phonenum'] . "</td>
				</tr>
				<tr>
				<th>Address</th>
				<td>" . $row['address'] . "</td>
				</tr>
				<tr>
				<th>City</th>
				<td>" . $row['city'] . "</td>
				</tr>
				<tr>
				<th>State</th>
				<td>" . $row['state'] . "</td>
				</tr>
				<tr>
				<th>Postal Code</th>
				<td>" . $row['postalCode'] . "</td>
				</tr>
				<tr>
				<th>Country</th>
				<td>" . $row['country'] . "</td>
				</tr>
				<tr>
				<th>User id</th>
				<td>" . $user . "</td>
				</tr>
				<tr>
				</table>";

			$submit = $_POST['submit'];
			$password = $_POST['password_entered'];
	
			$check = 0;

			if   ((!empty($password))) {
				$sql1 = "UPDATE customer SET password = ? WHERE userid = ?";
				$prepared_sql1 = sqlsrv_prepare($con, $sql1, array(&$password, &$user));
				$result_sql1 = sqlsrv_execute($prepared_sql1);
				$check = 1;
			}

			$submit1 = $_POST['submit1'];
			$address = $_POST['address_entered'];
			$city = $_POST['city_entered'];
			$state = $_POST['state_entered'];
			$postalCode = $_POST['postalCode_entered'];
			$country = $_POST['country_entered'];

			$check1 = 0;

			if   ((!empty($address))&&(!empty($city))&&(!empty($state))&&(!empty($postalCode))&&(!empty($country))) {
				$sql2 = "UPDATE customer SET address = ?, city = ?, state = ?, postalCode = ?, country = ? WHERE userid = ?";
				$prepared_sql2 = sqlsrv_prepare($con, $sql2, array(&$address, &$city, &$state, &$postalCode, &$country, &$user));
				$result_sql2 = sqlsrv_execute($prepared_sql2);
				$check1 = 1;
			}

			// Make sure to close connection
			disconnect($con);
		}
		?>
	<form action="" method="POST">

	<table>
	<tr><td>Change Password: </td>

	<td>
	<input type="password" name="password_entered" value='<?php echo $password;?>'/><?php if ($submit) {if (empty($password)) { echo "* This field cannot be left empty";   } } ?>
	</td></tr>
	</table>


	<br><br>
	<input type="submit" name="submit" value="Change Password"/><br><br>

	<?php
		if ($check == 1) {
			echo "You have successfully changed your password<br>";
			echo "Please re-login <a href='http://localhost/login.php'>here.</a>";
		}
	?>


	<form action="" method="POST">

	<table>
	<tr><td>Change Address: </td>

	<td>
	<input type="text" name="address_entered" value='<?php echo $address;?>'/><?php if ($submit1) {if (empty($address)) { echo "* This field cannot be left empty";   } } ?>
	</td></tr><br>

	<tr><td>Change City: </td>

	<td>
	<input type="text" name="city_entered" value='<?php echo $city;?>'/><?php if ($submit1) {if (empty($city)) { echo "* This field cannot be left empty";   } } ?>
	</td></tr><br>

	<tr><td>Change State: </td>

	<td>
	<input type="text" name="state_entered" value='<?php echo $state;?>'/><?php if ($submit1) {if (empty($state)) { echo "* This field cannot be left empty";   } } ?>
	</td></tr><br>

	<tr><td>Change Postal Code: </td>

	<td>
	<input type="text" name="postalCode_entered" value='<?php echo $postalCode;?>'/><?php if ($submit1) {if (empty($postalCode)) { echo "* This field cannot be left empty";   } } ?>
	</td></tr><br>

	<tr><td>Change Country: </td>

	<td>
	<input type="text" name="country_entered" value='<?php echo $country;?>'/><?php if ($submit1) {if (empty($country)) { echo "* This field cannot be left empty";   } } ?>
	</td></tr>
	</table>


	<br><br>
	<input type="submit" name="submit1" value="Change Address"/><br><br>

	<?php
		if ($check1 == 1) {
			echo "You have successfully changed your address<br>";
			echo "Please refresh the page to see changes<br>";
		}
	?>
	</div>
</body>

</html>