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

<body>
  <h1> Course Information </h1>
  <h3> Retrieve continous assessment scores for: </h3>
  <div class = "all_course"> </div> 
</body>

<?php
start();
function start(){
  $dbcon = mysqli_connect("mydb","dummy","c3322b","db3322") or die("Connection Error!" . mysqli_connet_error());
  $query = "
      SELECT DISTINCT C.course
      FROM user U, courseinfo C  
      WHERE U.uid = C.uid AND U.email = '" . $_SESSION["email"] ."'";
  $result = mysqli_query($dbcon, $query);
  $counter = 0;
  $all_course = array();
    if (mysqli_num_rows($result) >= 0) {
      while($row = mysqli_fetch_array($result)) {
        echo " <div> <a href=getscore.php?course=" . $row["course"] . ">" . $row['course'] . "</a> </div>";
          $all_course[] = $row["course"];
          $counter += 1;
      }
    } 
    else {
      echo "0 results";
    } 
    $counter = 0;
  }
?>