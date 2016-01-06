<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["username"] != "")) {$username = $_GET["username"];
	if (($_GET["InsID"] != "")) {$InsID = $_GET["InsID"];}
	else {header("Location: manage_instructors.php?username=".$username."&error=Please complete the form entirely.");}
	if (($_GET["ins_username"] != "")) {$ins_username = $_GET["ins_username"];}
	else {header("Location: manage_instructors.php?username=".$username."&error=Please complete the form entirely.");}
	if (($_GET["fname"] != "")) {$fname = $_GET["fname"];}
	else {header("Location: manage_instructors.php?username=".$username."&error=Please complete the form entirely.");}
	if (($_GET["lname"] != "")) {$lname = $_GET["lname"];}
	else {header("Location: manage_instructors.php?username=".$username."&error=Please complete the form entirely.");}
	if ($_GET["password_1"] == $_GET["password_2"]) {$password = $_GET["password_1"];}
	else {header("Location: manage_instructors.php?username=".$username."&error=Passwords are not equal. Try again.");}
} else { header("Location: login.php");}

if (($_GET["username"] != "") &&
	($_GET["ins_username"] != "") && 
	($_GET["fname"] != "") &&
	($_GET["lname"] != "") && 
	($_GET["password_1"] != "") && 
	($_GET["password_2"] != "") &&
	($_GET["password_1"] == $_GET["password_2"]) && 
	$InsID != ""){

		$id_exists_query = "SELECT username FROM INSTRUCTORS WHERE InsID='".$InsID."';";
		$id_exists_result = mysql_query($id_exists_query) or die(mysql_error());
		$old_ins_info = mysql_fetch_array($id_exists_result);
		$ins_exists = mysql_num_rows($id_exists_result);
		$old_username = $old_ins_info['username'];
		
		$exists_query = "SELECT * FROM USERS WHERE username='".$ins_username."';";
		$exists_result = mysql_query($exists_query) or die(mysql_error());
		$username_exists = mysql_num_rows($exists_result);
				
		if ($ins_exists != 0 && ($ins_username == $old_username || $username_exists == 0)) {
			//insert into db
			$update_query = "UPDATE INSTRUCTORS,USERS SET USERS.username='".$ins_username."', fname='".$fname."', lname='".$lname."', password='".$password."' WHERE USERS.username=INSTRUCTORS.username AND INSTRUCTORS.InsID='".$InsID."';";
			$update_result = mysql_query($update_query) or die(mysql_error());
			header("Location: manage_instructors.php?username=" . $username);
			}
		else {header("Location: manage_instructors.php?username=".$username."&error=Username taken.");}
}

?>

<?php mysql_close($con);?>