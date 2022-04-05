
<?php

//code taken from http://www.learningaboutelectronics.com/Articles/How-to-create-a-send-email-form-using-PHP-and-MySQL.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Block 1
$user = "kruark"; 
$password = "Ark1915Kru"; 
$host = "avl.cs.unca.edu"; 
$dbase = "move_it_or_lose_itdb"; 
$table = "customers"; 

//Block 2
$from= 'kruark@unca.edu';

//Block 3
$subject= $_POST['email-subject'];
$body= $_POST['email-message'];

//Block 4
$dbc= mysqli_connect($host,$user,$password, $dbase) 
or die("Unable to select database");

//Block 5
$query= "SELECT * FROM $table";
$result= mysqli_query ($dbc, $query) 
or die ('Error querying database.');

//Block 6
while ($row = mysqli_fetch_array($result)) {
$first_name= $row['first_name'];
$last_name= $row['last_name'];
$email= $row['email'];

//Block 7
$msg= "Dear $first_name $last_name,\n\n$body";
mail($email, $subject, $msg, 'From:' . $from);
echo 'Email sent to: ' . $email. '<br>';
}

//Block 8
mysqli_close($dbc);

//redirect
header("Location:administrator.php");
?>
