<?php require_once('LOCALHOST.php'); ?>

<?php

if (($_GET["username"] != "") && ($_GET["password"] != "")){
	$username = $_GET["username"];
	$password = $_GET["password"];

$LoginBoth_query = "SELECT username, password FROM USERS WHERE 
					username='".$username."' AND password='".$password."'";
$admin_query = "SELECT * FROM ADMINS WHERE username='".$username."'";
$ins_query = "SELECT InsID FROM INSTRUCTORS WHERE username='".$username."'";
	
	$LoginBoth = mysql_query($LoginBoth_query) or die(mysql_error());
	$loginInfo = mysql_fetch_assoc($LoginBoth);
	$loginFoundUser = mysql_num_rows($LoginBoth);
	if ($loginFoundUser) {
		
		$instructor_result = mysql_query($ins_query) or die(mysql_error());
		$InsID_result = mysql_fetch_assoc($instructor_result);
		$foundIns = mysql_num_rows($instructor_result);
		//if it is an instructor remember InsID and move onto instructor.php
		if($foundIns){
			$InsID = $InsID_result["InsID"];
			header("Location: instructor.php?InsID=" . $InsID);
		}
		else {
			//otherwise it's an admin
			header("Location: admin.php?username=" . $username);
		}
	}
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Login</title>
</head>

<body>
<h1>Test Management System - Login Page</h1>
<p>Welcome Admin's and Instructors! Please Login using the form below.</p>

<?php if(isset($username)) echo "<p color='red'>Incorrect username and password combination</p>"?>
<form name="form1" method="get" action="login.php">
  <label>Username
  <input type="text" name="username">
  </label>
  <p>
    <label>Password
    <input type="password" name="password">
    </label>
  </p>
  <p>
    <label>
    <input type="submit">
    </label>
</p>
</form>


</body>
</html>

<?php mysql_close($con);?>