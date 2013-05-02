<?php
    // initialize
    include_once('../../common/init.php');

    include_once($BASE_PATH . 'common/DatabaseException.php');
    include_once($BASE_PATH . 'database/users.php');

    function returnIfHasErrors($errors) {
        global $BASE_URL;
        if($errors->hasErrors()) {
            $_SESSION['s_error'] = $errors->getErrors();
            $_SESSION['s_values'] = $_POST;
            header("Location: $BASE_URL"."pages/auth/signup.php");
            exit;
        }
    }

    function validatePasswords($pass1, $pass2) {
        if(preg_match('/^[a-zA-Z0-9!@#$%^&*-_]{6,30}$/',$pass1) && ($pass1 == $pass2)) {
            return true;
        }
        return false;
    }

    if(!isset($_SESSION['s_username'])) {

        $newAccountAllowed = TRUE;
        $errors = new DatabaseException();

        if(!isset($_POST['username'])) {
            $errors->addError('username', 'no_username');
        }
        if(!isset($_POST['email'])) {
            $errors->addError('email', 'no_email');
        }
        if(!isset($_POST['pass1'])) {
            $errors->addError('password', 'no_password');
        }
        if(!isset($_POST['pass2'])) {
            $errors->addError('password', 'no_confirmation_password');
        }

        returnIfHasErrors($errors);

        if(isset($_SESSION['s_last_account_created'])) {
            if((time() - $_SESSION['s_last_account_created']) < 30) { // at least 30 seconds between 2 new accounts request from same session
                $newAccountAllowed = FALSE;
                $errors->addError('account', 'wait_30_seconds');
                returnIfHasErrors($errors);
            }
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $pass1 = $_POST['pass1'];
        $pass2 = $_POST['pass2'];

        // validate values
        if(!validateUsername($username)) {
            $errors->addError('username', 'invalid');
        }
        if(!validateEmail($email)) {
            $errors->addError('email', 'invalid');
        }
        if(!validatePasswords($pass1, $pass2)) {
            if($pass1 != $pass2) {
                $errors->addError('password', 'invalid');
            } else {
                $errors->addError('password', 'no_match');
            }
        }

        // check if login info already exists
        if(getUserByUsername($username)) {
            $errors->addError('username', 'username_taken');
        }
        if(getUserByEmail($email)) {
            $errors->addError('email', 'email_taken');
        }

        returnIfHasErrors($errors);
        
        // add the new user to the database
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
            returnIfHasErrors($e);
        }
    }
?>