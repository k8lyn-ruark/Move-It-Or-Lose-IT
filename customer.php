<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection = mysqli_connect("avl.cs.unca.edu", "kruark", "Ark1915Kru");
$db = mysqli_select_db($connection, 'move_it_or_lose_itdb');

if($connection){
    echo '<script> alert("Connected to Database"); </script>';
}

try {
  $pdo = new PDO('mysql:host=avl.cs.unca.edu;dbname=move_it_or_lose_itdb', 'kruark', 'Ark1915Kru');
} catch (PDOException $e) {
  echo '<script> alert("Not Connected to Database"); </script>';
  die ($e->getMessage());
    
}

if(isset($_POST['insertData'])) {
    
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $streetAddress = $_POST['streetAddress'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    
    $fname_escape = mysqli_real_escape_string($connection, $_POST['fname']);
    $lname_escape = mysqli_real_escape_string($connection, $_POST['lname']);
    $phone_escape = mysqli_real_escape_string($connection, $_POST['phone']);
    $email_escape = mysqli_real_escape_string($connection, $_POST['email']);
    $streetAddress_escape = mysqli_real_escape_string($connection, $_POST['streetAddress']);
    $city_escape = mysqli_real_escape_string($connection, $_POST['city']);
    $state_escape = mysqli_real_escape_string($connection, $_POST['state']);
    $zip_escape = mysqli_real_escape_string($connection, $_POST['zip']);

$query = "INSERT INTO customers (first_name, last_name, phone, email, home_street, home_city, home_state, home_zip) VALUES ('$fname_escape', '$lname_escape', '$phone_escape', '$email_escape', '$streetAddress_escape', '$city_escape', '$state_escape', '$zip_escape');";
    
$query_run = mysqli_query($connection, $query);

if($query_run){
    echo '<script> alert("Data Saved"); </script>';
    header('Location: schedule.html'); 
} else {
    echo '<script> alert("Data Not Saved"); </script>';
}

}


?>