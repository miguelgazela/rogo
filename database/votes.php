<?php
    include_once($BASE_PATH . 'common/DatabaseException.php');

    function insertVote($postid, $voteType) {
        global $db;
        $errors = new DatabaseException();
        $voteId;

        if($voteType != 1 && $voteType != 2) {
            $errors->addError('vote_type', 'invalid_vote_type');
        }
        if($errors->hasErrors()) {
            throw ($errors);
        }

        $db->beginTransaction();
        // insert a new notifiable and get its id
        try {
            $stmt = $db->prepare("INSERT INTO notifiable (type) VALUES (2)");
            $stmt->execute();
            $voteId = $db->lastInsertId('notifiable_notifiableid_seq');
        } catch (Exception $e) {
            $db->rollBack();
            $errors->addError('notifiable', 'error processing insert into notifiable table');
            throw ($errors);
        }

        //insert the new vote
        try {
            $stmt = $db->prepare("INSERT INTO vote (voteid, creationdate, votetype, userid, votedid) VALUES (?, now(), ?, ?, ?");
            $stmt->execute(array($voteId, $voteType, $_SESSION["s_user_id"], $postid));
        } catch(Exception $e) {
            $db->rollBack();
            $errors->addError('vote', 'error processing insert into vote table');
            throw ($errors);
        }
        $db->commit();
        return $voteId;
    }

    function getVoteOfPost($postid) {
        global $db;
        if(!is_numeric($postid)) {
            throw new Exception("invalid_id");
        }

        $stmt = $db->prepare("SELECT * FROM vote WHERE votedid = ? AND userid = ?");
        $stmt->execute(array($postid, $_SESSION['s_user_id']));
        return $stmt->fetch();
    }

?>
