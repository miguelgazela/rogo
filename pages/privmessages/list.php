<?
    // initialize
    include_once('../../common/init.php');
	
	// include needed database functions
    include_once($BASE_PATH . 'database/privmessages.php');

	if(isset($_SESSION['s_username'])) {		
		$privmessages = listPrivateMessages($_SESSION['s_user_id']);
		
		foreach($privmessages as &$privatemessage) {
			$privatemessage['creationdate'] = getPrettyDate($privatemessage['creationdate']);
		}

		// send data to smarty
		$smarty->assign('private_messages', $privmessages);
		$smarty->assign('number_private_messages', count($privmessages));

		// display smarty template
		$smarty->display('privmessages/list.tpl');
		
    } else {
        $smarty->assign('warning_msg', "You have to <a href='{$BASE_URL}pages/auth/signin.php'>log in</a> to get access");
        $smarty->display('showWarning.tpl');
    }
?>
