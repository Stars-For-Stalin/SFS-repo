<?php
	$path = $_SERVER['DOCUMENT_ROOT'];
	include $path.'/include/auth.php';
	$user = $_SESSION['authenticatedUser'];

	$title = 'Your Account: Stars For Stalin';
	include $path.'/include/header.php';
?>

    <body>
    <div class="container">
        <h1>Your Info</h1><br><br><br>
        <div class="row">
			<?php
				// TODO: Print Customer information
				$con = try_connect();
				if ($con !== false) {
					$sql = "SELECT * FROM customer WHERE userid = ?;";
					$preparedStatement = sqlsrv_prepare($con, $sql, array(&$user));
					$result = sqlsrv_execute($preparedStatement);
					$tuple = sqlsrv_fetch_array($preparedStatement, SQLSRV_FETCH_ASSOC);
					$order_align = array("class"=>"text-left");
					$make_row_pair=function($title,$value,$align=null){
						$attr = array("scope"=>"col");
						$cells=array();
						array_push($cells,make_cell($title, $attr, 'th'));
						array_push($cells,make_cell($value, $align));
						return make_row($cells);
					};

					$submit = $_POST['submit'];
					$password = $_POST['password_entered'];

					$check = 0;

					if   ((!empty($password))) {
						$sql1 = "UPDATE customer SET password = ? WHERE userid = ?";
						$prepared_sql1 = sqlsrv_prepare($con, $sql1, array(&$password, &$user));
						$result_sql1 = sqlsrv_execute($prepared_sql1);
						$check = 1;
					}

					$submit1 = $_POST['submit1'];
					$address = $_POST['address_entered'];
					$city = $_POST['city_entered'];
					$state = $_POST['state_entered'];
					$postalCode = $_POST['postalCode_entered'];
					$country = $_POST['country_entered'];

					$check1 = 0;

					if   ((!empty($address))&&(!empty($city))&&(!empty($state))&&(!empty($postalCode))&&(!empty($country))) {
						$sql2 = "UPDATE customer SET address = ?, city = ?, state = ?, postalCode = ?, country = ? WHERE userid = ?";
						$prepared_sql2 = sqlsrv_prepare($con, $sql2, array(&$address, &$city, &$state, &$postalCode, &$country, &$user));
						$result_sql2 = sqlsrv_execute($prepared_sql2);
						$check1 = 1;
					}

					// Make sure to close connection
					disconnect($con);
				}
			?>
            <div class="col-6 align-leftside">
                <div class="card">
                    <article class="card-body">
                        <h4 class="card-title mb-4 mt-1">Change Address</h4>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label>Address: </label>
                                <input type="text" name="address_entered" class="form-control" value='<?php echo $address;?>'/><?php if (!$submit1) {if (empty($address)) { echo "* This field cannot be left empty";   } } ?>
                            </div>
                            <div class="form-group">
                                <label>City: </label>
                                <input type="text" name="city_entered" class="form-control" value='<?php echo $city;?>'/><?php if (!$submit1) {if (empty($city)) { echo "* This field cannot be left empty";   } } ?>
                            </div>
                            <div class="form-group">
                                <label>State: </label>
                                <input type="text" name="state_entered" class="form-control" value='<?php echo $state;?>'/><?php if (!$submit1) {if (empty($state)) { echo "* This field cannot be left empty";   } } ?>
                            </div>
                            <div class="form-group">
                                <label>Postal Code: </label>
                                <input type="text" name="postalCode_entered" class="form-control" value='<?php echo $postalCode;?>'/><?php if (!$submit1) {if (empty($postalCode)) { echo "* This field cannot be left empty";   } } ?>
                            </div>
                            <div class="form-group">
                                <label>Country: </label>
                                <input type="text" name="country_entered" class="form-control" value='<?php echo $country;?>'/><?php if (!$submit1) {if (empty($country)) { echo "* This field cannot be left empty";   } } ?>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit1" class="btn btn-primary btn-block"> Submit </button>
                            </div>
                        </form>
                    </article>
                </div>

                <?php
                    if ($check1 == 1) {
                        echo "You have successfully changed your address<br>";
                        echo "Please refresh the page to see changes<br>";
                    }
                ?>
                <br><br>
                <div class="card col-8">
                    <article class="card-body">
                        <h4 class="card-title mb-4 mt-1">Change Password</h4>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label><label>
                                        <input type="password" name="password_entered" class="form-control" value='<?php echo $password;?>'/><?php if (!$submit) {if (empty($password)) { echo "* This field cannot be left empty";   } } ?>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary btn-block"> Submit </button>
                            </div>
                        </form>
                    </article>
                </div>

				<?php
					if ($check == 1) {
						echo "You have successfully changed your password<br>";
						echo "Please re-login <a href='http://localhost/account/login.php'>here.</a>";
					}
				?>
            </div>
            <?php
				$attr_t1 = array("class"=>"table table-bordered");
				$rows = array();
				array_push($rows, $make_row_pair("Customer Id",$tuple['customerId'],$order_align));
				array_push($rows, $make_row_pair("First Name",$tuple['firstName'],$order_align));
				array_push($rows, $make_row_pair("Last Name",$tuple['lastName'],$order_align));
				array_push($rows, $make_row_pair("Address",$tuple['address'],$order_align));
				array_push($rows, $make_row_pair("City",$tuple['city'],$order_align));
				array_push($rows, $make_row_pair("State",$tuple['state'],$order_align));
				array_push($rows, $make_row_pair("Postal Code",$tuple['postalCode'],$order_align));
				array_push($rows, $make_row_pair("Country",$tuple['country'],$order_align));
				array_push($rows, $make_row_pair("User id",$user,$order_align));
				echo("<div class='col-6 align-rightside'>");
				echo(make_table($rows,$attr_t1));
				echo("</div>");
            ?>
        </div>
        <br><br>
    </div>
    </body>
<?php include $path.'/include/footer.php'; ?>