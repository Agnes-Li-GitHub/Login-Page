<?php
#This is a demo program using testmail.cs.hku.hk to send email
#Prerequisite: Must connect to HKUVPN before sending email

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

if (isset($_GET['to'], $_GET['name'])) {

  try {
      //Server settings
      $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = 'testmail.cs.hku.hk';                     //Set the SMTP server to send through
      $mail->SMTPAuth   = false;                                   //Enable SMTP authentication
  
      $mail->Port       = 25;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  
      //Sender
      $mail->setFrom('c3322@cs.hku.hk', 'COMP3322');
      //******** Add a recipient to receive your email *************
      $mail->addAddress($_GET['to'], $_GET['name']);     
  
      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = 'Send by PHPMailer';
      $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
      $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
  
      $mail->send();
      echo 'Message has been sent';
  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
  
} else {
  echo "Please specify the recipent's email and name.";
}