<?
	include_once($BASE_PATH . 'common/DatabaseException.php');

	function listPrivateMessages($userid){
		global $db;
        $stmt = $db->prepare("SELECT usermessage.*, rogouser.userid, rogouser.username FROM usermessage, rogouser WHERE receiverid = ? AND userid = senderid ORDER BY creationdate DESC");
		$stmt->execute(array($username));
        return $stmt->fetch();
	}
	
?>
