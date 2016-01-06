<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["username"] != "")) {
	
	$username = $_GET["username"];	
	
	if (($_GET["InsID"] != "")) {
		$InsID = $_GET["InsID"];
		$exists_query = "SELECT username FROM INSTRUCTORS WHERE InsID='".$InsID."';";
		$exists_result = mysql_query($exists_query) or die(mysql_error());
		$ins_array = mysql_fetch_assoc($exists_result);
		$ins_exists = mysql_num_rows($exists_result);
		
		if ($ins_exists) {
			$ins_username = $ins_array['username'];
			$exists_result = mysql_query("DELETE FROM USERS WHERE username='".$ins_username."';") or die(mysql_error());
			header("Location: manage_instructors.php?username=".$username);
		}
	}	
	else {header("Location: manage_instructors.php?username=".$username);}
} else { header("Location: login.php");}


mysql_close($con);?>