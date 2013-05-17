<?php
    include_once($BASE_PATH . 'common/DatabaseException.php');

    function insertQuestion($title, $details) {
        global $db;
        $errors = new DatabaseException();
        $postId;
        $followableId;

        if(!validateQuestionTitle($title)) {
            $errors->addError('question_title', 'insufficient_length');
        }
        if(!validateQuestionDetails($details)) {
            $errors->addError('question_details', 'insufficient_length');
        }

        if($errors->hasErrors()) {
            throw ($errors);
        }

        // insert a new notifiable and get its id
        try {
            $stmt = $db->prepare("INSERT INTO notifiable (type) VALUES (1)");
            $stmt->execute();
            $postId = $db->lastInsertId('notifiable_notifiableid_seq');
        } catch (Exception $e) {
            $errors->addError('notifiable', 'error processing insert into notifiable table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }

        // insert the new post
        try {
            $stmt = $db->prepare("INSERT INTO post (postid, title, body, creationdate, lastactivitydate, lasteditdate, commentcount, score, lasteditorid, ownerid) VALUES (?, ?, ?, now(), now(), now(), 0, 0, ?, ?)");
            $stmt->execute(array($postId, $title, $details, $_SESSION['s_user_id'], $_SESSION['s_user_id']));
        } catch(Exception $e) {
            $errors->addError('post', 'error processing insert into post table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }

        // insert a new followable and get its id
        try {
            $stmt = $db->prepare("INSERT INTO followable (type) VALUES (2)");
            $stmt->execute();
            $followableId = $db->lastInsertId('followable_followableid_seq');
        } catch (Exception $e) {
            $errors->addError('followable', 'error processing insert into followable table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }

        // insert the new question
        try {
            $stmt = $db->prepare("INSERT INTO question (questionid, followableid, viewcount, answercount) VALUES (?, ?, 0, 0)");
            $stmt->execute(array($postId, $followableId));
        } catch(Exception $e) {
            $errors->addError('question', 'error processing insert into question table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
        return $postId;
    }

    function getQuestionsWithSorting($sort) {
        global $db;
        $query = "SELECT question.*, post.*, username, reputation FROM question, post, rogouser WHERE questionid = post.postid AND post.ownerid = rogouser.userid ";

        switch ($sort) {
            case 'newest':
                $query = $query."ORDER BY post.creationdate DESC";
                break;
            case 'votes':
                $query = $query."ORDER BY post.score DESC";
                break;
            case 'active':
                $query = $query."ORDER BY post.lastactivitydate DESC";
                break;
            case 'unanswered':
                $query = $query."AND answercount = 0 ORDER BY creationdate DESC;";
                break;
            default:
                throw new Exception("getQuestionsWithSorting: Invalid sorting");
                break;
        }
        
        $result = $db->query($query);
        return $result->fetchAll();
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
        $errors = new DatabaseException();

        if(!is_numeric($id)) {
            throw new Exception("invalid_id");
        }

        try {
            $stmt = $db->prepare("UPDATE question SET viewcount = (SELECT viewcount FROM question WHERE questionid = ?) + 1 WHERE questionid = ?");
            $stmt->execute(array($id, $id));
        } catch(Exception $e) {
            $errors->addError('question', 'error processing update on question table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
    }

    function updLastActivityDate($postid) {
        global $db;
        $errors = new DatabaseException();

        if(!is_numeric($postid)) {
            $errors->addError('updLastActivityDate', 'invalid post id');
            throw ($errors);
        }

        try {
            $stmt = $db->prepare("UPDATE post SET lastactivitydate = now() WHERE postid = ?");
            $stmt->execute(array($postid));
        } catch(Exception $e) {
            $errors->addError('question', 'error processing update on question table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
    }

    function updateQuestionScore($postid, $diffScore) {
        global $db;
        $errors = new DatabaseException();

        if(!is_numeric($postid)) {
            $errors->addError('updateQuestionScore', 'invalid post id');
            throw ($errors);
        }
        if(!is_numeric($diffScore)) {
            $errors->addError('updateQuestionScore', 'invalid score type');
            throw ($errors);
        }

        $stmt = $db->prepare("SELECT * FROM question WHERE questionid = ?");
        $stmt->execute(array($postid));
        if($stmt->fetch()) {
            try {
                $stmt = $db->prepare("UPDATE post SET score = (SELECT post.score FROM post WHERE postid = ?) + ? WHERE postid = ?");
                $stmt->execute(array($postid, $diffScore, $postid));
            } catch(Exception $e) {
                $errors->addError('question', 'error processing update on question score');
                $errors->addError('exception', $e->getMessage());
                throw ($errors);
            }
        }
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