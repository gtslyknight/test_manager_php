<?php require_once('LOCALHOST.php'); ?>
<?php 
if (($_GET["username"] != "")){
	$username = $_GET["username"];
}
else {
	header("Location: login.php");
}

$title_query = "SELECT Title FROM ADMINS WHERE Username='".$username."'";
$title_result = mysql_query($title_query) or die(mysql_error());
$title_array = mysql_fetch_assoc($title_result);
$foundTitle = mysql_num_rows($title_result);

if($foundTitle){
	$title = $title_array["Title"];
}



?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Admin Page</title>
</head>

<body>

<h1>Test Management System - Admin Page</h1>
<p>Welcome, <?php echo $username; ?>! Your current title is: <?php echo $title; ?></p>
<p>
	<a href="manage_instructors.php?username=<?php echo $username;?>">Manage Instructors</a>
	<br />
	<a href="change_password.php?username=<?php echo $username;?>">Change Password</a>
	<br />
	<a href="login.php">Logout</a>
	
</p>

</body>
</html>

<?php mysql_close($con);?>