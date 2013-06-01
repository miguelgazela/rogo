<?php
	include_once($BASE_PATH . 'common/DatabaseException.php');

	function getPrivateMessages($userid){
		global $db;
        $stmt = $db->prepare("SELECT usermessage.*, rogouser.username, rogouser.email FROM usermessage, rogouser WHERE receiverid = ? AND userid = senderid ORDER BY creationdate DESC");
		$stmt->execute(array($userid));
        return $stmt->fetchAll();
	}
	
	function getPrivateMessage($msgid, $userReceiverID){
		global $db;
        $stmt = $db->prepare("SELECT * FROM usermessage WHERE usermsgid = ? AND receiverid = ?");
		$stmt->execute(array($msgid, $userReceiverID));
        return $stmt->fetch();
	}
	
	function messageRead($msgid, $userReceiverID){
		global $db;
        $stmt = $db->prepare("SELECT * FROM usermessage WHERE usermsgid = ? AND receiverid = ?");
		$stmt = $db->prepare("UPDATE usermessage SET read = TRUE WHERE usermsgid = ? AND receiverid = ?");
		$stmt->execute(array($msgid, $userReceiverID));
	}
	
?>
