<?php
    include_once($BASE_PATH . 'common/DatabaseException.php');
    //include_once($BASE_PATH . 'database/tags.php');

    function insertQuestion($title, $details, $anonymously) {
        //global $db;
        $errors = new DatabaseException();
        $postid;
        $followableid;

        if(!validateQuestion($title)) {
            $errors->addError('question_title', 'insufficient_length');
        }
        if(!validateDetails($details)) {
            $errors->addError('question_details', 'insufficient_length');
        }

        if($errors->hasErrors()) {
            throw ($errors);
        }

        // insert a new post and get its id
        // TODO anonymously not taken in consideration yet
        //$db->beginTransaction();
        try {
            $stmt = $db->prepare("INSERT INTO post (title, body, creationdate, lastactivitydate, lasteditdate, commentcount, score, lasteditorid, ownerid) VALUES (?, ?, now(), now(), now(), 0, 0, ?, ?)");
            $stmt->execute(array($title, $details, $_SESSION['s_userid'], $_SESSION['s_userid']));
            $postid = $db->lastInsertId('post_postid_seq');
        } catch(Exception $e) {
            //$db->rollBack();
            $errors->addError('post', 'error processing insert into post table');
            throw ($errors);
        }

        // insert a new followable and get its id
        try {
            $stmt = $db->prepare("INSERT INTO followable (type) VALUES (2)");
            $stmt->execute();
            $followableid = $db->lastInsertId('followable_followableid_seq');
        } catch (Exception $e) {
            //$db->rollBack();
            $errors->addError('followable', 'error processing insert into followable table');
            throw ($errors);
        }

        // insert the new question
        try {
            $stmt = $db->prepare("INSERT INTO question (questionid, followableid, viewcount, answercount) VALUES (?, ?, 0, 0)");
            $stmt->execute(array($postid, $followableid));
        } catch(Exception $e) {
            //$db->rollBack();
            $errors->addError('question', 'error processing insert into question table');
            throw ($errors);
        }

        //$db->commit();
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
            throw new Exception("INVALID_QUESTION_ID");
        }

        $stmt = $db->prepare("SELECT question.*, post.*, username, reputation FROM question, post, rogouser WHERE questionid = postid AND post.ownerid = rogouser.userid AND questionid = ?");
        $stmt->execute(array($id));
        $question = $stmt->fetch();
        if(!$question) {
            throw new Exception("NO_SUCH_QUESTION_WITH_ID");
        }
        return $question;
    }

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