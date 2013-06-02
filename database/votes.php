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

        
        // insert a new notifiable and get its id
        try {
            $stmt = $db->prepare("INSERT INTO notifiable (type) VALUES (2)");
            $stmt->execute();
            $voteId = $db->lastInsertId('notifiable_notifiableid_seq');
        } catch (Exception $e) {
            $errors->addError('notifiable', 'error processing insert into notifiable table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }

        //insert the new vote
        try {
            $stmt = $db->prepare("INSERT INTO vote (voteid, creationdate, votetype, userid, votedid) VALUES (?, now(), ?, ?, ?)");
            $stmt->execute(array($voteId, $voteType, $_SESSION["s_user_id"], $postid));
        } catch(Exception $e) {
            $errors->addError('vote', 'error processing insert into vote table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
        return $voteId;
    }

    function getNumberOfVotes($id) {
        global $db;
        if(!is_numeric($id)) {
            throw new Exception("invalid_id");
        }

        $stmt = $db->prepare("SELECT count(*) as total FROM vote WHERE votedid = ?");
        $stmt->execute(array($id));
        return $stmt->fetch();
    }

    function updateVote($voteid, $voteType) {
        global $db;
        $errors = new DatabaseException();

        // update vote
        try {
            $stmt = $db->prepare("UPDATE vote SET votetype = ? WHERE voteid = ?");
            $stmt->execute(array($voteType, $voteid));
        } catch(Exception $e) {
            $errors->addError('vote', 'error processing update of vote table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
    }

    function removeVote($voteid) {
        global $db;
        $errors = new DatabaseException();

        // delete vote
        try {
            $stmt = $db->prepare("DELETE FROM vote WHERE voteid = ?");
            $stmt->execute(array($voteid));
        } catch(Exception $e) {
            $errors->addError('vote', 'error processing delete from vote table');
            throw ($errors);
        }

        // delete notifiable
        try {
            $stmt = $db->prepare("DELETE FROM notifiable WHERE notifiableid = ?");
            $stmt->execute(array($voteid));
        } catch(Exception $e) {
            $errors->addError('notifiable', 'error processing delete from notifiable table');
            throw ($errors);
        }
    }

    /**
     * [getVoteOfPost Returns a vote for a post from current user, if it exists]
     * @param  [type] $postid [id of the post]
     * @return [type]         [the vote if it exists or false otherwise]
     */
    function getVoteOfPost($postid) {
        global $db;
        if(!is_numeric($postid)) {
            throw new Exception("invalid_id");
        }

        $stmt = $db->prepare("SELECT * FROM vote WHERE votedid = ? AND userid = ?");
        $stmt->execute(array($postid, $_SESSION['s_user_id']));
        return $stmt->fetch();
    }

    function getVoteOfPostFromUser($postid, $userid) {
        
    }
?>
