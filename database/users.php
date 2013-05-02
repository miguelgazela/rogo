<?php
    include_once($BASE_PATH . 'common/DatabaseException.php');

    function insertUser($username, $email, $pass_hash) {
        global $db;
        $errors = new DatabaseException();
        $followableid;

        if(!validateUsername($username)) {
            $errors->addError('username', 'invalid');
        }

        $db->beginTransaction();
        try {
            $stmt = $db->prepare("INSERT INTO followable (type) VALUES (1)");
            $stmt->execute();
            $followableid = $db->lastInsertId('followable_followableid_seq');
        } catch (Exception $e) {
            $db->rollBack();
            $errors->addError('followable', 'error processing insert into followable table');
            throw ($errors);
        }

        try {
            $stmt = $db->prepare("INSERT INTO rogouser (userid, fullname, username, email, passhash, birthdate, registrationdate, lastaccess, location, reputation, credits, viewcount, downvotes, upvotes, permissiontype, websiteurl, aboutme, consecutiveaccessdays) VALUES ($followableid, 'Rogo', ?, ?, ?, now(), now(), now(), 'Earth', 0, 0, 0, 0, 0, 1, null, null, 1)");
            $stmt->execute(array($username, $email, $pass_hash));
        } catch (Exception $e) {
            $db->rollBack();
            $errors->addError('rogouser', 'error processing insert into rogouser table');
            throw ($errors);
        }
        $db->commit();
        return $followableid;
    }

    function getUserInfoByLogin($login, $pass_hash) {
        global $db;
        $response;

        $result = $db->prepare("SELECT permissiontype, userid FROM rogouser WHERE username = ? AND passhash = ?");
        $result->execute(array($login,$pass_hash));
        $user = $result->fetch();

        if($user) {
            $response['result'] = 'OK';
            $response['userid'] = $user['userid'];
            $response['permissiontype'] = $user['permissiontype'];
        } else {
            $response['result'] = 'NOK';
        }
        return $response;
    }

    function getUserByUsername($username) {
        global $db;
        $result = $db->prepare("SELECT * FROM rogouser WHERE username = ?");
        $result->execute(array($username));
        return $result->fetch();
    }

    function getUserByEmail($email) {
        global $db;
        $result = $db->prepare("SELECT * FROM rogouser WHERE email = ?");
        $result->execute(array($email));
        return $result->fetch();
    }

    function validateUsername($username){
        if(preg_match('/^[A-Za-z0-9_.]{4,20}$/', $username)) {
            return true;
        }
        return false;
    }

    function validateEmail($email) {
        if(preg_match('/^[[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $email)) {
            return true;
        }
        return false;
    }
?>