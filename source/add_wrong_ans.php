<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {
	$InsID = $_GET["InsID"];
	
	if (($_GET["CRN"] != "")) {$CRN = $_GET["CRN"];}
	if (($_GET["TestID"] != "")) {$TestID = $_GET["TestID"];}
	
	if (($_GET["TopicID"] != "")) 
		{$TopicID = $_GET["TopicID"];}
	
	if (($_GET["QuestionID"] != "")) {$QuestionID = $_GET["QuestionID"];}
	else {header("Location: questions.php?InsID=".$InsID);}
		
	if (($_GET["ans_text"] != "")) {$ans_text = $_GET["ans_text"];}
	else {header("Location: update_question.php?InsID=".$InsID."&TopicID=".$TopicID."&QuestionID=".$QuestionID."&error=Fill in wrong answer text");}
} else {header("Location: login.php");}

$update_question_url = "update_question.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&TopicID=".$TopicID."&QuestionID=".$QuestionID;
$questions_url = "questions.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&TopicID=".$TopicID;

//Check if valid question ans combo
$exists_query = "SELECT QuestionID FROM QUESTIONS WHERE QuestionID='".$QuestionID."';";
$exists_result = mysql_query($exists_query) or die(mysql_error());
$question_found = mysql_num_rows($exists_result);

$next_query = "SELECT max(WrongAnsID) FROM MC_WRONG_ANS WHERE QuestionID='".$QuestionID."';";
$next_result = mysql_query($next_query) or die(mysql_error());
$next_array = mysql_fetch_array($next_result);
if ($next_array['max(WrongAnsID)'] == NULL) {
	$next_wrong_id = 1;} 
else 
	{$next_wrong_id = $next_array['max(WrongAnsID)']+1;}

//Update statement
if ($InsID != "" && $ans_text != "" && $question_found && $next_wrong_id){
	//insert into db
	$update_query = "INSERT INTO MC_WRONG_ANS (QuestionID, WrongAnsID, WrongAns) VALUES ('".$QuestionID."','".$next_wrong_id."','".$ans_text."');";
	$update_result = mysql_query($update_query) or die(mysql_error());
	header("Location: ".$update_question_url);
} else {header("Location: ".$questions_url."&error=Question not found.");}


?>


<?php mysql_close($con);?>