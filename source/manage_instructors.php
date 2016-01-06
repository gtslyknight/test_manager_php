<?php require_once('LOCALHOST.php'); ?>
<?php 
if (($_GET["username"] != "")){
	$username = $_GET["username"];
}
else {
	header("Location: login.php");
}

if (($_GET["sortby"] != "")){
	$sortby = $_GET["sortby"];
} else { $sortby = "InsID";}

$sortby_InsID = "?username=".$username."&sortby=InsID";
$sortby_username = "?username=".$username."&sortby=username";
$sortby_fname = "?username=".$username."&sortby=fname";
$sortby_lname = "?username=".$username."&sortby=lname";

$instructors_query = "SELECT * FROM INSTRUCTORS ORDER by ".$sortby;
$instructors_result = mysql_query($instructors_query) or die(mysql_error());
$instructors_found = mysql_num_rows($instructors_result);

$update_action_string = "update_instructor.php?username=".$username;
$add_action_string = "add_instructor.php?username=".$username;
$remove_action_string = "remove_instructor.php?username=".$username;

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Manage Instructors</title>
</head>

<body>

<h1>Test Management System - Manage Instructors</h1>
<?php if (($_GET['error'] != "")) {echo "<p color='red'>There was an error adding the instructor.</p>".$_GET['error'];} ?>

<?php
if (($_GET['InsID'] != "")) {
	$InsID = $_GET['InsID'];
	$ins_query = "SELECT USERS.username, fname, lname, password FROM INSTRUCTORS JOIN USERS on INSTRUCTORS.username=USERS.username WHERE InsID='".$InsID."';";
	$ins_result = mysql_query($ins_query) or die(mysql_error());
	$ins_info_array = mysql_fetch_array($ins_result);
	$ins_found = mysql_num_rows($ins_result);
	
	if ($ins_found) {

?>
	<h2>Update Instructor Information</h2>
	<form name="update_form" method="get" action="<?php echo $update_action_string;?>">
	  <p>
		<label>Username
		<input type="text" name="ins_username" value="<?php echo $ins_info_array['username'];?>">
		</label>
		<br />
		<label>First Name
		<input type="text" name="fname" value="<?php echo $ins_info_array['fname'];?>">
		</label>
		<br />
		<label>Last Name
		<input type="text" name="lname" value="<?php echo $ins_info_array['lname'];?>">
		</label>
		<br />
		<label>Password
		<input type="password" name="password_1" value="<?php echo $ins_info_array['password'];?>">
		</label>
		<br />
		<label>Retype Password
		<input type="password" name="password_2" value="<?php echo $ins_info_array['password'];?>">
		</label>
	  </p>
	  <p>
		<label>
		<input type="submit" value="Update">
		</label>
		<?php if($username != "") { ?>
			 <input type="hidden" name="username" value="<?php echo $username; ?>" />
		<?php } ?>
		<?php if($InsID != "") { ?>
			 <input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
		<?php } ?>
	  </p>
	</form>
	<a href="manage_instructors.php?username=<?php echo $username;?>">Cancel</a>
<?php 
	} else {header("Location: manage_instructors.php?username=".$username);}
} else { ?>

	<h2>Add an Instructor</h2>
	<form name="add_form" method="get" action="<?php echo $add_action_string;?>">
	  <p>
		<label>Username
		<input type="text" name="ins_username">
		</label>
		<br />
		<label>First Name
		<input type="text" name="fname">
		</label>
		<br />
		<label>Last Name
		<input type="text" name="lname">
		</label>
		<br />
		<label>Password
		<input type="password" name="password_1">
		</label>
		<br />
		<label>Retype Password
		<input type="password" name="password_2">
		</label>
	  </p>
	  <p>
		<label>
		<input type="submit" value="Add User">
		</label>
		<?php if(($username != "")) { ?>
			<input type="hidden" name="username" value="<?php echo $username; ?>" />
		<?php } ?>
	  </p>
	</form>
	
	<h2>Instructor List</h2>
	<?php 
	if($instructors_found) { 
	?>
		<table width="500" border="0" cellspacing="1">
		  <tr>
			<th scope="col"><a href="manage_instructors.php<?php echo $sortby_InsID;?>">InsID</a></th>
			<th scope="col"><a href="manage_instructors.php<?php echo $sortby_username;?>">Username</a></th>
			<th scope="col"><a href="manage_instructors.php<?php echo $sortby_fname;?>">First</a></th>
			<th scope="col"><a href="manage_instructors.php<?php echo $sortby_lname;?>">Last</a></th>
		  </tr>
		  <?php
		  
		  while($row = mysql_fetch_array($instructors_result))
			  {
			  echo "<tr align='center'>";
			  echo "<td>".$row['InsID']."</td><td>".$row['Username']."</td><td>".$row['FName']."</td><td>".$row['LName']."</td>";
			  echo "<td><a href=\"manage_instructors.php?username=".$username."&InsID=".$row['InsID']."\">Update</a></td>";
			  echo "<td><a href=\"".$remove_action_string."&InsID=".$row['InsID']."\">Remove</a></td>";
			  echo "</tr>";
			  }
		  ?>
		
		</table>
	<?php } else { echo "<p color='red'>No instructors were found.</p>";}?>
	
<?php } ?>

<p>
	<a href="admin.php?username=<?php echo $username;?>">Back to admin homepage</a>
	<br />
	<a href="login.php">Logout</a>
	
</p>

</body>
</html>

<?php 
mysql_close($con);
mysql_free_result($instructors_result);
?>



