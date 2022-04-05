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
        
        <header>
            <img src="images/Color%20logo%20-%20no%20background_400.png" alt="Company logo in black with words 'Move It Or Lose It!' beside a circle with a racoon in the center.">
        </header>
         <nav role="navigation">
             <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="quote.html">Schedule A Job</a></li>
                <li><a href="contact.html">Contact</a></li>
                 <li><a href="login.html">Employees</a></li>
            </ul>
        </nav>
        <div id="login-content"> 
            <div class="content">
                <h2>Administrator Headquarters</h2>
                <h4>Welcome back, <?=$_SESSION['name']?>!</h4>
            </div>
             <div id="email-form">
                <p>Send Promotional Email Through the Form: </p>

                <form action="promotional.php" method="POST" class="form">
                    <label for="email-subject">Email Subject: </label>
                        <input type="text" name="email-subject" id="email-subject" required><br>
                    <br><label for="email-message">Email Message: </label>
                    <textarea name="email-message" id="email-message" rows="15" cols="40" placeholder="Your message here."></textarea><br>
                    <button type="submit" onclick="sentEmails();">Send</button>
                </form>
            </div>
            <div id="logout">
                <br><a class="index-btn" href=logout.php>Logout</a>
            </div>
        </div>
        <script src="js/thankyou.js"></script>
	</body>
</html>