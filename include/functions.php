<?php
    function try_connect(){
        include 'db_credentials.php';
        /** Create connection, and validate that it connected successfully **/
        $con = sqlsrv_connect($server, $connectionInfo);
        if ($con === false) {
            print_r(sqlsrv_errors(), true);
        }
        return $con;
    }
    function disconnect($con){
        sqlsrv_close($con);
    }
    function print_product($prodtuple){
        
    }
?>