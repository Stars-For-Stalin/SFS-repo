<script type="text/javascript" src="include/functions.js"></script>
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
    function debug($msg){
        if(false){
            echo($msg);
        }
    }
    function print_product($prodtuple){
        echo('<br/>');
        echo($prodtuple['productName']);
        echo(" ");
        echo($prodtuple['productPrice']);
        $picURL = $prodtuple['productImageURL'];
        if($picURL != false){
            echo($picURL);
        }
    }
?>