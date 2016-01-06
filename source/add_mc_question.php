<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {$InsID = $_GET["InsID"];} 
else { header("Location: login.php");}

if (($_GET["TopicID"] != "")) {$TopicID = $_GET["TopicID"];}
if (($_GET["QText"] != "")) {$QText = $_GET["QText"];}
if (($_GET["Score"] != "")) {$Score = $_GET["Score"];}
if (($_GET["ans_text"] != "")) {$ans_text = $_GET["ans_text"];}

$questions_url = "questions.php?InsID=".$InsID."&TopicID=".$TopicID;
$home_url = "instructor.php?InsID=".$InsID;
$logout_url = "login.php";

$topics_query = "SELECT TopicID, Name FROM TOPICS ORDER BY Name;";
$topics_result = mysql_query($topics_query) or die(mysql_error());
$topics_found = mysql_num_rows($topics_result);

if (isset($InsID) && isset($TopicID) && isset($QText) && isset($Score) && isset($ans_text)) {
	$add_question_query = "INSERT INTO QUESTIONS (Score, QText, QTopic, CreatedBy) VALUES('".$Score."','".$QText."','".$TopicID."','".$InsID."');";
	$add_question_result = mysql_query($add_question_query) or die(mysql_error());
	$last_id = mysql_insert_id();
	$add_mc_question_query = "INSERT INTO MC_QUESTIONS (QuestionID, Ans) VALUES('".$last_id."','".$ans_text."');";
	$add_mc_question_result = mysql_query($add_mc_question_query) or die(mysql_error());
	header("Location: update_question.php?InsID=".$InsID."&TopicID=".$TopicID."&QuestionID=".$last_id);
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Add Question</title>
</head>

<body>

<h1>Test Management System - Add Question</h1>
<h2>Add a Multiple Choice Question</h2>
	<form name="add_form" method="get" action="add_mc_question.php">
	  <p>
		<label>Question Text</label>
		<br />
		<textarea name="QText" cols="40" rows="4"></textarea>
		<br>
		<label>Correct Answer
		<input type="text" name="ans_text" size="50">
		</label>
		<br/>
		<label>Score
		<input type="text" name="Score" size="3">
		</label>
		<br/>
		<label>Select Topic
		<select name="TopicID">
		<?php while($row = mysql_fetch_array($topics_result))
			  {?>
		  		<option value="<?php echo $row['TopicID']; ?>" <?php if($TopicID == $row['TopicID']){echo "SELECTED";}?>><?php echo $row['Name']; ?></option>
		<?php } ?>
		</select>
		</label>
	  </p>
	  <p>
		<label>
		<input type="submit" value="Add Question">
		</label>
			<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
	  </p>
	</form>
	
	<br>
	<a href="<?php echo $questions_url;?>">Cancel - Go Back to Questions</a>
	<br>
	<a href="<?php echo $instructor_url;?>">Back to instructor homepage</a>
	<br>
	<a href="<?php echo $logout_url;?>">Logout</a>
	<br>
	
</body>
</html>
<?php mysql_close($con);?>