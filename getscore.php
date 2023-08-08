<?php
session_start();
date_default_timezone_set("Asia/Hong_Kong");
if (isset($_SESSION["session_timestamp"]) && date("H:i:s") > $_SESSION["session_timestamp"]){
  header("location:../login.php");   
}
elseif(! isset($_SESSION["session_timestamp"])){
  header("location:../login.php");
}
?> 

<head>
    <link rel="stylesheet" href="../styles/getscore.css">
</head>
<body>
  <h1> <?php echo $_GET["course"] ?> - Gradebook</h1>
  <p id="p_center"> Assessment scores: </p> 
  <table id="have_record">
      <tr>
          <th>Item</th>
          <th>Score</th>
      </tr>
<p id="no_record"> You do not have the gradebook for the course: <?php echo $_GET["course"] ?> in the system.</p> 
</body>

<?php
$total_score;
start();
function start(){
  $dbcon = mysqli_connect("mydb","dummy","c3322b","db3322") or die("Connection Error!" . mysqli_connet_error());
  $query = "
  SELECT assign, score
  FROM user U, courseinfo C 
  WHERE U.uid = C.uid AND U.email = '" . $_SESSION["email"] . "' AND C.course = '" . $_GET["course"]  . "'";
  $result = mysqli_query($dbcon, $query);
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      echo "
        <tr>
          <td>" . $row["assign"] . "</td>
          <td>" . $row["score"] . "</td> 
        </tr>";
      $total_score += $row["score"];
    }
      echo "
        <tr>
          <td> </td>
          <td>". "Total  " . $total_score . "</td> 
        </tr>";
  } 
  else {
    ?> <script>
      document.getElementById('have_record').style.display = 'none';
      document.getElementById('p_center').style.display = 'none';
      document.getElementById('no_record').style.display = 'block';
    </script> <?php   
  }
  mysqli_close($dbcon);
?> 
  </table>
<?php  
  }
?>