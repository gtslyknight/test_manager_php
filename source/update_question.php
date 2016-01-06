<?php require_once('LOCALHOST.php'); ?>
<?php 
// Take in: InsID, TopicID, QuestionID
// Show QText, Score, Ans, Wrong Ans List

if (($_GET["InsID"] != "")){
	$InsID = $_GET["InsID"];
}
else {
	header("Location: login.php");
}

if (($_GET["TopicID"] != "")){$TopicID = $_GET["TopicID"];}
if (($_GET["CRN"] != "")) {$CRN = $_GET["CRN"];}
if (($_GET["TestID"] != "")) {$TestID = $_GET["TestID"];}

if ( isset($CRN) && isset($TestID) ) {
	$questions_url = "update_test.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID;
} else {
	$questions_url = "questions.php?InsID=".$InsID."&TopicID=".$TopicID;
}
$update_url = "update_question.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&TopicID=".$TopicID."&QuestionID=";
$home_url = "instructor.php?InsID=".$InsID;
$logout_url = "login.php";



if (($_GET["QuestionID"] != "")){$QuestionID = $_GET["QuestionID"];}
else {
	header("Location: ".$questions_url."&error=No question id given to update_question.php");
}

$remove_wrong_ans_url = "remove_wrong_ans.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&TopicID=".$TopicID."&QuestionID=".$QuestionID;
$update_wrong_ans_url = "update_wrong_ans.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&TopicID=".$TopicID."&QuestionID=".$QuestionID;
$add_wrong_ans_url = "add_wrong_ans.php?InsID=".$InsID."&CRN=".$CRN."&TestID=".$TestID."&TopicID=".$TopicID."&QuestionID=".$QuestionID;

//Check if valid QuestionID
$exists_query = "SELECT QuestionID FROM QUESTIONS WHERE QuestionID='".$QuestionID."';";
$exists_result = mysql_query($exists_query) or die(mysql_error());
$question_found = mysql_num_rows($exists_result);

if ($question_found) {
	//check whether question is TF type
	$tf_check_result = mysql_query("SELECT QuestionID FROM TF_QUESTIONS WHERE QuestionID='".$QuestionID."';") or die(mysql_error());
	$tf_check = mysql_num_rows($tf_check_result);
	
	if ($tf_check) {
		$question_query = "SELECT QUESTIONS.QuestionID, QText, Score, QTopic, Ans FROM QUESTIONS LEFT OUTER JOIN TF_QUESTIONS ON QUESTIONS.QuestionID=TF_QUESTIONS.QuestionID WHERE QUESTIONS.QuestionID='".$QuestionID."';";
	} else {
		$question_query = "SELECT QUESTIONS.QuestionID, QText, Score, QTopic, Ans FROM QUESTIONS LEFT OUTER JOIN MC_QUESTIONS ON QUESTIONS.QuestionID=MC_QUESTIONS.QuestionID WHERE QUESTIONS.QuestionID='".$QuestionID."';";
	}
	
	$question_result = mysql_query($question_query) or die(mysql_error());
	$question_array = mysql_fetch_array($question_result);
	$question_found = mysql_num_rows($question_result);
	
	//get wrong answers for mc questions
	if ($tf_check == 0) {
		$wrong_ans_query = "SELECT QuestionID, WrongAnsID, WrongAns FROM MC_WRONG_ANS WHERE QuestionID='".$QuestionID."';";
		$wrong_ans_result = mysql_query($wrong_ans_query) or die(mysql_error());
		$wrong_ans_found = mysql_num_rows($wrong_ans_result);
	}

} else {header("Location: ".$questions_url."&error=Invalid QuestionID sent to update_question.php");}


//Generate topic list for drop down menu
$topics_query = "SELECT TopicID, Name FROM TOPICS ORDER BY Name;";
$topics_result = mysql_query($topics_query) or die(mysql_error());
$topics_found = mysql_num_rows($topics_result);

//text, ans, score, topic
if($_GET['QText'] != "") { $QText = $_GET['QText'];}
if($_GET['Ans'] != "") { $Ans = $_GET['Ans'];}
if($_GET['Score'] != "") { $Score = $_GET['Score'];}
if($_GET['QTopic'] != "") { $QTopic = $_GET['QTopic'];}

if(isset($QText) && isset($Ans) && isset($Score) && isset($QTopic)) {
	//Update query for TF questions
	if ($tf_check) {
		$update_question_query = "UPDATE QUESTIONS SET Score='".$Score."', QText='".$QText."', QTopic='".$QTopic."' WHERE QuestionID='".$QuestionID."';";
		$update_question_result = mysql_query($update_question_query) or die(mysql_error());
		$update_tf_question_query = "UPDATE TF_QUESTIONS SET Ans='".$Ans."' WHERE QuestionID='".$QuestionID."';";
		$update_tf_question_result = mysql_query($update_tf_question_query) or die(mysql_error());
		header("Location: ".$questions_url);
		
	} else {
	//Update query for MC questions
		$update_question_query = "UPDATE QUESTIONS SET Score='".$Score."', QText='".$QText."', QTopic='".$QTopic."' WHERE QuestionID='".$QuestionID."';";
		$update_question_result = mysql_query($update_question_query) or die(mysql_error());
		$update_mc_question_query = "UPDATE MC_QUESTIONS SET Ans='".$Ans."' WHERE QuestionID='".$QuestionID."';";
		$update_mc_question_result = mysql_query($update_mc_question_query) or die(mysql_error());
		header("Location: ".$update_url.$QuestionID);	
			
	}
} else if (isset($QText) || isset($Ans) || isset($Score) || isset($QTopic)) {$error = "Please complete entire form.";}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Management System - Update Question</title>
</head>

