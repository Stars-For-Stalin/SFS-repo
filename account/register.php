<?php
	$title = 'Create User Account Page';
	$path = $_SERVER['DOCUMENT_ROOT'];
	include $path.'/include/header.php';
?>

<body>
	<div class='container'>
		<div class="row mt-4">
			<aside class="col-sm-4"></aside>
			<?php

				$submit = $_POST['submit'];
				$firstName = $_POST['firstname_entered'];
				$lastName = $_POST['lastname_entered'];
				$email = $_POST['email_entered'];
				$phone = $_POST['phone_entered'];
				$address = $_POST['address_entered'];
				$city = $_POST['city_entered'];
				$state = $_POST['state_entered'];
				$postalCode = $_POST['postalCode_entered'];
				$country = $_POST['country_entered'];
				$username = $_POST['username_entered'];
				$password = $_POST['password_entered'];

				$check = 0;
				$count = 0;

				$con = try_connect();
				if (!$con) {
					die('Error connecting to DB');
				}

				$sql = "SELECT * FROM customer WHERE userid = ?";
				$prepared_sql = sqlsrv_prepare($con, $sql, array(&$username));
				$result_sql = sqlsrv_execute($prepared_sql);
				if(!$result_sql) {
					die('Error connecting to DB');
				}

				if ($result_sql || !empty($result_sql)) {
					while ($row = sqlsrv_fetch_array($prepared_sql, SQLSRV_FETCH_ASSOC)) {
						$count = 1;
					}
				}

				if   ((!empty($firstName))&&(!empty($lastName))&&(!empty($email))&&(!empty($phone))&&(!empty($address))&&(!empty($city))&&(!empty($state))&&(!empty($postalCode))&&(!empty($country))&&(!empty($username))&&(!empty($password))) {
					if ($count == 0) {
						$sql1 = "INSERT INTO customer (firstName, lastName, email, phonenum, address, city, state, postalCode, country, userid, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
						$prepared_sql1 = sqlsrv_prepare($con, $sql1, array(&$firstName, &$lastName, &$email, &$phone, &$address, &$city, &$state, &$postalCode, &$country, &$username, &$password));
						$result_sql1 = sqlsrv_execute($prepared_sql1);
						$check = 1;
					}
				}

				disconnect($con);
                if ($check == 1) {
                    echo('<div class="container"><br>');
                    echo "<h5 class='text-center'>You have been successfully registered</h5><br>";
                    $msg = "Hello $firstName, your account $username has been created.";
                    send_email($email,"Welcome to Stars for Stalin",$msg);
                    echo "<h5 class='text-center'>Please go to the <a href='http://localhost/login.php'>login</a> page to now log into your account</h5>";
                    echo("</div>");
                } else {
            ?>
			    <div class="card">
				<article class="card-body">
				<h4 class="card-title mb-4 mt-1">Register</h4>
					<form action="" method="POST">
						<div class="form-group">
							<label>First name: </label>
								<input type="text" name="firstname_entered" class="form-control" value='<?php echo $firstName;?>'/><?php if ($submit) {if (empty($firstName)) { echo "* This field cannot be left empty";   } } ?>
						</div>
						<div class="form-group">
							<label>Last name: </label>
								<input type="text" name="lastname_entered" class="form-control" value='<?php echo $lastName;?>'/><?php if ($submit) {if (empty($lastName)) { echo "* This field cannot be left empty";   } } ?>
						</div>
						<div class="form-group">
							<label>Email: </label>
								<input type="text" name="email_entered" class="form-control" value='<?php echo $email;?>'/><?php if ($submit) {if (empty($email)) { echo "* This field cannot be left empty";   } } ?>
						</div>
						<div class="form-group">
							<label>Phone Number: </label>
								<input type="text" name="phone_entered" class="form-control" value='<?php echo $phone;?>'/><?php if ($submit) {if (empty($phone)) { echo "* This field cannot be left empty";   } } ?>
						</div>
						<div class="form-group">
							<label>Address: </label>
								<input type="text" name="address_entered" class="form-control" value='<?php echo $address;?>'/><?php if ($submit) {if (empty($address)) { echo "* This field cannot be left empty";   } } ?>
						</div>
						<div class="form-group">
							<label>City: </label>
								<input type="text" name="city_entered" class="form-control" value='<?php echo $city;?>'/><?php if ($submit) {if (empty($city)) { echo "* This field cannot be left empty";   } } ?>
						</div>
						<div class="form-group">
							<label>State: </label>
								<input type="text" name="state_entered" class="form-control" value='<?php echo $state;?>'/><?php if ($submit) {if (empty($state)) { echo "* This field cannot be left empty";   } } ?>
						</div>
						<div class="form-group">
							<label>Postal Code: </label>
								<input type="text" name="postalCode_entered" class="form-control" value='<?php echo $postalCode;?>'/><?php if ($submit) {if (empty($postalCode)) { echo "* This field cannot be left empty";   } } ?>
						</div>
						<div class="form-group">
							<label>Country: </label>
								<input type="text" name="country_entered" class="form-control" value='<?php echo $country;?>'/><?php if ($submit) {if (empty($country)) { echo "* This field cannot be left empty";   } } ?>
						</div>
						<div class="form-group">
							<label>Username: </label>
								<input type="text" name="username_entered" class="form-control" value='<?php echo $username;?>'/><?php if ($submit) {if ($count != 0) { echo "* This username is already taken. Please enter a different username";   } } ?>
								<?php if ($submit) {if (empty($username)) { echo "* This field cannot be left empty";   } } ?>
						</div>
						<div class="form-group">
							<label>Password: <label>
								<input type="password" name="password_entered" class="form-control" value='<?php echo $password;?>'/><?php if ($submit) {if (empty($password)) { echo "* This field cannot be left empty";   } } ?>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block"> Register </button>
						</div>
					</form>
				</article>
			</div>
            <?php }?>
		</div>
	</div>
</body>
<?php include $path.'/include/footer.php'; ?>