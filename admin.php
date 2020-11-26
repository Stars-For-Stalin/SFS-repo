<?php
include('auth.php');
$title = 'Administrator Page';
include('include/header.php');
?>

<body>
    <div class='container'>
        

        <?php
        $con = try_connect();

        if ($con == false) {
            die('Error connecting to DB');
        }

        $sql = 'SELECT CAST(orderDate AS DATE) as orderDate, SUM(totalAmount) as totalAmount FROM ordersummary GROUP BY CAST(orderDate AS DATE)';
        $results = sqlsrv_query($con, $sql, array());
        $tbrows = array(make_tableheader(array('Order Date', 'Total Order Amount')));
        if ($results != false) {
            while ($row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC)) {
                $tbrow = make_row(array(make_cell(date_format($row['orderDate'], 'Y-m-d')), make_cell("$" . number_format($row['totalAmount'], 2))));
                array_push($tbrows, $tbrow);
            }
        }

        echo('<h2>Administrator Sales Report by Day</h2>');
        echo (make_table($tbrows));

        disconnect($con);
        ?>
    </div>
</body>

</html>