<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php
        include 'functions.php';
    ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <title>
        <?php
            if (isset($title)) {
                echo($title);
            } else {
                echo("Stars For Stalin");
            }
        ?>
    </title>
</head>
<?php
	/** @var $root url-root, from functions.php which should have been included into the page THIS page is included in already. */
	$classtype = "navbar-brand";
	$plink = make_link($root."listprod.php","Stars", $classtype);
	$olink = make_link($root."listorder.php","Your Orders", $classtype);
	$clink = make_link($root."showcart.php","My Cart", $classtype);
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<?php
		echo($plink);
		echo($olink);
		echo($clink);
	?>
</nav>
