<?php
	include_once('init.php');
	include_once($BASE_PATH . 'database/questions.php');

	// "2013-05-17 23:20:52.580226"
	$questions = getQuestionsWithSorting('newest');

	//echo(json_encode($questions));

	$questions[0]['creationdate'] = getPrettyDate($questions[0]['creationdate']);

	echo(json_encode($questions));
	
?>