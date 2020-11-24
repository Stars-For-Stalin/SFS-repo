<?php
header("Content-Type: image/jpeg");

$id = $_GET['id'];
   
if ($id == null)
    return;

include 'include/db_credentials.php';

// TODO: Modify SQL to retrieve productImage given productId
$sql = "";

$con = sqlsrv_connect($server, $connectionInfo);
$pstmt = sqlsrv_query($con, $sql, array($id));

if ($rst = sqlsrv_fetch_array( $pstmt, SQLSRV_FETCH_ASSOC)) 
{
    echo $rst['productImage'];
}
                    
sqlsrv_free_stmt($pstmt);
sqlsrv_close($con);
?>
