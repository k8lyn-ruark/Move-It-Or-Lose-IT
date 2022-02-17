<?php
//code taken from https://codeshack.io/secure-login-system-php-mysql/
session_start();
session_destroy();
// Redirect to the login page:
header('Location: login.html');
?>