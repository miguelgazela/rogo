<?php
    // initialize
    include_once('../../common/init.php');

    include_once($BASE_PATH . 'common/DatabaseException.php');
    include_once($BASE_PATH . 'database/users.php');

    function validateUsername($username){
        if(preg_match('/^[A-Za-z0-9_ .]{4,20}$/', $username));
            return true;
        return false;
    }

    function validatePasswords($pass1, $pass2) {
        if(preg_match('/^[a-zA-Z0-9!@#$%^&*-_]{6,30}$/',$pass1) && ($pass1 == $pass2))
            return true;
        return false;
    }

    if(!isset($_SESSION['s_username'])) {

        if(!isset($_POST['username'])) {
            die('NO_USERNAME');
        }
        if(!isset($_POST['email'])) {
            die('NO_EMAIL');
        }
        if(!isset($_POST['pass1'])) {
            die('NO_PASSWORD');
        }
        if(!isset($_POST['pass2'])) {
            die('NO_PASSWORD_CONFIRMATION');
        }

        $newAccountAllowed = TRUE;
        $errors = new DatabaseException(); // TODO remove

        if(isset($_SESSION['s_last_account_created'])) {
            if((time() - $_SESSION['s_last_account_created']) < 30) { // at least 30 seconds between 2 new accounts request from same session
                $newAccountAllowed = FALSE;
            }
        }

        if($newAccountAllowed == TRUE) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $pass1 = $_POST['pass1'];
            $pass2 = $_POST['pass2'];

            if(!validateUsername($username)) {
                $errors->addError('username', 'invalid');
            }
            if(!validatePasswords($pass1, $pass2)) {
                $errors->addError('password', 'invalid');
            }

            if($errors->hasErrors()) {
                throw ($errors);
            }

            /* NEEDS TO CHECK IF USERNAME OR EMAIL ARE ALREADY TAKEN */
            /*
            $errors->addError('username', 'taken');
            $_SESSION['s_error'] = $errors->getErrors();
            $_SESSION['s_values'] = $_POST;
            header("Location: ".$BASE_URL."pages/auth/signup.php");
            exit;
            */
            
            try {
                $id = insertUser($username, $email, sha1($pass1));
                $_SESSION['s_last_account_created'] = time();

                // logs in the user and redirects to main page
                $_SESSION['s_user_permission'] = 1;
                $_SESSION['s_username'] = $username;
                $_SESSION['s_email'] = $email;
                $_SESSION['s_user_id'] = $id;
                $_SESSION['s_ok'] = "Login Ok";
                header("Location: $BASE_URL"."index.php");
                exit;

            } catch (DatabaseException $e) {
                $_SESSION['s_error'] = $e->getErrors();
                $_SESSION['s_values'] = $_POST;
                header("Location: $BASE_URL" . "pages/auth/signup.php");
                exit;
            }

        } else {
            die('WAIT_AT_LEAST_30_SECONDS_BETWEEN_ACCOUNT_REGISTRATION');
        }
    } else {
        die('ALREADY_HAVE_ACCOUNT');
    }
?>