<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["username"] != "")) {$username = $_GET["username"];	
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
	($_GET["password_1"] == $_GET["password_2"]) ){

		$exists_query = "SELECT * FROM USERS WHERE username='".$ins_username."';";
		$exists_result = mysql_query($exists_query) or die(mysql_error());
		$username_exists = mysql_num_rows($exists_result);
				
		if ($username_exists == 0) {
			//insert into db
			$add_query = "INSERT INTO USERS VALUES ('".$ins_username."', '".$password."');";
			$add_result = mysql_query($add_query) or die(mysql_error());
			mysql_query("INSERT INTO INSTRUCTORS (username, fname, lname) VALUES('".$ins_username."', '".$fname."', '".$lname."');") or die(mysql_error());
			header("Location: manage_instructors.php?username=" . $username);
			}
		else {header("Location: manage_instructors.php?username=".$username."&error=Username already taken");}
}

?>

<?php mysql_close($con);?>