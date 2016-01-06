<?php require_once('LOCALHOST.php'); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Home</title>
</head>

<body>
<h1>Test Management System</h1>
<p>Created by Sly, Josh, and Jack.</p>

<p>To begin, 
<a href="login.php">Login</a>
</p>

<h1>Debug and test area</h1>
<?php
$result = mysql_query("SELECT * FROM USERS");
while($row = mysql_fetch_array($result))
  {
  echo $row['Username'] . " " . $row['Password'];
  echo "<br />";
  }

echo "<br /><br />";  
$InsID = "1";
if($InsID) {
	$ins_query = "SELECT username FROM INSTRUCTORS WHERE InsID=".$InsID;
	$instructor_result = mysql_query($ins_query) or die(mysql_error());
	$InsID_result = mysql_fetch_assoc($instructor_result);
	$foundIns = mysql_num_rows($instructor_result);
	if($foundIns){
		$username = $InsID_result["username"];
		echo $username;
	}
}

//$query = mysql_query("select max(WrongAnsID) from MC_WRONG_ANS where QuestionID = 1;") or die(mysql_error()); 
//$query_result = mysql_fetch_assoc($query);
//$next_index = $query_result['max(WrongAnsID)']+1;
//echo "<br />".$query_result['max(WrongAnsID)']."<br />";
//echo $next_index;

//$exists_query = "SELECT Username FROM USERS WHERE Username='sknight';";
//$exists_result = mysql_query($exists_query) or die(mysql_error());
//$username_exists = mysql_num_rows($exists_result);
//echo $username_exists;

$sortby = "Name";
$topics_query = "SELECT TopicID, Name, Count(*) FROM TOPICS, QUESTIONS 
				WHERE TopicID=QTopic GROUP BY TopicID, Name 
				ORDER BY ".$sortby.";";
$topics_result = mysql_query($topics_query) or die(mysql_error());
$topics_list = mysql_fetch_array($topics_result);
$topics_found = mysql_num_rows($topics_result);
echo $topics_list['TopicID']." ".$topics_list['Name']." ".$topics_list['Count(*)'];

$QuestionID = 41;
$next_query = "SELECT max(WrongAnsID) FROM MC_WRONG_ANS WHERE QuestionID='".$QuestionID."';";
$next_result = mysql_query($next_query) or die(mysql_error());
$next_array = mysql_fetch_array($next_result);
$next_wrong_id = $next_array['max(WrongAnsID)']+1;
echo "NextWrongID:".$next_wrong_id;
  
  
?>

</body>
</html>
<?php 
mysql_close($con);
mysql_free_result($result);
mysql_free_result($query);
?>