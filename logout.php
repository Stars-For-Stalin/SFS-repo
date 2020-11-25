<?php
// Remove the user from the session to log them out	
session_start();
unset($_SESSION['authenticatedUser']);
unset($_SESSION['save_password']);
$_SESSION['loginMessage'] = "You've succesfully logged out!";

header('Location: login.php');
