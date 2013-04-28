<?php
    include_once('../../common/init.php');

    include_once($BASE_PATH . 'database/users.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Never store passwords in clear text!
    $userInfo = getUserInfoByLogin($username, sha1($password));

    if ($userInfo) {
        $_SESSION['s_username'] = $username;
        $_SESSION['s_user_permission'] = $userInfo['permissiontype'];
        $_SESSION['s_userid'] = $userInfo['userid'];
        $_SESSION['s_ok'] = "Login Ok";
        header("Location: $BASE_URL"."index.php");
        exit;
    } else {
        $_SESSION['s_error']['global'] = "Wrong username or password";
        header("Location: $BASE_URL"."pages/auth/signup.php");
        exit;
    }
?>
