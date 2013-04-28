<?php
    include_once($BASE_PATH . 'common/DatabaseException.php');

    function insertUser($username, $email, $pass_hash) {
        global $db;
        $errors = new DatabaseException();
        $followableid;

        if(strlen($username) < 4) {
            $errors->addError('username', 'username >= 4');
            throw ($errors);
        }

        try {
            $stmt = $db->prepare("INSERT INTO followable (type) VALUES (1)");
            $stmt->execute();
            $followableid = $db->lastInsertId('followable_followableid_seq');
        } catch (Exception $e) {
            $errors->addError('followable', 'error processing insert into followable table');
            throw ($errors);
        }

        try {
            $stmt = $db->prepare("INSERT INTO rogouser (userid, fullname, username, email, passhash, birthdate, registrationdate, lastaccess, location, reputation, credits, viewcount, downvotes, upvotes, permissiontype, websiteurl, aboutme, consecutiveaccessdays) VALUES ($followableid, 'Rogo', ?, ?, ?, now(), now(), now(), 'Earth', 0, 0, 0, 0, 0, 1, null, null, 1)");
            $stmt->execute(array($username, $email, $pass_hash));
        } catch (Exception $e) {
            $errors->addError('rogouser', 'error processing insert into rogouser table');
            throw ($errors);
        }
        return $followableid;
    }

    function getUserInfoByLogin($login, $pass_hash) {
        global $db;
        $result = $db->prepare("SELECT permissiontype, userid FROM rogouser WHERE username = ? AND passhash = ?");
        $result->execute(array($login,$pass_hash));
        $user = $result->fetch();

        if($user) {
            $userInfo = array("userid"=>$user['userid'], "permissiontype"=>$user['permissiontype']);
            return $userInfo;
        } else {
            return false;
        }
    }
?>