<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
date_default_timezone_set("Asia/Hong_Kong");

function display_content_page() {
  header('location: courseinfo/index.php');
}

start();
function start() {
  if($_SERVER['REQUEST_METHOD'] === 'POST') {  // post = press the login button
    authenticate();
  } 
  else {  // is a get method
    if ($_GET["token"]){
      check_OTP();
    }
    elseif (isset($_SESSION["session_timestamp"]) && date("H:i:s") > $_SESSION["session_timestamp"]){
      display_login_form();
      ?> <script> document.getElementById("session_expire").style.display = 'block'; </script> <?php
      session_destroy();
    }
    elseif (isset($_SESSION["session_timestamp"]) && date("H:i:s") <= $_SESSION["session_timestamp"]){
      display_content_page();
    }
    else{
      display_login_form();
    }
  }
}

function display_login_form() {
  ?>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="\styles\login.css">
  </head>
  <h1> Gradebook Accessing Page</h1>
  <p id="test"> </p>
  <form action="" method="post">
    <fieldset name="logininfo">
      <legend>My Gradebooks</legend>
      <br>
      <label for="email">Email:</label> 
      <!-- source: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/email#pattern_validation -->
      <input type="text" name="email" id="email" pattern = ".+@connect\.hku\.hk|.+@cs\.hku\.hk" title="Must be an email address with @cs.hku.hk or @connect.hku.hk" > <br>
      <br>
      <button type="submit" id="login_button" name='login'>Login</button>
    </fieldset>
</form>
  <br>
  <p id="error_unknown_user"> Unknown user - we don't know the records for <?php echo $_POST["email"] ?> in the system. </p>
  <p id="check_your_email"> Please check your email for the authentication URL. </p>
  <p id="OTP_expired"> Fail to authenticate - OTP expired!  </p>
  <p id="wrong_secret"> Fail to authenticate - incorrect secret!  </p>
  <p id="user_not_in_database"> Unknown user - cannot identify the student. </p>
  <p id="session_expire">  Session expired. Please login again. </p>
  <?php 
}
 
function authenticate(){
    $dbcon = mysqli_connect("mydb","dummy","c3322b","db3322") or die("Connection Error!" . mysqli_connet_error());
    $query = "
      SELECT email
      FROM user  
      WHERE email = '" . $_POST['email'] . "'" ;
    $result = mysqli_query($dbcon, $query);
    if (mysqli_num_rows($result) == 0){
      display_login_form();
      ?> <script> document.getElementById('error_unknown_user').style.display = "block"; </script> <?php
    }
    else {
      display_login_form();
      ?> <script> document.getElementById('check_your_email').style.display = 'block'; </script> <?php
      $_SESSION["email"] = $_POST['email'];
      send_email();
    }
  }

function send_email(){
  $dbcon = mysqli_connect("mydb","dummy","c3322b","db3322") or die("Connection Error!" . mysqli_connet_error());
  $query = "
      SELECT uid
      FROM user   
      WHERE email = '" . $_SESSION["email"] . "'";
  $result = mysqli_query($dbcon, $query);
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
      $uid = $row["uid"];
    }  
  }
  $secret = bin2hex(random_bytes(8));
  $token_array = array("uid" => "$uid", "secret" => "$secret");
  $token = 'http://localhost:9080/login.php?token=' . bin2hex(json_encode($token_array));
  $_SESSION["OTP_timestamp"] = date("H:i:s", strtotime("+1 minutes"));
  $query = " UPDATE user SET secret='" . $secret . "', timestamp = '" . strtotime($_SESSION["OTP_timestamp"]) . "' WHERE email='" . $_SESSION["email"] . "'; ";
  $result = mysqli_query($dbcon, $query);

  $mail = new PHPMailer(true);
  if (isset($_POST['email'])) {
    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output, it will output the progress of sending to my page
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'testmail.cs.hku.hk';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = false;                                   //Enable SMTP authentication
        $mail->Port       = 25;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Sender
        $mail->setFrom('c3322@cs.hku.hk', 'COMP3322');
        //******** Add a recipient to receive your email *************
        $mail->addAddress($_POST['email']);     
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Send by PHPMailer';
        $mail->Body    = "Dear Student, You can log on to the system via the following link:" . "\n" . "<a href=" . "$token" . ">" . "$token" . "</a>" ;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  } else {
    echo "Please specify the recipent's email and name.";
  } 
}
 
function check_OTP(){
  $token_from_URL = $_GET['token'];
  $token_from_URL = json_decode(hex2bin($token_from_URL), true);
  $uid_from_URL =  $token_from_URL["uid"];
  $secret_from_URL =  $token_from_URL["secret"];

  $dbcon = mysqli_connect("mydb","dummy","c3322b","db3322") or die("Connection Error!" . mysqli_connet_error());
  $query = "
    SELECT email, timestamp
    FROM user   
    WHERE uid = '" . $uid_from_URL . "'";
  $result = mysqli_query($dbcon, $query);
  if (mysqli_num_rows($result) > 0 && !isset($_SESSION["OTP_timestamp"])) {
    while($row = mysqli_fetch_array($result)) {
      $_SESSION["OTP_timestamp"] = $row["timestamp"];
      $_SESSION["email"] = $row["email"];
    } 
    
  }

  $dbcon = mysqli_connect("mydb","dummy","c3322b","db3322") or die("Connection Error!" . mysqli_connet_error());
  $query = "
    SELECT secret
    FROM user   
    WHERE uid = '" . $uid_from_URL . "'";
  $result = mysqli_query($dbcon, $query);

  // have record
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
      $saved_secret = $row["secret"];
    } 

    if ($saved_secret == $secret_from_URL){
      if (date("H:i:s") > $_SESSION["OTP_timestamp"]){ 
        
        $dbcon = mysqli_connect("mydb","dummy","c3322b","db3322") or die("Connection Error!" . mysqli_connet_error());
        $query = " UPDATE user SET secret=NULL, timestamp =NULL WHERE email='" . $_SESSION["email"] . "'; ";
        $result = mysqli_query($dbcon, $query); 
        display_login_form();
        ?> <script> document.getElementById("OTP_expired").style.display = "block"; </script> <?php
      }
      else{
        $dbcon = mysqli_connect("mydb","dummy","c3322b","db3322") or die("Connection Error!" . mysqli_connet_error());
        $query = " UPDATE user SET secret=NULL, timestamp =NULL WHERE email='" . $_SESSION["email"] . "'; ";
        $result = mysqli_query($dbcon, $query);
        $_SESSION["session_timestamp"] = date("H:i:s", strtotime("+5 minutes"));
        display_content_page();
      }
    }
    else{
      $dbcon = mysqli_connect("mydb","dummy","c3322b","db3322") or die("Connection Error!" . mysqli_connet_error());
      $query = " UPDATE user SET secret=NULL, timestamp =NULL WHERE email='" . $_SESSION["email"] . "'; ";
      $result = mysqli_query($dbcon, $query);
      display_login_form();
      ?> <script> document.getElementById('wrong_secret').style.display = 'block'; </script> <?php
    }
  }
  // no record
  else {
    display_login_form();
    ?> <script> document.getElementById('user_not_in_database').style.display = 'block'; </script> <?php
  }
}
?>
<!-- end -->