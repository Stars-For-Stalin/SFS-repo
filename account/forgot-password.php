<?php
	$path = $_SERVER['DOCUMENT_ROOT'];
	include $path.'/include/header.php';

	$user = $_POST["username"];
	$con = try_connect();
	if ($con !== false) {
		$sql = "select email,password from customer where userid = ?;";
		$ps = sqlsrv_prepare($con, $sql, array(&$user));
		if (sqlsrv_execute($ps)){
			if ($customer = sqlsrv_fetch_array($ps, SQLSRV_FETCH_ASSOC)){
				$email = $customer['email'];
				$pw = $customer['password'];
				$msg = "Hello $user,\nYour password is: $pw";
				send_email($email,"Forgotten password",$msg);
				$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
				$loginurl = $root."account/login.php";
				echo("<br><br><h3 class='text-center'>Sent password to user's email.</h3><br>");
			} else {
				echo("<h5 class='text-center'>User not found in database.</h5><br>");
			}
			echo("<h5 class='text-center'>Redirecting to login in 3 seconds..</h5><br>");
			addjs("setTimeout(function(){window.location.href=\"$loginurl\";},3000);");
		} else {
			oops();
		}
	}
	include $path.'/include/footer.php';