<?php
session_start();

include 'include/functions.php';
if (empty($_GET)) {
    echo ('Error: Invalid Perameters');
} else if (isset($_SESSION['customerId'])) {
    echo ('custid exsist');
    header('Location: order.php');
} else if (isset($_GET['customerId']) && isset($_GET['password'])) {
    $con =  try_connect();
    $custId = $_GET['customerId'];
    $sql = 'SELECT customerId, password FROM customer WHERE customerId = ?';
    $preparedStatement = sqlsrv_prepare($con, $sql, array(&$custId));
    $results = sqlsrv_execute($preparedStatement);
    if ($results != false) {

        while ($row = sqlsrv_fetch_array($preparedStatement, SQLSRV_FETCH_ASSOC)) {
            if ($row['customerId'] == $custId && $row['password'] === $_GET['password']) {
                $_SESSION['customerId'] = $custId;
                if (isset($_GET['save_password']))
                    $_SESSION['save_password'] = true;
                echo ('a ok');
                echo ('<script>window.location = "order.php";</script>');
            } else {
                echo ('Error: Wrong Password');
                unset($_SESSION['customerId']);
                unset($_SESSION['save_password']);
            }
        }
    } else {
        echo ('Error: Invalid Customer ID');
    }
} else {
    echo ('Error: Invalid Perameters');
}

disconnect($con);