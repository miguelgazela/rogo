<?php
    // initialize
    include_once('../../common/init.php');
	
	// include needed database functions
    include_once($BASE_PATH . 'database/privmessages.php');

	if(isset($_SESSION['s_username'])) {		
		$privatemessages = getPrivateMessages($_SESSION['s_user_id']);
		$unread = 0;
		
		foreach($privatemessages as &$privatemessage) {
			$privatemessage['creationdate_p'] = getPrettyDate($privatemessage['creationdate']);
			$privatemessage['body'] = getSmallerText($privatemessage['body'], 50);
			if(!$privatemessage['read']) {
				$unread++;
			}
			$privatemessage['gravatar'] = "http://www.gravatar.com/avatar/".md5(strtolower(trim($privatemessage['email'])))."?s=40&r=pg&d=identicon";
		}

		// send data to smarty
		$smarty->assign('private_messages', $privatemessages);
		$smarty->assign('number_private_messages', count($privatemessages));
		$smarty->assign('number_unread_messages', $unread);

		// display smarty template
		$smarty->display('privmessages/list.tpl');
		
    } else {
        $smarty->assign('warning_msg', "You have to <a href='{$BASE_URL}pages/auth/signin.php'>log in</a> to get access");
        $smarty->display('showWarning.tpl');
    }
?>
