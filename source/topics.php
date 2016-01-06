<?php require_once('LOCALHOST.php'); ?>
<?php 
if (($_GET["InsID"] != "")){
	$InsID = $_GET["InsID"];
}
else {
	header("Location: login.php");
}

if (($_GET["sortby"] != "")){
	$sortby = $_GET["sortby"];
} else { $sortby = "TopicID";}

$sortby_TopicID = "?InsID=".$InsID."&sortby=TopicID";
$sortby_Name = "?InsID=".$InsID."&sortby=Name";
$sortby_Count = "?InsID=".$InsID."&sortby=Count(QuestionID) DESC";

$topics_query = "SELECT TopicID, Name, Count(QuestionID) FROM TOPICS LEFT OUTER JOIN QUESTIONS 
				ON TOPICS.TopicID=QUESTIONS.QTopic GROUP BY TopicID 
				ORDER BY ".$sortby.";";
$topics_result = mysql_query($topics_query) or die(mysql_error());
$topics_found = mysql_num_rows($topics_result);

$update_action_string = "update_topic.php?InsID=".$InsID;
$add_action_string = "add_topic.php?InsID=".$InsID;
$remove_action_string = "remove_topic.php?InsID=".$InsID;

$questions_link = "questions.php?InsID=".$InsID."&TopicID=";

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Topics</title>
</head>

<body>

<h1>Test Management System - Topics</h1>
<?php if (($_GET['error'] != "")) {echo "<p color='red'>There was an error adding the topic.</p>".$_GET['error'];} ?>

<?php
if (($_GET['TopicID'] != "")) {
	$TopicID = $_GET['TopicID'];
	$topic_query = "SELECT name FROM TOPICS WHERE TopicID='".$TopicID."';";
	$topic_result = mysql_query($topic_query) or die(mysql_error());
	$topic_info_array = mysql_fetch_array($topic_result);
	$topic_found = mysql_num_rows($topic_result);
	
	if ($topic_found) {

?>
	<h2>Update Topic Name</h2>
	<form name="update_form" method="get" action="<?php echo $update_action_string;?>">
	  <p>
		<label>New Topic Name
		<input type="text" name="topic_name" value="<?php echo $topic_info_array['name'];?>">
		</label>
	  </p>
	  <p>
		<label>
		<input type="submit" value="Update">
		</label>
		<?php if($InsID != "") { ?>
			 <input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
		<?php } ?>
		<?php if($TopicID != "") { ?>
			 <input type="hidden" name="TopicID" value="<?php echo $TopicID; ?>" />
		<?php } ?>
	  </p>
	</form>
	<a href="topics.php?InsID=<?php echo $InsID;?>">Cancel</a>
<?php 
	} else {header("Location: topics.php?InsID=".$InsID);}
} else { ?>

	<h2>Add a Topic</h2>
	<form name="add_form" method="get" action="<?php echo $add_action_string;?>">
	  <p>
		<label>Topic Name
		<input type="text" name="topic_name">
		</label>
	  </p>
	  <p>
		<label>
		<input type="submit" value="Add Topic">
		</label>
		<?php if(($InsID != "")) { ?>
			<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
		<?php } ?>
	  </p>
	</form>
	
	<h2>Topic List</h2>
	<p>Choose a topic below (by clicking the name) or <a href="<?php echo $questions_link?>">view all questions</a></p>
	<?php 
	if($topics_found) { 
	?>
		<table border="0" cellspacing="2">
		  <tr>
			<th scope="col"><a href="topics.php<?php echo $sortby_TopicID;?>">TopicID</a></th>
			<th scope="col"><a href="topics.php<?php echo $sortby_Name;?>">Name</a></th>
			<th scope="col"><a href="topics.php<?php echo $sortby_Count;?>"># of Questions</a></th>
		  </tr>
		  <?php
		  
		  while($row = mysql_fetch_array($topics_result))
			  { ?>
			  <tr align='center'>
			  <td><?php echo $row['TopicID'];?></td>
			  		<td><a href="<?php echo $questions_link.$row['TopicID'];?>"><?php echo $row['Name'];?></a></td>
			  		<td><?php echo $row['Count(QuestionID)'];?></td>
			  <td><a href="topics.php?InsID=<?php echo $InsID;?>&TopicID=<?php echo $row['TopicID'];?>">Edit Name</a></td>
			  <td><a href="<?php echo $remove_action_string;?>&TopicID=<?php echo $row['TopicID'];?>">Remove Topic</a></td>
			  </tr>
		<?php } ?>
		
		</table>
	<?php } else { echo "<p color='red'>No topics were found.</p>";}?>
	
<?php } ?>

<p>
	<a href="instructor.php?InsID=<?php echo $InsID;?>">Back to instructor homepage</a>
	<br />
	<a href="login.php">Logout</a>
	
</p>

</body>
</html>

<?php 
mysql_close($con);
mysql_free_result($topics_result);
mysql_free_result($topic_result);
?>
