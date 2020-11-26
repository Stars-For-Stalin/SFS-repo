<?php 
    include 'auth.php';	
	$user = $_SESSION['authenticatedUser'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Customer Page</title>
</head>
<body>

<?php     
    include 'include/header.php';
    include 'include/db_credentials.php';
?>

<?php
// TODO: Print Customer information
include('auth.php');
    $con = try_connect();
        if ($con !== false) {
            $sql = "SELECT * FROM customer WHERE userid = ?;";
			$preparedStatement = sqlsrv_prepare($con, $sql, array(&$user));
            $result = sqlsrv_execute($preparedStatement);
            $row = sqlsrv_fetch_array($preparedStatement, SQLSRV_FETCH_ASSOC);
            echo "<head><style>table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                } th, td {
                padding: 5px;
                text-align: left;
                }</style></head>
                <body>
                <table border=\"1\">
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
                </table>
                </body>
                </html>";
}


// Make sure to close connection
?>
</body>
</html>