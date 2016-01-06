<?php require_once('LOCALHOST.php'); ?>
<?php 
if (($_GET["InsID"] != "")){
	$InsID = $_GET["InsID"];
}
else {
	header("Location: login.php");
}

if (($_GET["TopicID"] != "")){
	$TopicID = $_GET["TopicID"];
}

if (($_GET["QuestionID"] != "")){
	$QuestionID = $_GET["QuestionID"];
}

if (($_GET["sortby"] != "")){
	$sortby = $_GET["sortby"];
} else { $sortby = "QuestionID";}

$sortby_QuestionID = "questions.php?InsID=".$InsID."&sortby=QuestionID&TopicID=".$TopicID;
$sortby_Instructor = "questions.php?InsID=".$InsID."&sortby=lname, fname&TopicID=".$TopicID;


if (isset($TopicID)) {
	$questions_query = "SELECT QuestionID, QText, fname, lname FROM QUESTIONS JOIN INSTRUCTORS ON InsID=CreatedBy WHERE QTopic='".$TopicID."' ORDER BY ".$sortby.";";
	$questions_result = mysql_query($questions_query) or die(mysql_error());
	$questions_found = mysql_num_rows($questions_result);
} else {
	$questions_query = "SELECT QuestionID, QText, fname, lname FROM QUESTIONS JOIN INSTRUCTORS ON InsID=CreatedBy ORDER BY ".$sortby.";";
	$questions_result = mysql_query($questions_query) or die(mysql_error());
	$questions_found = mysql_num_rows($questions_result);
}


$update_action_string = "update_question.php?InsID=".$InsID."&TopicID=".$TopicID;
$add_mc_action_string = "add_mc_question.php?InsID=".$InsID."&TopicID=".$TopicID;
$add_tf_action_string = "add_tf_question.php?InsID=".$InsID."&TopicID=".$TopicID;
$remove_action_string = "remove_question.php?InsID=".$InsID."&TopicID=".$TopicID;

$questions_link = "questions.php?InsID=".$InsID."&TopicID=";

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Questions</title>
</head>

<body>

<h1>Test Management System - Questions</h1>
<?php if (($_GET['error'] != "")) {echo $_GET['error'];} ?>


	<h2>Question List</h2>
	<?php
	if (isset($TopicID)) {
		$topic_query = "SELECT name FROM TOPICS WHERE TopicID='".$TopicID."';";
		$topic_result = mysql_query($topic_query) or die(mysql_error());
		$topic_info_array = mysql_fetch_array($topic_result);
		$topic_found = mysql_num_rows($topic_result);
		
		if ($topic_found) {echo "<p>Showing questions for Topic: ".$topic_info_array['name']."</p>";}
	}
	?>	
	<p>Helpful links: <a href="<?php echo $questions_link?>">View All Questions</a> - 
					<a href="topics.php?InsID=<?php echo $InsID; ?>">Select a Topic</a>
	</p>
	
	<?php 
	if($questions_found) { 
	?>
		<table border="0" cellspacing="2">
		  <tr>
			<th scope="col"><a href="<?php echo $sortby_QuestionID;?>">QuestionID</a></th>
			<th scope="col">Question Text</th>
			<th scope="col"><a href="<?php echo $sortby_Instructor;?>">Instructor</a></th>
		  </tr>
		  <?php
		  
		  while($row = mysql_fetch_array($questions_result))
			  { ?>
			  <tr align='center'>
			  <td><?php echo $row['QuestionID'];?></td>
			  <td><?php echo $row['QText'];?></td>
			  <td><?php echo $row['fname']." ".$row['lname'];?></td>
			  <td><a href="<?php echo $update_action_string;?>&QuestionID=<?php echo $row['QuestionID'];?>">Edit</a></td>
			  <td><a href="<?php echo $remove_action_string;?>&QuestionID=<?php echo $row['QuestionID'];?>">Remove</a></td>
			  </tr>
		<?php } ?>
		
		</table>
	<?php } else { echo "<p color='red'>No questions were found.</p>";}?>

<p>
	
	<a href="<?php echo $add_mc_action_string;?>">Add Multiple Choise Question</a>
	<br />
	<a href="<?php echo $add_tf_action_string;?>">Add True/False Question</a>
	<br />
	<br />
	<a href="instructor.php?InsID=<?php echo $InsID;?>">Back to instructor homepage</a>
	<br />
	<a href="login.php">Logout</a>
	
</p>

</body>
</html>

<?php 
mysql_close($con);
mysql_free_result($topics_result);
mysql_free_result($topic_result);
?>
