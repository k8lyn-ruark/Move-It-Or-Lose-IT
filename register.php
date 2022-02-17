<?php
//code taken from https://codeshack.io/secure-registration-system-php-mysql/
// Connection info.
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

// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	// Could not get the data that should have been sent.
	exit('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	// One or more values are empty.
	exit('Please complete the registration form');
}

//email validation
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid!');
}

//invalid characters validation
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    exit('Username is not valid!');
}

if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	exit('Password must be between 5 and 20 characters long!');
}

// We need to check if the account with that username exists.
if ($stmt = $con->prepare('SELECT id, password FROM employee_accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Username already exists
		echo 'Username exists, please choose another!';
	} else {
		// Username doesnt exists, insert new account
        if ($stmt = $con->prepare('INSERT INTO employee_accounts (username, password, email, activation_code) VALUES (?, ?, ?, ?)')) {
	       // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
	       $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	       $uniqid = uniqid();
           $stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $uniqid);
	       $stmt->execute();
            //using kruark email ONLY while testing
	       $from    = 'kruark@unca.edu';
            $subject = 'Account Activation Required';
            $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
            // Update the activation variable below
            $activate_link = 'https://www.cs.unca.edu/~kruark/moveitorloseit_with_logins_and_calendar/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
            $message = '<p>Please click the following link to activate your account: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
            mail($_POST['email'], $subject, $message, $headers);
            echo 'Please check your email to activate your account!';
        } else {
            // Something is wrong with the sql statement, check to make sure employee_accounts table exists with all 3 fields.
	        echo 'Could not prepare statement!';
        }
	   }
	   $stmt->close();
    } else {
	   // Something is wrong with the sql statement, check to make sure employee_accounts table exists with all 3 fields.
	   echo 'Could not prepare statement!';
    }
    $con->close();
?>