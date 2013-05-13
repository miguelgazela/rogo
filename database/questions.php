<?php
    include_once($BASE_PATH . 'common/DatabaseException.php');

    function insertQuestion($title, $details, $anonymously) {
        global $db;
        $errors = new DatabaseException();
        $postid;
        $followableid;

        if(!validateQuestionTitle($title)) {
            $errors->addError('question_title', 'insufficient_length');
        }
        if(!validateQuestionDetails($details)) {
            $errors->addError('question_details', 'insufficient_length');
        }

        if($errors->hasErrors()) {
            throw ($errors);
        }

        // insert a new post and get its id
        try {
            $stmt = $db->prepare("INSERT INTO post (title, body, creationdate, lastactivitydate, lasteditdate, commentcount, score, lasteditorid, ownerid) VALUES (?, ?, now(), now(), now(), 0, 0, ?, ?)");
            $stmt->execute(array($title, $details, $_SESSION['s_user_id'], $_SESSION['s_user_id']));
            $postid = $db->lastInsertId('post_postid_seq');
        } catch(Exception $e) {
            $errors->addError('post', 'error processing insert into post table');
            throw ($errors);
        }

        // insert a new followable and get its id
        try {
            $stmt = $db->prepare("INSERT INTO followable (type) VALUES (2)");
            $stmt->execute();
            $followableid = $db->lastInsertId('followable_followableid_seq');
        } catch (Exception $e) {
            $errors->addError('followable', 'error processing insert into followable table');
            throw ($errors);
        }

        // insert the new question
        try {
            $stmt = $db->prepare("INSERT INTO question (questionid, followableid, viewcount, answercount) VALUES (?, ?, 0, 0)");
            $stmt->execute(array($postid, $followableid));
        } catch(Exception $e) {
            $errors->addError('question', 'error processing insert into question table');
            throw ($errors);
        }
        return $postid;
    }

    function getQuestionsWithSorting($sort) {
        global $db;

        $result = $db->query("SELECT question.*, post.*, username, reputation FROM question, post, rogouser WHERE questionid = post.postid AND post.ownerid = rogouser.userid ORDER BY post.creationdate DESC");

        return $result->fetchAll();

        /*
        switch ($sort) {
            case 'newest':
                # code...
                break;
            case 'votes':
                # code
                break;
            case 'active':
                # code
                break;
            case 'unanswered':
                # code
                break;
            default:
                throw new Exception("getQuestionsWithSorting: Invalid sorting");
                break;
        }
        */
    }

    function getQuestionById($id) {
        global $db;
        if(!is_numeric($id)) {
            throw new Exception("invalid_id");
        }

        $stmt = $db->prepare("SELECT question.*, post.*, username, reputation FROM question, post, rogouser WHERE questionid = postid AND post.ownerid = rogouser.userid AND questionid = ?");
        $stmt->execute(array($id));
        return $stmt->fetch();
    }

    function incQuestionViews($id) {
        global $db;
        if(!is_numeric($id)) {
            throw new Exception("invalid_id");
        }

        $stmt = $db->prepare("UPDATE question SET viewcount = (SELECT viewcount FROM question WHERE questionid = ?) + 1 WHERE questionid = ?");
        $stmt->execute(array($id, $id));
    }

    /* HELPER FUNCTIONS */

    function validateQuestionTitle($title) {
        if(strlen($title) < 15) {
            return false;
        }
        return true;
    }

    function validateQuestionDetails($details) {
        if(strlen($details) < 30) {
            return false;
        }
        return true;
    }
?>