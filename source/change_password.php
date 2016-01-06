<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["username"] != "")){
	$username = $_GET["username"];
} else if (($_GET["InsID"] != "")){
	$InsID = $_GET["InsID"];
} else {
	header("Location: login.php");
}


if($InsID) {
	$ins_query = "SELECT username, FName FROM INSTRUCTORS WHERE InsID='".$InsID."'";
	$instructor_result = mysql_query($ins_query) or die(mysql_error());
	$InsID_result = mysql_fetch_assoc($instructor_result);
	$foundIns = mysql_num_rows($instructor_result);
	
	if($foundIns){
		$ins_username = $InsID_result["username"];
		$FName = $InsID_result["FName"];
	}
}

if (($_GET["password"] != "") && ($_GET["new_password_1"] != "") && ($_GET["new_password_2"] != "")){
	$password = $_GET["password"];
	if ($_GET["new_password_1"] == $_GET["new_password_2"]) {
		$new_password = $_GET["new_password_1"];
	}
}

//admins
if ($password && $new_password && ($username)) {

	$passwdck_query = "SELECT username, password FROM USERS WHERE 
					username='".$username."' AND password='".$password."'";
	
	$passwdck_result = mysql_query($passwdck_query) or die(mysql_error());
	$FoundUser = mysql_num_rows($passwdck_result);
	if ($FoundUser) {
		$update_query = "UPDATE USERS SET Password='".$new_password."'WHERE Username='".$username."'";
		$update_result = mysql_query($update_query) or die(mysql_error());
		header("Location: admin.php?username=" . $username);	
	}
}

//instructors
if ($password && $new_password && ($ins_username)) {

	$passwdck_query = "SELECT username, password FROM USERS WHERE 
					username='".$ins_username."' AND password='".$password."'";
	
	$passwdck_result = mysql_query($passwdck_query) or die(mysql_error());
	$FoundUser = mysql_num_rows($passwdck_result);
	if ($FoundUser) {
		$update_query = "UPDATE USERS SET Password='".$new_password."'WHERE Username='".$ins_username."'";
		$update_result = mysql_query($update_query) or die(mysql_error());
		header("Location: instructor.php?InsID=" . $InsID);
	}
}


if ($InsID) {$action_string = "change_password.php?InsID=".$InsID;}
else if ($username) {$action_string = "change_password.php?username=".$username;}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Change Password</title>
</head>

<body>
<h1>Test Management System - Change Password Page</h1>
<p>Welcome, <?php if(isset($username)){echo $username;} else {echo $FName;} ?>! 
	Please fill out this form to change your password.</p>

<form name="form1" method="get" action="<?php echo $action_string;?>">
  <p>
	<label>Old Password
	<input type="password" name="password">
	</label>
	<br />
	<label>New Password
	<input type="password" name="new_password_1">
	</label>
	<br />
	<label>Re-enter New Password
	<input type="password" name="new_password_2">
	</label>
  </p>
  <p>
	<label>
	<input type="submit">
	</label>
	<?php if(isset($username)) { ?>
	<input type="hidden" name="username" value="<?php echo $username; ?>" />
	<?php } ?>
	<?php if(isset($InsID)) { ?>
	<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
	<?php } ?>
  </p>
</form>


</body>
</html>

<?php mysql_close($con);?>