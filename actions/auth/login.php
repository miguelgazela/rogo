<?php
    include_once('../../common/init.php');

    include_once($BASE_PATH . 'database/users.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Never store passwords in clear text!
    $userInfo = getUserInfoByLogin($username, sha1($password));

    if ($userInfo['result'] == "OK") {
        updateLastAccess($username);
        $_SESSION['s_username'] = $username;
        $_SESSION['s_user_permission'] = $userInfo['user']['permissiontype'];
        $_SESSION['s_user_id'] = $userInfo['user']['userid'];
        $_SESSION['s_ok'] = "Login Ok";
        $_SESSION['s_reputation'] = $userInfo['user']['reputation'];
        header("Location: $BASE_URL"."index.php");
        exit;
    } else {
        $_SESSION['s_error']['login'] = "Wrong username or password";
        $_SESSION['s_values'] = $_POST;
        header("Location: $BASE_URL"."pages/auth/signin.php");
        exit;
    }
?>
