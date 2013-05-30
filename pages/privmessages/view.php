<?
    // initialize
    include_once('../../common/init.php');
	
	// include needed database functions
    include_once($BASE_PATH . 'database/privmessages.php');
	
	$msgid = $_GET['id'];
	
	// fetch data
    try {
        $message = getPrivateMessage($msgid, $_SESSION['s_user_id']);
        if(!$message) {
            $smarty->assign('warning_msg', "We don't have any message with that id");
            $smarty->display("showWarning.tpl");
            exit();
        }
        messageRead($msgid, $message['receiverid']);
    } catch(Exception $e) {
        $smarty->assign('warning_msg', "He need a valid question id to show you something useful");
        $smarty->display("showWarning.tpl");
        exit();
    }
	
	// send data to smarty and display template
    $smarty->assign('subject', $message['subject']);
    $smarty->assign('body', $message['body']);
    $smarty->assign("creationdate", $message['creationdate']);
    $smarty->assign("sendername", $message['username']);

    $smarty->display("privmessages/view.tpl");
?>
