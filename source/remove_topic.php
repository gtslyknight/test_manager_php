<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {
	
	$InsID = $_GET["InsID"];	
	
	if (($_GET["TopicID"] != "")) {
		$TopicID = $_GET["TopicID"];
		$exists_query = "SELECT name FROM TOPICS WHERE TopicID='".$TopicID."';";
		$exists_result = mysql_query($exists_query) or die(mysql_error());
		$topic_array = mysql_fetch_assoc($exists_result);
		$topic_exists = mysql_num_rows($exists_result);
		
		if ($topic_exists) {
			$delete_result = mysql_query("DELETE FROM TOPICS WHERE TopicID='".$TopicID."';") or die(mysql_error());
			header("Location: topics.php?InsID=".$InsID);
		} 
	}	
	else {header("Location: topics.php?InsID=".$InsID);}
} else { header("Location: login.php");}


mysql_close($con);?>