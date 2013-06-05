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

    function getNumberOfQuestionsWithSorting($sort) {
        global $db;
        $query = "SELECT count(*) AS total FROM question, post, rogouser WHERE questionid = post.postid AND post.ownerid = rogouser.userid";
        $now = date('Y-m-d', time()-1296000); // current date minus 15 days

        switch ($sort) {
            case 'newest':
                $query = $query." AND creationdate > ?";
                break;
            case 'unanswered':
                $query = $query." AND answercount = 0";
                break;
            case 'votes':
            case 'active':
                break;
            default:
                throw new Exception("getQuestionsWithSorting: Invalid sorting");
                break;
        }
        $stmt = $db->prepare($query);
        if($sort == 'newest')
            $stmt->execute(array($now));
        else
            $stmt->execute();
        return $stmt->fetch();
    }

    function getQuestionsWithSorting($sort, $limit, $offset) {
        global $db;
        $query = "SELECT question.*, post.*, username, email, reputation FROM question, post, rogouser WHERE questionid = post.postid AND post.ownerid = rogouser.userid ";
        $now = date('Y-m-d', time()-1296000); // current date minus 15 days

        switch ($sort) {
            case 'newest':
                $query = $query."AND creationdate > ? ORDER BY post.creationdate DESC";
                break;
            case 'votes':
                $query = $query."ORDER BY post.score DESC, answercount DESC";
                break;
            case 'active':
                $query = $query."ORDER BY post.lastactivitydate DESC";
                break;
            case 'unanswered':
                $query = $query."AND answercount = 0 ORDER BY creationdate DESC";
                break;
            default:
                throw new Exception("getQuestionsWithSorting: Invalid sorting");
                break;
        }

        if($limit !== null && $offset !== null) {
            $query = $query." LIMIT ? OFFSET ?";
        }

        $stmt = $db->prepare($query);

        if($limit !== null && $offset !== null) {
            if($sort == 'newest')
                $stmt->execute(array($now, $limit, $offset));
            else
                $stmt->execute(array($limit, $offset));
        } else {
            if($sort == 'newest')
                $stmt->execute(array($now));
            else
                $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    function getQuestionsCount($unanswered) {
        global $db;

        if($unanswered != null) {
            $query = "SELECT COUNT(*) AS num FROM question";
        } else {
            $query = "SELECT COUNT(*) AS num FROM question WHERE answercount = 0";
        }

        $result = $db->query($query);
        return $result->fetch();
    }

    function getQuestionById($id) {
        global $db;
        if(!is_numeric($id)) {
            throw new Exception("invalid_id");
        }

        $stmt = $db->prepare("SELECT question.*, post.*, username, email, reputation FROM question, post, rogouser WHERE questionid = postid AND post.ownerid = rogouser.userid AND questionid = ?");
        $stmt->execute(array($id));
        return $stmt->fetch();
    }

    function incNumAnswers($id) {
        global $db;
        $errors = new DatabaseException();

        if(!is_numeric($id)) {
            throw new Exception("invalid_id");
        }

        try {
            $stmt = $db->prepare("UPDATE question SET answercount = (SELECT answercount FROM question WHERE questionid = ?) + 1 WHERE questionid = ?");
            $stmt->execute(array($id, $id));
        } catch(Exception $e) {
            $errors->addError('question', 'error processing update on question table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
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

    function updateQuestion($id, $title, $details) {
        global $db;
        $errors = new DatabaseException();

        if(!is_numeric($id)) {
            throw new Exception("invalid_id");
        }

        try {
            $stmt = $db->prepare("UPDATE post SET title = ?, body = ?, lasteditorid = ?, lastactivitydate = now(), lasteditdate = now() WHERE postid = ?");
            $stmt->execute(array($title, $details, $_SESSION['s_user_id'], $id));
        } catch(Exception $e) {
            $errors->addError('question', 'error processing update on question table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
    }

    function acceptValidAnswer($questionid, $answerid) {
        global $db;
        $errors = new DatabaseException();

        if(!is_numeric($answerid)) {
            $errors->addError('acceptValidAnswer', 'invalid answer id');
            throw ($errors);
        }
        if(!is_numeric($questionid)) {
            $errors->addError('acceptValidAnswer', 'invalid question id');
            throw ($errors);
        }

        try {
            $stmt = $db->prepare("UPDATE question SET acceptedanswerid = ? WHERE questionid = ?");
            $stmt->execute(array($answerid, $questionid));
        } catch(Exception $e) {
            $errors->addError('answer', 'error processing update on accept valid answer');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
    }

    function removeAcceptedAnswer($questionid) {
        global $db;
        $errors = new DatabaseException();

        if(!is_numeric($questionid)) {
            $errors->addError('removeValidAnswer', 'invalid question id');
            throw ($errors);
        }

        try {
            $stmt = $db->prepare("UPDATE question SET acceptedanswerid = ? WHERE questionid = ?");
            $stmt->execute(array(null, $questionid));
        } catch(Exception $e) {
            $errors->addError('answer', 'error processing update on remove valid answer');
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

    function getQuestionsOfUser($userid, $limit) {
        global $db;
        if(!is_numeric($userid) || !is_numeric($limit)) {
            throw new Exception("invalid_id");
        }

        try {
            $stmt = $db->prepare("SELECT question.*, post.*, username, email, reputation FROM question, post, rogouser WHERE questionid = postid AND post.ownerid = rogouser.userid AND ownerid = ? ORDER BY lastactivitydate LIMIT ?");
            $stmt->execute(array($userid, $limit));
            return $stmt->fetchAll();
        } catch(Exception $e) {
            $errors->addError('question', 'error processing select on question table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
    }

    function getSearchQuestions($word) {
        global $db;

        try {
            $stmt = $db->prepare("SELECT question.*, post.*, username, email, reputation FROM question, post, rogouser WHERE questionid = postid AND post.ownerid = rogouser.userid AND lower(title) LIKE lower(?) ORDER BY lastactivitydate");
            $stmt->execute(array("%$word%"));
            return $stmt->fetchAll();
        } catch(Exception $e) {
            $errors->addError('question', 'error processing select on question table');
            $errors->addError('exception', $e->getMessage());
            throw ($errors);
        }
    }

    function getNumberOfQuestionsOfUser($userid) {
        global $db;

        $stmt = $db->prepare("SELECT count(*) AS total FROM question, post, rogouser WHERE questionid = postid AND post.ownerid = rogouser.userid AND ownerid = ?");
        $stmt->execute(array($userid));
        return $stmt->fetch();
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