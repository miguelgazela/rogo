<?php
    // initialize
    include_once('../../common/init.php');

    if(isset($_SESSION['s_username'])) {
        header("Location: $BASE_URL"."index.php");
    }
    $smarty->display('auth/signin.tpl');
?>