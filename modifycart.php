<?php
session_start();
header('Location: showcart.php');
$productList = $_SESSION['productList'];

if (!empty($_GET)) {
    foreach ($_GET as $key => $value) {
        $key = intval(str_replace('prod_', '', $key));
        if (isset($productList[$key])) {
            echo ($value);

            if (empty($value) || $value < 0) {
                unset($productList[$key]);
            } else {
                $productList[$key]['quantity'] = $value;
            }
        }
    }

    if (empty($productList)) {
        unset($_SESSION['productList']);
    } else {
        $_SESSION['productList'] = $productList;
    }
}
