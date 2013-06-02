<?php
	include_once($BASE_PATH . 'common/DatabaseException.php');

    function insertPrivateMessage($subject, $details, $receiverid) {
        global $db;
        $errors = new DatabaseException();

        // insert the new private message
        try {
            $stmt = $db->prepare("INSERT INTO usermessage (subject, body, senderid, receiverid, questionid) VALUES (?, ?, ?, ?, null)");
            $stmt->execute(array($subject, $details, $_SESSION['s_user_id'], $receiverid));
            return $db->lastInsertId('usermessage_usermsgid_seq');
        } catch(Exception $e) {
            $errors->addError('privmessage', 'error processing insert into usermessage table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
    }

	function getPrivateMessages($userid){
		global $db;
        $stmt = $db->prepare("SELECT usermessage.*, rogouser.username, rogouser.email FROM usermessage, rogouser WHERE receiverid = ? AND userid = senderid ORDER BY creationdate DESC");
		$stmt->execute(array($userid));
        return $stmt->fetchAll();
	}
	
	function getPrivateMessage($msgid){
		global $db;
        $stmt = $db->prepare("SELECT usermessage.*, rogouser.username, rogouser.email FROM usermessage, rogouser WHERE usermsgid = ? AND userid = senderid");
		$stmt->execute(array($msgid));
        return $stmt->fetch();
	}

	function removePM($id) {
		global $db;
        $errors = new DatabaseException();

        // delete pm
        try {
            $stmt = $db->prepare("DELETE FROM usermessage WHERE usermsgid = ?");
            $stmt->execute(array($id));
        } catch(Exception $e) {
            $db->rollBack();
            $errors->addError('usermessage', 'error processing delete from usermessage table');
            $errors->addError("database_errors", $e);
            throw ($errors);
        }
	}

	function markAsRead($id) {
		global $db;
		$stmt = $db->prepare("UPDATE usermessage SET read = TRUE WHERE usermsgid = ?");
		$stmt->execute(array($id));
	}

    function validateSubject($subject) {
    	if(strlen($subject) < 15) {
    		return false;
    	}
    	return true;
    }

    function validateMessageDetails($details) {
    	if(strlen($details) < 30) {
    		return false;
    	}
    	return true;
    }
?>
