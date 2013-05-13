<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'common/DatabaseException.php');

    function insertAnswer($questionid, $text, $title) {
        global $db;
        $errors = new DatabaseException();
        $postid;

        if(!validateAnswerText($text)) {
            $errors->addError('answer_body', 'insufficient_length');
        }
        if($errors->hasErrors()) {
            throw ($errors);
        }

        $db->beginTransaction();
        // insert a new post and get its id
        try {
            $stmt = $db->prepare("INSERT INTO post (title, body, creationdate, lastactivitydate, lasteditdate, commentcount, score, lasteditorid, ownerid) VALUES (?, ?, now(), now(), now(), 0, 0, ?, ?)");
            $stmt->execute(array($title, $text, $_SESSION['s_user_id'], $_SESSION['s_user_id']));
            $postid = $db->lastInsertId('post_postid_seq');
        } catch(Exception $e) {
            $db->rollBack();
            $errors->addError('post', 'error processing insert into post table');
            throw ($errors);
        }

        // insert the new answer
        try {
            $stmt = $db->prepare("INSERT INTO answer (answerid, questionid) VALUES (?, ?)");
            $stmt->execute(array($postid, $questionid));
        } catch(Exception $e) {
            $db->rollBack();
            $errors->addError('question', 'error processing insert into question table');
            throw ($errors);
        }
        $db->commit();
        return $postid;
    }

    function getAnswersOfQuestion($questionid) {
        global $db;
        $result = $db->prepare("SELECT post.*, rogouser.username, rogouser.reputation FROM answer, post, rogouser WHERE answerid = postid AND questionid = ? AND ownerid = userid");
        $result->execute(array($questionid));
        return $result->fetchAll();
    }


    /* HELPER FUNCTIONS */

    function validateAnswerText($text) {
        if(strlen($text) > 20) {
            return true;
        }
        return false;
    }
?>
