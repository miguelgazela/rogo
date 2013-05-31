<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'common/DatabaseException.php');

    function insertAnswer($questionid, $text, $title, $draft) {
        global $db;
        $errors = new DatabaseException();
        $postid;

        if(!validateAnswerText($text)) {
            $errors->addError('answer_body', 'insufficient_length');
        }
        if($errors->hasErrors()) {
            throw ($errors);
        }

        // insert a new notifiable and get its id
        try {
            $stmt = $db->prepare("INSERT INTO notifiable (type) VALUES (1)");
            $stmt->execute();
            $postid = $db->lastInsertId('notifiable_notifiableid_seq');
        } catch (Exception $e) {
            $errors->addError('notifiable', 'error processing insert into notifiable table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }

        // insert the new post
        try {
            $stmt = $db->prepare("INSERT INTO post (postid, title, body, creationdate, lastactivitydate, lasteditdate, commentcount, score, lasteditorid, ownerid) VALUES (?, ?, ?, now(), now(), now(), 0, 0, ?, ?)");
            $stmt->execute(array($postid, $title, $text, $_SESSION['s_user_id'], $_SESSION['s_user_id']));
        } catch(Exception $e) {
            $errors->addError('post', 'error processing insert into post table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }

        // insert the new answer
        try {
            $stmt = $db->prepare("INSERT INTO answer (answerid, questionid, draft) VALUES (?, ?, ?)");
            $stmt->execute(array($postid, $questionid, $draft));
        } catch(Exception $e) {
            $errors->addError('question', 'error processing insert into question table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
        return $postid;
    }

    function getAnswerByUser($postid) {
        global $db;

        if(!is_numeric($postid)) {
            throw new Exception("invalid_id");
        }

        $stmt = $db->prepare("SELECT answerid, draft, ownerid FROM post, answer WHERE questionid = ? AND postid = answerid AND ownerid = ?");
        $stmt->execute(array($postid, $_SESSION['s_user_id']));
        return $stmt->fetch();
    }

    function updateAnswerDraft($answerid, $text) {
        global $db;
        $errors = new DatabaseException();

        if(!is_numeric($answerid)) {
            throw new Exception("invalid_id");
        }

        try {
            $stmt = $db->prepare("UPDATE post SET body = ? WHERE postid = ? AND post.ownerid = ?");
            $stmt->execute(array($text, $answerid, $_SESSION['s_user_id']));
        } catch(Exception $e) {
            $errors->addError('answer', 'error processing update on answer table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
    }

    function getAnswerDraft($questionid) {
        global $db;
        if(!is_numeric($questionid)) {
            throw new Exception("invalid_id");
        }

        $stmt = $db->prepare("SELECT post.body FROM answer, post, rogouser WHERE answerid = postid AND post.ownerid = rogouser.userid AND questionid = ? AND ownerid = ? AND draft = true");
        $stmt->execute(array($questionid, $_SESSION['s_user_id']));
        return $stmt->fetch();
    }

    function setDraftAsAnswer($answerid) {
        global $db;
        $errors = new DatabaseException();

        if(!is_numeric($answerid)) {
            throw new Exception("invalid_id");
        }

        try {
            $stmt = $db->prepare("UPDATE answer SET draft = false WHERE answerid = ?");
            $stmt->execute(array($answerid));
        } catch(Exception $e) {
            $errors->addError('answer', 'error processing update on answer table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
    }

    function getAnswerById($id) {
        global $db;
        if(!is_numeric($id)) {
            throw new Exception("invalid_id");
        }

        $stmt = $db->prepare("SELECT answer.*, post.*, username, reputation FROM answer, post, rogouser WHERE answerid = postid AND post.ownerid = rogouser.userid AND answerid = ?");
        $stmt->execute(array($id));
        return $stmt->fetch();
    }

    function getAnswersOfQuestion($questionid) {
        global $db;
        $result = $db->prepare("SELECT post.*, answer.accepted, rogouser.username, rogouser.reputation FROM answer, post, rogouser WHERE answerid = postid AND questionid = ? AND ownerid = userid AND draft = false ORDER BY score DESC, lastactivitydate DESC");
        $result->execute(array($questionid));
        return $result->fetchAll();
    }

    function removeAnswer($answerid) {
        global $db;
        $errors = new DatabaseException();

        $db->beginTransaction();

        // delete answer
        try {
            $stmt = $db->prepare("DELETE FROM answer WHERE answerid = ?");
            $stmt->execute(array($answerid));
        } catch(Exception $e) {
            $db->rollBack();
            $errors->addError('vote', 'error processing delete from answer table');
            $errors->addError("database_errors", $e);
            throw ($errors);
        }

        // delete post
        try {
            $stmt = $db->prepare("DELETE FROM post WHERE postid = ? AND ownerid = ? AND commentcount = 0"); // TODO doesn't check if user has extra permission
            $stmt->execute(array($answerid, $_SESSION['s_user_id']));
        } catch(Exception $e) {
            $db->rollBack();
            $errors->addError('notifiable', 'error processing delete from post table');
            throw ($errors);
        }
        

        // delete notifiable
        try {
            $stmt = $db->prepare("DELETE FROM notifiable WHERE notifiableid = ?");
            $stmt->execute(array($answerid));
        } catch(Exception $e) {
            $db->rollBack();
            $errors->addError('notifiable', 'error processing delete from notifiable table');
            throw ($errors);
        }

        $db->commit();
    }

    function updateAnswerScore($postid, $diffScore) {
        global $db;
        $errors = new DatabaseException();

        if(!is_numeric($postid)) {
            $errors->addError('updateAnswerScore', 'invalid post id');
            throw ($errors);
        }
        if(!is_numeric($diffScore)) {
            $errors->addError('updateAnswerScore', 'invalid score type');
            throw ($errors);
        }

        $stmt = $db->prepare("SELECT * FROM answer WHERE answerid = ?");
        $stmt->execute(array($postid));
        if($stmt->fetch()) {
            try {
                $stmt = $db->prepare("UPDATE post SET score = (SELECT post.score FROM post WHERE postid = ?) + ? WHERE postid = ?");
                $stmt->execute(array($postid, $diffScore, $postid));
            } catch(Exception $e) {
                $errors->addError('answer', 'error processing update on answer score');
                $errors->addError('exception', $e->getMessage());
                throw ($errors);
            }
        }
    }

    function acceptedAsValidAnswer($answerid, $state) {
        global $db;
        $errors = new DatabaseException();

        if(!is_numeric($answerid)) {
            $errors->addError('updateAnswerAcceptedState', 'invalid answer id');
            throw ($errors);
        }

        try {
            if($state) {
                $stmt = $db->prepare("UPDATE answer SET accepted = true WHERE answerid = ?");
            } else {
                $stmt = $db->prepare("UPDATE answer SET accepted = false WHERE answerid = ?");
            }
            $stmt->execute(array($answerid));
        } catch(Exception $e) {
            $errors->addError('answer', 'error processing update on answer accepted state');
            $errors->addError('exception', $e->getMessage());
            $errors->addError('state', $state);
            throw ($errors);
        }
    }

    /* HELPER FUNCTIONS */

    function validateAnswerText($text) {
        if(strlen($text) > 20) {
            return true;
        }
        return false;
    }
?>
