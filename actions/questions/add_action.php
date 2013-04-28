<?php
    // initialize
    include_once('../../common/init.php');

    include_once($BASE_PATH . 'common/DatabaseException.php');
    include_once($BASE_PATH . 'database/questions.php');

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

    if(isset($_SESSION['s_username'])) {
        if(!isset($_POST['question'])) {
            die('NO_QUESTION'); // TODO
        }
        if(!isset($_POST['details'])) {
            die('NO_DETAILS'); // TODO
        }
        if(!isset($_POST['tags'])) {
            die('NO_TAGS'); // TODO 
        }

        $question = $_POST['question'];
        $details = $_POST['details'];
        $tags = $_POST['tags'];
        $anonymously = false;

        if(isset($_POST['anonymously'])) {
            $anonymously = true;
        }

        if(!validateQuestionTitle($question)) {
            die('invalid_question');
        }
        if(!validateQuestionDetails($details)){
            die('invalid_question_details');
        }

        try {
            $id = insertQuestion($question, $details, $anonymously);
            // redirects to question page
            header("Location: $BASE_URL"."pages/questions/view.php?id=".$id);
            exit;
        } catch (DatabaseException $e) {
            $_SESSION['s_error'] = $e->getErrors();
            $_SESSION['s_values'] = $_POST;
            header("Location: $BASE_URL" . "pages/questions/add.php");
            exit;
        }

    } else {
        die('YOU_HAVE_TO_LOG_IN_TO_ASK_QUESTION'); // TODO not this way
    }
?>
