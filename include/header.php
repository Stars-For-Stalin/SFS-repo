<?php if (!isset($_SESSION)) session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<?php include 'functions.php'; ?>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo($root . "include/just-a-little-style.css"); ?>">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
	<script type="text/javascript" src="include/functions.js"></script>
	<title>
		<?php
			if (isset($title)) {
				echo ($title);
			} else {
				echo ("Stars For Stalin");
			}
		?>
	</title>
</head>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
	<?php
		echo (make_link($root . "index.php", "Stars For Stalin!", array("class" => "navbar-brand")));

		echo ('<div class="navbar-nav mr-auto">');
		echo (make_link($root . "listprod.php", "Browse Stars", array("class" => "nav-item nav-link")));
		if ($_SESSION['authenticatedUser'] == "admin"){
			echo (make_link($root . "listorder.php", "All Orders", array("class" => "nav-item nav-link")));
        } else {
			echo (make_link($root . "listorder.php", "Your Orders", array("class" => "nav-item nav-link")));
		}
		echo (make_link($root . "showcart.php", "View Cart", array("class" => "nav-item nav-link")));
		echo ('</div>');

		echo ('<div class="navbar-nav">');
		if (isset($_SESSION['authenticatedUser'])) {
			echo ('<span class="navbar-text">Hello ' .  $_SESSION['authenticatedUser'] . "!</span>");
			echo (make_link($root . "account/logout.php", "Logout", array("class" => "nav-item nav-link")));
		} else {
			echo (make_link($root . "account/login.php", "Login", array("class" => "nav-item nav-link")));
		}

		echo ('</div>');
	?>
</nav>