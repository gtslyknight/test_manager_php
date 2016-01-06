<?php require_once('LOCALHOST.php'); ?>
<?php 
if (($_GET["InsID"] != "")){
	$InsID = $_GET["InsID"];
}
else {
	header("Location: login.php");
}

if (($_GET["TopicID"] != "")){
	$TopicID = $_GET["TopicID"];
}



if (($_GET["CRN"] != "")) {$CRN = $_GET["CRN"];}
if (($_GET["TestID"] != "")) {$TestID = $_GET["TestID"];}
if (($_GET["keyword"] != "")) {$keyword = $_GET["keyword"];}
if (($_GET["type"] != "")) {$type = $_GET["type"];}

if (($_GET["QuestionID"] != "")){
	$QuestionID = $_GET["QuestionID"];
}

if (($_GET["sortby"] != "")){
	$sortby = $_GET["sortby"];
} else { $sortby = "QuestionID";}

$sortby_QuestionID = "search_questions.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&sortby=QuestionID&TopicID=".$TopicID;
$sortby_Instructor = "search_questions.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&sortby=lname, fname&TopicID=".$TopicID;

if ($type == "tf" || $type =="both" || !isset($type)){
	$tf_result = mysql_query("SELECT Q.QuestionID, Q.QText, I.fname, I.lname
		FROM QUESTIONS AS Q, TF_QUESTIONS AS TFQ, INSTRUCTORS AS I
		WHERE Q.QuestionID = TFQ.QuestionID AND Q.CreatedBy = I.InsID;") or die(mysql_error());
	$tf_exists = mysql_num_rows($tf_result);
} 
if ($type == "mc" || $type =="both" || !isset($type)) {
	$mc_result = mysql_query("SELECT Q.QuestionID, Q.QText, I.fname, I.fname
		FROM QUESTIONS AS Q, MC_QUESTIONS AS MCQ, INSTRUCTORS AS I
		WHERE Q.QuestionID = MCQ.QuestionID AND Q.CreatedBy = I.InsID;") or die(mysql_error());
	$mc_exists = mysql_num_rows($mc_result);
}

$update_action_string = "update_question.php?InsID=".$InsID."&TopicID=".$TopicID;
$add_mc_action_string = "add_mc_question.php?InsID=".$InsID."&TopicID=".$TopicID;
$add_tf_action_string = "add_tf_question.php?InsID=".$InsID."&TopicID=".$TopicID;
$remove_action_string = "remove_question.php?InsID=".$InsID."&TopicID=".$TopicID;

$add_question_url = "update_test.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&Add_QuestionID=";
$questions_link = "questions.php?InsID=".$InsID."&TopicID=";
$search_url = "search_questions.php";
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Questions</title>
</head>

<body>

<h1>Test Management System - Questions</h1>
<?php if (($_GET['error'] != "")) {echo $_GET['error'];} ?>

	<h2><Question Search</h2>
	<form name="search_form" method="get" action="<?php echo $search_url;?>">
		<p>
		<label>Question Type<br>
			<input type="radio" name="type" value="both" <?php if($type=="both" || !isset($type)){echo "checked";}?> DEFAULT>Both
			<input type="radio" name="type" value="tf" <?php if($type=="tf"){echo "checked";}?>>True/False
			<input type="radio" name="type" value="mc" <?php if($type=="mc"){echo "checked";}?>>Multiple Choice
		</label>
		<br>
		<label>Keyword
			<input type="text" name="keyword" value="<?php echo $_GET["keyword"];?>">
		</label>
		<br>
		<label>QuestionID
			<input type="text" name="QuestionID" value="<?php echo $_GET["QuestionID"];?>">
		</label>
		<br>
		</p>
		<p>
		<label>
		<input type="submit" value="Search">
		</label>
			<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
			<input type="hidden" name="TopicID" value="<?php echo $TopicID; ?>" />
			<input type="hidden" name="QuestionID" value="<?php echo $QuestionID; ?>" />
			<input type="hidden" name="TestID" value="<?php echo $TestID; ?>" />
			<input type="hidden" name="CRN" value="<?php echo $CRN; ?>" />
	  </p>
		
	</form>

	<h2>Question List</h2>
	
	<?php 
	if($tf_exists) { 
	?>
	<h3>True/False Questions</h3>
		<table border="0" cellspacing="2">
		  <tr>
			<th scope="col"><a href="<?php echo $sortby_QuestionID;?>">QuestionID</a></th>
			<th scope="col">Question Text</th>
			<th scope="col"><a href="<?php echo $sortby_Instructor;?>">Instructor</a></th>
		  </tr>
		  <?php
		  
		  while($row = mysql_fetch_array($tf_result))
			  { if(strpos(" ".$row['QText'],$keyword) || !isset($keyword)) {?>
			  <tr align='center'>
			  <td><?php echo $row['QuestionID'];?></td>
			  <td><?php echo $row['QText'];?></td>
			  <td><?php echo $row['fname']." ".$row['lname'];?></td>
			  <td><a href="<?php echo $add_question_url.$row['QuestionID'];?>">Add to test</a></td>
			  </tr>
		<?php }} ?>
		
		</table>
	<?php } 
	if ($mc_exists) { 
	?>
	<h3>Multiple Choice Questions</h3>
		<table border="0" cellspacing="2">
		  <tr>
			<th scope="col"><a href="<?php echo $sortby_QuestionID;?>">QuestionID</a></th>
			<th scope="col">Question Text</th>
			<th scope="col"><a href="<?php echo $sortby_Instructor;?>">Instructor</a></th>
		  </tr>
		  <?php
		  
		  while($row = mysql_fetch_array($mc_result))
			  { if(strpos(" ".$row['QText'],$keyword) || !isset($keyword)) {?>
			  <tr align='center'>
			  <td><?php echo $row['QuestionID'];?></td>
			  <td><?php echo $row['QText'];?></td>
			  <td><?php echo $row['fname']." ".$row['lname'];?></td>
			  <td><a href="<?php echo $add_question_url.$row['QuestionID'];?>">Add to test</a></td>
			  </tr>
		<?php }} ?>
		
		</table>
	
	
	<?php } ?>

<p>
	
	<a href="<?php echo $add_mc_action_string;?>">Add Multiple Choise Question</a>
	<br />
	<a href="<?php echo $add_tf_action_string;?>">Add True/False Question</a>
	<br />
	<br />
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
