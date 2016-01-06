<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["InsID"] != "")) {$InsID = $_GET["InsID"];
	if (($_GET["TopicID"] != "")) {$TopicID = $_GET["TopicID"];}
	else {header("Location: topics.php?InsID=".$InsID."&error=TopicID not passed on.");}
	if (($_GET["topic_name"] != "")) {$topic_name = $_GET["topic_name"];}
	else {header("Location: topics.php?InsID=".$InsID."&error=Please complete the form entirely.");}
} else { header("Location: login.php");}

if ($InsID != "" && $topic_name != "" && $TopicID != ""){

		$id_exists_query = "SELECT name FROM TOPICS WHERE TopicID='".$TopicID."';";
		$id_exists_result = mysql_query($id_exists_query) or die(mysql_error());
		$old_topic_info = mysql_fetch_array($id_exists_result);
		$id_exists = mysql_num_rows($id_exists_result);
		$old_name = $old_topic_info['name'];
		
		$exists_query = "SELECT TopicID FROM TOPICS WHERE name='".$topic_name."';";
		$exists_result = mysql_query($exists_query) or die(mysql_error());
		$name_exists = mysql_num_rows($exists_result);
				
		if ($id_exists && ($topic_name == $old_name || $name_exists == 0)) {
			//insert into db
			$update_query = "UPDATE TOPICS SET name='".$topic_name."' WHERE TopicID='".$TopicID."';";
			$update_result = mysql_query($update_query) or die(mysql_error());
			header("Location: topics.php?InsID=" . $InsID);
			}
		else {header("Location: topics.php?InsID=".$InsID."&error=Topic name already in use, or the name entered was unchanged.");}
}

?>

<?php mysql_close($con);?>