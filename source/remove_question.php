<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {
	
	$InsID = $_GET["InsID"];
	$TopicID = $_GET["TopicID"];	
	
	if (($_GET["QuestionID"] != "")) {
		$QuestionID = $_GET["QuestionID"];
		$exists_query = "SELECT QuestionID FROM QUESTIONS WHERE QuestionID='".$QuestionID."';";
		$exists_result = mysql_query($exists_query) or die(mysql_error());
		$question_array = mysql_fetch_assoc($exists_result);
		$question_exists = mysql_num_rows($exists_result);
		
		if ($question_exists) {
			$delete_result = mysql_query("DELETE FROM QUESTIONS WHERE QuestionID='".$QuestionID."';") or die(mysql_error());
			header("Location: questions.php?InsID=".$InsID."&TopicID=".$TopicID);
		} 
	}	
	else {header("Location: questions.php?InsID=".$InsID);}
} else { header("Location: login.php");}


mysql_close($con);?>