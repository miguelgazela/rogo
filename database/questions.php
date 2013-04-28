<?php
    include_once($BASE_PATH . 'common/DatabaseException.php');

    function insertQuestion($title, $details, $anonymously) {
        global $db;
        $errors = new DatabaseException();
        $postid;
        $followableid;

        if(strlen($title) < 15) {
            $errors->addError('question_title', 'insufficient_length');
        }
        if(strlen($details) < 30) {
            $errors->addError('question_details', 'insufficient_length');
        }

        if($errors->hasErrors()) {
            throw ($errors);
        }

        // insert a new post and get its id
        // TODO anonymously not taken in consideration yet
        try {
            $stmt = $db->prepare("INSERT INTO post (title, body, creationdate, lastactivitydate, lasteditdate, commentcount, score, lasteditorid, ownerid) VALUES (?, ?, now(), now(), now(), 0, 0, ?, ?)");
            $stmt->execute(array($title, $details, $_SESSION['s_userid'], $_SESSION['s_userid'])); 
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

        $result = $db->query("SELECT question.*, post.*, rogouser.userid, username, reputation FROM question, post, rogouser WHERE questionid = post.postid AND post.ownerid = rogouser.userid ORDER BY post.creationdate DESC");
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
?>