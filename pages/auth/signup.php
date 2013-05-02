<?php
    // initialize
    include_once('../../common/init.php');

    // display smarty template
    if(isset($_SESSION['s_username'])) {
        header("Location: $BASE_URL"."index.php");
    }
    $smarty->display('auth/signup.tpl');
?>