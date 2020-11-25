<?php 
	session_start();      
	include 'include/functions.php';    
    $authenticatedUser = validateLogin();
    
    if ($authenticatedUser != null)
        header('Location: index.php');      		// Successful login
    else
        header('Location: login.php');	             // Failed login - redirect back to login page with a message     
	
	function validateLogin()
	{	  
		$con = try_connect();
		if ($con == false) {
			die("Unable to connect to DB");
		}

	    $user = $_POST["username"];	 
		$pw = $_POST["password"];
		$retStr = null;

		if ($user == null || $pw == null)
			return null;
		if ((strlen($user) == 0) || (strlen($pw) == 0))
			return null;

		include 'include/db_credentials.php';
		$con = sqlsrv_connect($server, $connectionInfo);
		
		// TODO: Check if userId and password match some customer account. If so, set retStr to be the username.
		$sql = "SELECT customerId, password FROM customer WHERE customerId = ?";
		$preparedStatement = sqlsrv_prepare($con, $sql, array(&$user));
		$result = sqlsrv_execute($preparedStatement);
		$retStr = null;

		if ($result || !empty($result)) {
			while ($row = sqlsrv_fetch_array($preparedStatement, SQLSRV_FETCH_ASSOC)) {
				if ($pw === $row['password'])
					$retStr = $row['customerId'];
			}
		}
		
		sqlsrv_free_stmt($preparedStatement);
		disconnect($con);
		
		if ($retStr != null)
		{	$_SESSION["loginMessage"] = null;
	       	$_SESSION["authenticatedUser"] = $user;
		}
		else
		    $_SESSION["loginMessage"] = "Could not connect to the system using that username/password.";


		return $retStr;
	}
