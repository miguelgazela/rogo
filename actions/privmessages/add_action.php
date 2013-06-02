<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/privmessages.php');

    if(isset($_SESSION['s_username'])) {
    	$errors = new DatabaseException();

    	if(!isset($_POST['subject'])) {
    		$errors->addError('private_message', 'no_subject');
    	}
    	if(!isset($_POST['details'])) {
    		$errors->addError('private_message', 'no_details');
    	}

    	returnIfHasErrors($errors, "pages/privmessages/add.php?id=".$_SESSION['s_receiverid']);

    	$subject = $_POST['subject'];
    	$details = $_POST['details'];

    	if(!validateSubject($subject)) {
    		$errors->addError("private_message", "invalid_subject");
    	}
    	if(!validateMessageDetails($details)) {
    		$errors->addError("private_message", "invalid_details");
    	}

    	returnIfHasErrors($errors, "pages/privmessages/add.php?id=".$_SESSION['s_receiverid']);

    	try {
    		insertPrivateMessage($subject, $details, $_SESSION['s_receiverid']);
    		$_SESSION['message_sent'] = "ok";
    		header("Location: $BASE_URL"."pages/privmessages/add.php?id=".$_SESSION['s_receiverid']);
    	} catch(Exception $e) {
    		$errors->addError("DatabaseException", $e->getMessage());
    		returnIfHasErrors($errors, "pages/privmessages/add.php?id=".$_SESSION['s_receiverid']);
    	}
    } else {
    	echo "Access denied";
    }
?>
