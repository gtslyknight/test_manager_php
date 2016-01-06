<?php require_once('LOCALHOST.php'); ?>
<?php 
if (($_GET["InsID"] != "")){
	$InsID = $_GET["InsID"];
}
else {
	header("Location: login.php");
}

if($InsID) {
	$ins_query = "SELECT FName FROM INSTRUCTORS WHERE InsID='".$InsID."'";
	$instructor_result = mysql_query($ins_query) or die(mysql_error());
	$InsID_result = mysql_fetch_assoc($instructor_result);
	$foundIns = mysql_num_rows($instructor_result);
	
	if($foundIns){
		$FName = $InsID_result["FName"];
	}
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Instructor Page</title>
</head>

<body>

<h1>Test Management System - Instructor Page</h1>
<p>Welcome<?php if(isset($FName)){echo ", ".$FName;} ?>.</p>

<p>
	<a href="topics.php?InsID=<?php echo $InsID;?>">Question Library</a>
	<br />
	<a href="courses.php?InsID=<?php echo $InsID;?>">Exams Library (Select Course)</a>
	<br />
	<a href="change_password.php?InsID=<?php echo $InsID;?>">Change Password</a>
	<br />
	<a href="login.php">Logout</a>
	
</p>

</body>
</html>

<?php mysql_close($con);?>