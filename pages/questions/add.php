<?php
    // initialize
    include_once('../../common/init.php');

    // display smarty template
    if(isset($_SESSION['s_username'])) {
        $smarty->display('questions/add.tpl');
    } else {
        $smarty->display('haveToLogin.tpl');
    }
?>
