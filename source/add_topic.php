<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {$InsID = $_GET["InsID"];	
	if (($_GET["topic_name"] != "")) {$topic_name = $_GET["topic_name"];}
	else {header("Location: topics.php?InsID=".$InsID."&error=Please complete the form entirely.");}
} else { header("Location: login.php");}

if ($InsID != "" && $topic_name != ""){

		$exists_query = "SELECT TopicID FROM TOPICS WHERE name='".$topic_name."';";
		$exists_result = mysql_query($exists_query) or die(mysql_error());
		$topic_exists = mysql_num_rows($exists_result);
				
		if ($topic_exists == 0) {
			//insert into db
			$add_query = "INSERT INTO TOPICS (name) VALUES ('".$topic_name."');";
			$add_result = mysql_query($add_query) or die(mysql_error());
			header("Location: topics.php?InsID=" . $InsID);
			}
		else {header("Location: topics.php?InsID=".$InsID."&error=Topic already exists.");}
}

?>

<?php mysql_close($con);?>