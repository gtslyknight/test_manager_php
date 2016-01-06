<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {
	
	$InsID = $_GET["InsID"];	
	if (($_GET["CRN"] != "")) {$CRN = $_GET["CRN"];}
	if (($_GET["TestID"] != "")) {$TestID = $_GET["TestID"];}
	
	if ($_GET["QuestionID"] != "") {$QuestionID = $_GET["QuestionID"];}
	if ($_GET["TopicID"] != "") {$TopicID = $_GET["TopicID"];}
	if ($_GET["WrongAnsID"] != "") {$WrongAnsID = $_GET["WrongAnsID"];}
	
	$update_question_url = "update_question.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&TopicID=".$TopicID."&QuestionID=".$QuestionID;
	$questions_url = "questions.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&TopicID=".$TopicID;

	
	if (isset($QuestionID) && isset($WrongAnsID)) {
		$exists_query = "SELECT * FROM MC_WRONG_ANS WHERE QuestionID='".$QuestionID."' AND WrongAnsID='".$WrongAnsID."';";
		$exists_result = mysql_query($exists_query) or die(mysql_error());
		$wa_array = mysql_fetch_assoc($exists_result);
		$wa_exists = mysql_num_rows($exists_result);
		
		if ($wa_exists) {
			$delete_result = mysql_query("DELETE FROM MC_WRONG_ANS WHERE QuestionID='".$QuestionID."' AND WrongAnsID='".$WrongAnsID."';") or die(mysql_error());
			header("Location: ".$update_question_url);
		} else {header("Location: ".$update_question_url."&error=Cannot delete answer because it doesn't exist");}
	}	
	else {header("Location: ".$update_question_url."&error=Cannot delete answer because not given enough information.");}
} else { header("Location: login.php");}


mysql_close($con);?>