<?php
//code taken from https://codeshack.io/secure-login-system-php-mysql/
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.html');
	exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Move It Or Lose It!</title>
        <link rel="stylesheet" href="css/main.css">
        <script src="https://kit.fontawesome.com/ab2155e76b.js" crossorigin="anonymous"></script>
    </head>
    <body>
        
        <h1>Move It Or Lose It!</h1>
         <nav role="navigation">
             <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="quote.html">Schedule A Job</a></li>
                <li><a href="contact.html">Contact</a></li>
                 <li><a href="login.html">Employees</a></li>
            </ul>
        </nav>
		<div class="content">
			<h2>Administrator Headquarters</h2>
			<p>Welcome back, <?=$_SESSION['name']?>!</p>
            <!--THE SHIFT PICK UP FEATURE GOES HERE!!!! -->
		</div>
        <div id="logout">
            <p><a class="index-btn" href=logout.php>Logout</a></p>
        </div>
	</body>
</html>