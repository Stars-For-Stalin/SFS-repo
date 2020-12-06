<?php
	$path = $_SERVER['DOCUMENT_ROOT'];
    include $path.'/include/header.php';
?>

<body>

	<div class='container'>
		<div class="row mt-4">
			<aside class="col-sm-4"></aside>

			<aside class="col-sm-4">
				<?php
				if ($_SESSION['loginMessage']  != null) {
					echo ('<div class="alert alert-danger" role="alert">');
					echo ($_SESSION['loginMessage']);
					echo ('</div>');

					unset($_SESSION['loginMessage']);
				}
				?>

				<div class="card">
					<article class="card-body">
						<button class="float-right btn btn-outline-primary" onclick='document.getElementById("login").reset();'>Reset</button>
						<h4 class="card-title mb-4 mt-1">Sign in</h4>

						<form id='login' data-bitwarden-watching="1" action="validateLogin.php" method="post">

							<div class="form-group">
								<label>Your Username</label>
								<input name="username" class="form-control" placeholder="Username" maxlength=10>
							</div>
							<div class="form-group">
								<label>Your password</label>
								<input name="password" class="form-control" placeholder="******" type="password">
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block"> Login </button>
							</div>
							<?php
							if (isset($_GET['redirect']))
								echo ('<input type="hidden" name="redirect" value="' . $_GET['redirect'] . '">');
							?>
						</form>
                        <div class="form-group">
                            <button onclick="window.location.href='register.php'" class="btn btn-primary btn-block"> Register </button>
                        </div>
                        <a href="forgot-password.php?">forgot password</a>
					</article>
				</div>
			</aside>

			<aside class="col-sm-4"></aside>

		</div>
	</div>
</body>
<?php include $path.'/include/footer.php'; ?>