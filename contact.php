<?php

//code taken from https://codeconia.com/2021/01/12/contact-form-with-phpmail-for-your-website/

//get data from form  
$name = $_POST['contact-name'];
$email= $_POST['contact-email'];
$phone = $_POST['contact-phone'];
$message= $_POST['message'];
$to = "kruark@unca.edu";
$subject = "Move It Or Lose It Webmail";
$txt =" Name = ". $name . "\r\n Email = " . $email . "\r\n Phone=" .$phone. "\r\n Message = \r\n" . $message;
$headers = "From: " . $email . "\r\n" .
"CC: ";
if($email!=NULL){
    mail($to,$subject,$txt,$headers);
}
//redirect
header("Location:contact.html");
?>
