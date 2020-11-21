<script type="text/javascript" src="include/functions.js"></script>
<?php
    $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
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
        echo('<a href="' . get_addcart_url($prodtuple) . '">Add To Cart</a>');
        echo(" ");
        echo($prodtuple['productName']);
        echo(" ");
        echo($prodtuple['productPrice']);
        $picURL = $prodtuple['productImageURL'];
        if($picURL != false){
            echo($picURL);
        }
    }
    function get_addcart_url($prodtuple){
        //id=<>name=<>&price=<>
        $url = $root . "addcart.php?id=" . $prodtuple['productId'] . "&name=" . $prodtuple['productName'] . "&price=" . $prodtuple['productPrice'];
        return $url;
    }
?>