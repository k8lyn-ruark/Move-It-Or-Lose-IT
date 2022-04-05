<!DOCTYPE html>
<!-- code taken from https://startutorial.com/view/how-to-build-a-php-booking-calendar-with-mysql -->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Move It Or Lose It!</title>
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/calendar.css">
        <script src="https://kit.fontawesome.com/ab2155e76b.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header>
            <img src="images/Color%20logo%20-%20no%20background_400.png">
        </header>
         <nav role="navigation">
             <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="quote.html">Schedule A Job</a></li>
                <li><a href="contact.html">Contact</a></li>
                 <li><a href="login.html">Employees</a></li>
            </ul>
        </nav>
        <?php
            //code taken from https://codeshack.io/secure-registration-system-php-mysql/
            // Connection info
            $DATABASE_HOST = 'avl.cs.unca.edu';
            $DATABASE_USER = 'kruark';
            $DATABASE_PASS = 'Ark1915Kru';
            $DATABASE_NAME = 'move_it_or_lose_itdb';
            // Try and connect using the info above.
            $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
            if (mysqli_connect_errno()) {
                // If there is an error with the connection, stop the script and display the error.
                exit('Failed to connect to MySQL: ' . mysqli_connect_error());
            }
            // First we check if the email and code exists...
            if (isset($_GET['email'], $_GET['code'])) {
                if ($stmt = $con->prepare('SELECT * FROM employee_accounts WHERE email = ? AND activation_code = ?')) {
                    $stmt->bind_param('ss', $_GET['email'], $_GET['code']);
                    $stmt->execute();
                    // Store the result so we can check if the account exists in the database.
                    $stmt->store_result();
                    if ($stmt->num_rows > 0) {
                        // Account exists with the requested email and code.
                        if ($stmt = $con->prepare('UPDATE employee_accounts SET activation_code = ? WHERE email = ? AND activation_code = ?')) {
                            // Set the new activation code to 'activated', this is how we can check if the user has activated their account.
                            $newcode = 'activated';
                            $stmt->bind_param('sss', $newcode, $_GET['email'], $_GET['code']);
                            $stmt->execute();
                            echo '<p style="text-align: center;">Your account is now activated! You can now <a href="login.html">login</a>.</p>!';
                        }
                    } else {
                        echo 'The account is already activated or doesn\'t exist!';
                    }
                }
            }
        ?>

   </body>
</html>