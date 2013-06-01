<?php
    // initialize
    include_once('../../common/init.php');

    // display smarty template
    if(isset($_SESSION['s_username'])) {
        $smarty->display('privmessages/add.tpl');
    } else {
        $smarty->assign('warning_msg', "You have to <a href='{$BASE_URL}pages/auth/signin.php'>log in</a> to get access");
        $smarty->display('showWarning.tpl');
    }
?>
