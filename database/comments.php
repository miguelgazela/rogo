<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'common/DatabaseException.php');

    function insertComment($postid, $text) {
        global $db;
        $errors = new DatabaseException();
        $commentId;

        if(!validateCommentText($text)) {
            $errors->addError('comment_body', 'insufficient_length');
        }
        if($errors->hasErrors()) {
            throw ($errors);
        }

        // insert a new notifiable and get its id
        try {
            $stmt = $db->prepare("INSERT INTO notifiable (type) VALUES (3)");
            $stmt->execute();
            $commentId = $db->lastInsertId('notifiable_notifiableid_seq');
        } catch (Exception $e) {
            $errors->addError('notifiable', 'error processing insert into notifiable table');
            throw ($errors);
        }

        // insert the new comment
        try {
            $stmt = $db->prepare("INSERT INTO comment (commentid, creationdate, body, score, ownerid, postid) VALUES (?, now(), ?, 0, ?, ?)");
            $stmt->execute(array($commentId, $text, $_SESSION['s_user_id'], $postid));
        } catch(Exception $e) {
            $errors->addError('comment', 'error processing insert into comment table');
            throw ($errors);
        }
        return $commentId;
    }

    function getCommentsOfPost($postid) {
        global $db;
        $result = $db->prepare("SELECT comment.*, username FROM comment, rogouser WHERE comment.postid = ? AND userid = ownerid ORDER BY creationdate");
        $result->execute(array($postid));
        return $result->fetchAll();
    }

    function removeComment($commentid) {
        global $db;
        $errors = new DatabaseException();

        $db->beginTransaction();

        // delete comment
        try {
            $stmt = $db->prepare("DELETE FROM comment WHERE commentid = ?");
            $stmt->execute(array($commentid));
        } catch(Exception $e) {
            $db->rollBack();
            $errors->addError('vote', 'error processing delete from vote table');
            throw ($errors);
        }

        // delete notifiable
        try {
            $stmt = $db->prepare("DELETE FROM notifiable WHERE notifiableid = ?");
            $stmt->execute(array($commentid));
        } catch(Exception $e) {
            $db->rollBack();
            $errors->addError('notifiable', 'error processing delete from notifiable table');
            throw ($errors);
        }
        $db->commit();
    }

    function validateCommentText($text) {
        if(strlen($text) < 15) {
            return false;
        }
        return true;
    }
?>
