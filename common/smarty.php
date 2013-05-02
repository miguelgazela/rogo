<?php
    // Setup Smarty
    include_once($BASE_PATH . 'libs/Smarty/Smarty.class.php');
    $smarty = new Smarty;
    $smarty->setTemplateDir($BASE_PATH . "templates/");    
    $smarty->setCompileDir($BASE_PATH . "templates_c/");

    // Send some common variables to Smarty
    $smarty->assign("BASE_URL", $BASE_URL);

    // Send error messages to Smarty and delete them
    $smarty->assign("s_error", $_SESSION['s_error']);
    $_SESSION['s_error'] = null;
    
    // Send ok messages to Smarty and delete them
    $smarty->assign("s_ok", $_SESSION['s_ok']);
    $_SESSION['s_ok'] = null;

    // Send form values to Smarty and PHP and delete them
    $smarty->assign("s_values", $_SESSION['s_values']);
    $_values = $_SESSION['s_values'];
    $_SESSION['s_values'] = null;

    // Send session variables to Smarty
    $smarty->assign("s_username", $_SESSION['s_username']);
    $smarty->assign("s_user_id", $_SESSION['s_user_id']);
    $smarty->assign("s_user_permission", $_SESSION['s_user_permission']);
?>