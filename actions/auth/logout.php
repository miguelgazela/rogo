<?php
    include_once('../../common/init.php');

    session_destroy();
    session_start();
    $_SESSION['s_ok'] = 'Logged Out';

    header("Location: $BASE_URL"."index.php");
?>
