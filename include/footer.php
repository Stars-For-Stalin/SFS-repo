<footer class="fixed-bottom">
    <nav class="footer navbar-light bg-light">
        <div class="navbar-nav mr-auto">
            <?php
                if($_SESSION['authenticatedUser'] == "admin") {
					echo('<h6 class="text-left"><a class="nav-item nav-link" href="account/admin.php">Admin Panel</a></h6>');
				}
            ?>
        </div>
    </nav>
    <h6 class="text-center align-vcenter">Stars for Stalin is a paid subsidiary of the <a href="https://umbrellacorporation.com/">Umbrella Corp</a></h6>
</footer>
</html>