<?php

$title = 'Create User Account Page';
include 'include/header.php';

?>

<body>
	<div class='container'>

	<?php

		$submit= $_POST['submit'];
		$firstName= $_POST['firstname_entered'];
		$lastName= $_POST['lastname_entered'];
		$username= $_POST['username_entered'];
		$password= $_POST['password_entered'];

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

		if   ((!empty($firstName))&&(!empty($lastName))&&(!empty($username))&&(!empty($password))) {
			if ($count == 0) {
				$sql1 = "INSERT INTO customer (firstName, lastName, userid, password) VALUES (?, ?, ?, ?)";
				$prepared_sql1 = sqlsrv_prepare($con, $sql1, array(&$firstName, &$lastName, &$username, &$password));
				$result_sql1 = sqlsrv_execute($prepared_sql1);
				$check = 1;
			}
		}

		sqlsrv_close($con);

		if ($check == 1) {
			echo "You have been successfully registered<br>";
			echo "Please go to the <a href='http://localhost/login.php'>login</a> page to now log into your account";
		}

	?>

	<form action="" method="POST">

	<table>
	<tr><td>First name:</td><td>
	<input type="text" name="firstname_entered" value='<?php echo $firstName;?>'/><?php if ($submit) {if (empty($firstName)) { echo "* This field cannot be left empty";   } } ?>
	</td></tr>

	<tr><td>Last name: </td><td>
	<input type="text" name="lastname_entered" value='<?php echo $lastName;?>'/><?php if ($submit) {if (empty($lastName)) { echo "* This field cannot be left empty";   } } ?>
	</td></tr>

	<tr><td>Username: </td>

	<td>
	<input type="text" name="username_entered" value='<?php echo $username;?>'/><?php if ($submit) {if ($count != 0) { echo "* This username is already taken. Please enter a different username";   } } ?>
	<?php if ($submit) {if (empty($username)) { echo "* This field cannot be left empty";   } } ?>
	</td></tr>




	<tr><td>Password: </td>

	<td>
	<input type="password" name="password_entered" value='<?php echo $password;?>'/><?php if ($submit) {if (empty($password)) { echo "* This field cannot be left empty";   } } ?>
	</td></tr>
	</table>


	<br><br>
	<input type="submit" name="submit" value="Register"/><br><br>
