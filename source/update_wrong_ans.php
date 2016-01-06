<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {
	$InsID = $_GET["InsID"];
	
	if (($_GET["CRN"] != "")) {$CRN = $_GET["CRN"];}
	if (($_GET["TestID"] != "")) {$TestID = $_GET["TestID"];}
	if ($_GET["TopicID"] != "") {$TopicID = $_GET["TopicID"];}
	if ($_GET["WrongAnsID"] != "") {$WrongAnsID = $_GET["WrongAnsID"];}
	
	if (($_GET["TopicID"] != "")) 
		{$TopicID = $_GET["TopicID"];}
	
	if (($_GET["QuestionID"] != "")) 
		{$QuestionID = $_GET["QuestionID"];}
	else 
		{header("Location: update_question.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&TopicID=".$TopicID);}
	
	if (($_GET["WrongAnsID"] != "")) 
		{$WrongAnsID = $_GET["WrongAnsID"];}
	else 
		{header("Location: update_question.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&TopicID=".$TopicID."&QuestionID=".$QuestionID."&error=No wrong answer id passed on.");}
} else { header("Location: login.php");}

if (($_GET["ans_text"] != "")) {$ans_text = $_GET["ans_text"];}

$update_question_url = "update_question.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&TopicID=".$TopicID."&QuestionID=".$QuestionID;

//Check if valid question ans combo
$wa_exists_query = "SELECT WrongAnsID FROM MC_WRONG_ANS WHERE QuestionID='".$QuestionID."' AND WrongAnsID='".$WrongAnsID."';";
$wa_exists_result = mysql_query($wa_exists_query) or die(mysql_error());
$wrong_ans_found = mysql_num_rows($wa_exists_result);

if ($wrong_ans_found) {
	$wrong_ans_query = "SELECT QuestionID, WrongAnsID, WrongAns FROM MC_WRONG_ANS WHERE QuestionID='".$QuestionID."';";
	$wrong_ans_result = mysql_query($wrong_ans_query) or die(mysql_error());
	$wrong_ans_array = mysql_fetch_array($wrong_ans_result);
	
	$question_result = mysql_query("SELECT QText FROM QUESTIONS WHERE QuestionID='".$QuestionID."';") or die(mysql_error());
	$question_array = mysql_fetch_array($question_result);
} else {header("Location: ".$update_question_url."&error=Invalid QuestionID, WrongAnsID sent to update_wrong_ans.php");}

//Update statement
if ($InsID != "" && $WrongAnsID != "" && $ans_text != "" && $wrong_ans_found){

	//update db
	$update_query = "UPDATE MC_WRONG_ANS SET WrongAns='".$ans_text."' WHERE QuestionID='".$QuestionID."' AND WrongAnsID='".$WrongAnsID."';";
	$update_result = mysql_query($update_query) or die(mysql_error());
	header("Location: " . $update_question_url);

}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Update Question</title>
</head>

<body>

<h1>Test Management System - Update Wrong Answer</h1>
<h2>Update a wrong answer</h2>
	<form name="add_form" method="get" action="update_wrong_ans.php">
	  <p>
		<label>Question Text: </label>
		<?php echo $question_array['QText'];?>
	  </p>
	  <p>
		<label>Wrong Answer Text
		<input type="text" name="ans_text" value="<?php echo $wrong_ans_array['WrongAns'];?>" size="50">
		</label>
		<br/>
	  </p>
	  <p>
		<label>
		<input type="submit" value="Update Wrong Answer">
		</label>
			<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
			<input type="hidden" name="TopicID" value="<?php echo $TopicID; ?>" />
			<input type="hidden" name="QuestionID" value="<?php echo $QuestionID; ?>" />
			<input type="hidden" name="WrongAnsID" value="<?php echo $WrongAnsID; ?>" />
			<input type="hidden" name="TestID" value="<?php echo $TestID; ?>" />
			<input type="hidden" name="CRN" value="<?php echo $CRN; ?>" />
	  </p>
	</form>
	<br>
	<?php if (isset($TestID) && isset($CRN)){ ?>
<a href="<?php echo $questions_url;?>">Go Back to Test</a>
<?php } ?>
	<br>
	<a href="<?php echo $update_question_url;?>">Cancel - Go Back to Question</a>
	<br>
	<a href="<?php echo $instructor_url;?>">Back to instructor homepage</a>
	<br>
	<a href="<?php echo $logout_url;?>">Logout</a>
	<br>
	
	
</body>
</html>


<?php mysql_close($con);?>