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

            // Make sure to close connection
            disconnect($con);
        }
        ?>
    </div>
</body>

</html>