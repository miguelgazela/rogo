<?php
    // initialize
    include_once('../../common/init.php');
	
	// include needed database functions
    include_once($BASE_PATH . 'database/privmessages.php');
	
    if(isset($_SESSION['s_username'])) {
        $pmid = $_GET['id'];
    
        // fetch data
        try {
            $pm = getPrivateMessage($pmid);
            if(!$pm) {
                $smarty->assign('warning_msg', "We don't have any private message with that id");
                $smarty->display("showWarning.tpl");
                exit;
            }
        } catch(Exception $e) {
            $smarty->assign('warning_msg', "He need a valid private message id to show you something useful");
            $smarty->display("showWarning.tpl");
            exit();
        }
        
        // check if the user is the owner of the private message
        if($pm['receiverid'] != $_SESSION['s_user_id']) {
            $smarty->assign('warning_msg', "That's not your private message! Why are you trying to see it?");
            $smarty->display("showWarning.tpl");
            exit;
        }

        markAsRead($pmid);
        $pm['subject'] = htmlspecialchars(stripslashes($pm['subject']));
        $pm['body'] = nl2br(htmlspecialchars(stripslashes($pm['body'])));
        $pm['creationdate_p'] = getPrettyDate($pm['creationdate']);
        $pm['gravatar'] = "http://www.gravatar.com/avatar/".md5(strtolower(trim($pm['email'])))."?s=50&r=pg&d=identicon";

        $_SESSION['s_values']['subject'] = "FWD: ".htmlspecialchars(stripslashes($pm['subject']));
        
        // send data to smarty and display template
        $smarty->assign('pm', $pm);
        $smarty->display("privmessages/view.tpl");
    } else {
        $smarty->assign('warning_msg', "You have to <a href='{$BASE_URL}pages/auth/signin.php'>log in</a> to get access");
        $smarty->display('showWarning.tpl');
    }
?>
