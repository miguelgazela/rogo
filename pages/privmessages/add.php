<?php
    // initialize
    include_once('../../common/init.php');

    // include needed database functions
    include_once($BASE_PATH . 'database/users.php');


    // display smarty template
    if(isset($_SESSION['s_username'])) {

        if(isset($_SESSION['message_sent']) && $_SESSION['message_sent'] == 'ok') {
            $smarty->assign('message_sent', "ok");
            unset($_SESSION['message_sent']);
        }

    	$receiver_id = $_GET['id'];

    	// fetch data
    	try {
    		$receiver = getUserById($receiver_id);
    		if(!$receiver) {
    			$smarty->assign('warning_msg', "We don't have any user with that id");
	            $smarty->display("showWarning.tpl");
	            exit;
    		}
			$receiver['gravatar'] =    "http://www.gravatar.com/avatar/".md5(strtolower(trim($question['email'])))."?s=50&r=pg&d=identicon";
			$smarty->assign("receiver", $receiver);
            $_SESSION['s_receiverid'] = $receiver['userid'];
			$smarty->display('privmessages/add.tpl');		
    	} catch(Exception $e) {
    		$smarty->assign('warning_msg', "He need a valid user id to show you something useful");
        	$smarty->display("showWarning.tpl");
        	exit;
    	}
    } else {
        $smarty->assign('warning_msg', "You have to <a href='{$BASE_URL}pages/auth/signin.php'>log in</a> to get access");
        $smarty->display('showWarning.tpl');
    }
?>