<body>

<h1>Test Management System - Update Question</h1>
<?php if (($_GET['error'] != "") || $error != "") {echo "<p color='red'>There was an error adding the topic.</p>".$_GET['error']."<br>".$error;} ?>

<?php
//////////////////////TF QUESTIONS//////////////////////////
if ($tf_check) {
?>

	<h2>Update TF Question</h2>
	<form name="update_form" method="get" action="<?php echo $update_url;?>">
	  <p>
		<label>Question Text</label>
		<br />
		<textarea name="QText" cols="40" rows="4"><?php echo $question_array['QText'];?></textarea>
		<br/>
		<br>
		<label>Answer</label><br>
		<input type="radio" name="Ans" value="1" <?php if($question_array['Ans']){echo "checked";}?>>True<br>
		<input type="radio" name="Ans" value="0" <?php if($question_array['Ans']==0){echo "checked";}?>>False<br>
		<br/>
		<label>Score
		<input type="text" name="Score" value="<?php echo $question_array['Score'];?>" size="3">
		</label>
		<br/>
		<label>Select Topic
		<select name="QTopic">
		<?php while($row = mysql_fetch_array($topics_result))
			  {?>
		  		<option value="<?php echo $row['TopicID']; ?>" <?php if($question_array['QTopic'] == $row['TopicID']){echo "SELECTED";}?>><?php echo $row['Name']; ?></option>
		<?php } ?>
		</select>
		</label>
	  </p>
	  <p>
		<label>
		<input type="submit" value="Save Changes">
		</label>
			<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
			<input type="hidden" name="TopicID" value="<?php echo $TopicID; ?>" />
			<input type="hidden" name="QuestionID" value="<?php echo $QuestionID; ?>" />
			<input type="hidden" name="TestID" value="<?php echo $TestID; ?>" />
			<input type="hidden" name="CRN" value="<?php echo $CRN; ?>" />
	  </p>
	</form>

<?php 
} else {
/////////////////////////MC QUESTIONS/////////////////////////// ?>

	<h2>Update MC Question</h2>
	<form name="update_form" method="get" action="<?php echo $update_url;?>">
	  <p>
		<label>Question Text</label>
		<br />
		<textarea name="QText" cols="40" rows="4"><?php echo $question_array['QText'];?></textarea>
		<br/>
		<br>
		<label>Correct Answer
		<input type="test" name="Ans" value="<?php echo $question_array['Ans'];?>">
		</label>
		<br/>
		<label>Score
		<input type="text" name="Score" value="<?php echo $question_array['Score'];?>" size="3">
		</label>
		<br/>
		<label>Select Topic
		<select name="QTopic">
		<?php while($row = mysql_fetch_array($topics_result))
			  {?>
		  		<option value="<?php echo $row['TopicID']; ?>" <?php if($question_array['QTopic'] == $row['TopicID']){echo "SELECTED";}?>><?php echo $row['Name']; ?></option>
		<?php } ?>
		</select>
		</label>
	  </p>
	  <p>
		<label>
		<input type="submit" value="Save Changes">
		</label>
			<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
			<input type="hidden" name="TopicID" value="<?php echo $TopicID; ?>" />
			<input type="hidden" name="QuestionID" value="<?php echo $QuestionID; ?>" />
			<input type="hidden" name="TestID" value="<?php echo $TestID; ?>" />
			<input type="hidden" name="CRN" value="<?php echo $CRN; ?>" />
	  </p>
	</form>
	
	<?php if($wrong_ans_found){ ?>
	<h2>Wrong Answers</h2>
	<p>
	<table border="0" cellspacing="2">
	  <tr>
		<th scope="col">WrongAnsID</th>
		<th scope="col">Answer Text</th>
	  </tr>
	  <?php
	  
	  while($row = mysql_fetch_array($wrong_ans_result))
		  { ?>
		  <tr align='center'>
		  <td><?php echo $row['WrongAnsID'];?></td>
		  <td><?php echo $row['WrongAns'];?></td>
		  <td><a href="<?php echo $update_wrong_ans_url;?>&WrongAnsID=<?php echo $row['WrongAnsID'];?>">Edit</a></td>
		  <td><a href="<?php echo $remove_wrong_ans_url;?>&WrongAnsID=<?php echo $row['WrongAnsID'];?>">Remove</a></td>
	  </tr>
<?php } ?>

	</table>
	</p>
	<?php } else {echo "No wrong answers found. Add using the form below.<br>";} ?>
		
	<h2>Add Wrong Answer</h2>
	<form name="add_form" method="get" action="<?php echo $add_wrong_ans_url;?>">
	  <p>
		<label>Wrong Answer Text
		<input type="text" name="ans_text" size="50">
		</label>
	  </p>
	  <p>
		<label>
		<input type="submit" value="Add Wrong Answer">
		</label>
		
			<input type="hidden" name="InsID" value="<?php echo $InsID; ?>" />
			<input type="hidden" name="TopicID" value="<?php echo $TopicID; ?>" />
			<input type="hidden" name="QuestionID" value="<?php echo $QuestionID; ?>" />
			<input type="hidden" name="TestID" value="<?php echo $TestID; ?>" />
			<input type="hidden" name="CRN" value="<?php echo $CRN; ?>" />
		
	  </p>
	</form>


<?php } ///////////END OF QTYPE IF////////////////////?>

<?php if (isset($TestID) && isset($CRN)){ ?>
<a href="<?php echo $questions_url;?>">Go Back to Test</a><br>
<?php } ?>

<a href="<?php echo $questions_url;?>">Go to Questions</a>

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
