<?php
session_start();
$title = 'Grocery CheckOut Line';

if (isset($_SESSION['customerId']))
    header('Location: order.php');

include 'include/header.php';
?>


<body>

    <div class='container'>
        <div class="row">
            <aside class="col-sm-4"></aside>

            <aside class="col-sm-4">
                <div class="alert alert-info" role="alert">
                    Please login to complete the transaction:
                </div>

                <div class="card">
                    <article class="card-body">
                        <button class="float-right btn btn-outline-primary" onclick='document.getElementById("login").reset();'>Reset</button>
                        <h4 class="card-title mb-4 mt-1">Sign in</h4>

                        <form id='login' data-bitwarden-watching="1" action="order.php" method="get">

                            <div class="form-group">
                                <label>Your customer ID</label>
                                <input name="customerId" class="form-control" placeholder="123456789" type="number">
                            </div>
                            <div class="form-group">
                                <label>Your password</label>
                                <input name="password" class="form-control" placeholder="******" type="password">
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label> <input type="checkbox" name="save_password"> Save password </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block"> Login </button>
                            </div>
                        </form>
                    </article>
                </div>
            </aside>

            <aside class="col-sm-4"></aside>

        </div>
    </div>
</body>

</html>