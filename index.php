<?php
	$title = "Stars For Stalin - филиал СССР";
	include 'include/header.php';
?>


<body>
    <h1 class="text-center"><?php echo($title); ?></h1>
    <br><br>
    <h2 class="text-center"><a href="login.php">Login</a></h2>
    <h2 class="text-center"><a href="logout.php">Log out</a></h2>
    <h2 class="text-center"><a href="useraccount.php">Register</a></h2>
    <br>
    <h2 class="text-center"><a href="customer.php">My Info</a></h2>
    <h2 class="text-center"><a href="listorder.php">Order History</a></h2>
    <h2 class="text-center"><a href="listprod.php">Shop for Stars</a></h2>
<?php
    // TODO: Display user name that is logged in (or nothing if not logged in)	
?>
</body>
<?php include 'include/footer.php'; ?>


