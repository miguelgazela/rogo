<?php
    // initialize
    include_once('../../common/init.php');

    include_once($BASE_PATH . 'common/DatabaseException.php');
    include_once($BASE_PATH . 'database/questions.php');

    function returnIfHasErrors($errors) {
        global $BASE_URL;
        if($errors->hasErrors()) {
            $_SESSION['s_error'] = $errors->getErrors();
            $_SESSION['s_values'] = $_POST;
            header("Location: $BASE_URL"."pages/questions/add.php");
            exit;
        }
    }

    if(isset($_SESSION['s_username'])) {

        $errors = new DatabaseException();

        if(!isset($_POST['question'])) {
            $errors->addError('question', 'no_questions');
        }
        if(!isset($_POST['details'])) {
            $errors->addError('details', 'no_details');
        }
        if(!isset($_POST['tags'])) {
            $errors->addError('tags', 'no_tags');
        }

        returnIfHasErrors($errors);

        $question = $_POST['question'];
        $details = $_POST['details'];
        $tags = $_POST['tags'];
        $anonymously = false;

        if(isset($_POST['anonymously'])) {
            $anonymously = true;
        }

        if(!validateQuestionTitle($question)) {
            $errors->addError('question', 'invalid');
        }
        if(!validateQuestionDetails($details)) {
            $errors->addError('details', 'invalid');
        }

        returnIfHasErrors($errors);

        try {
            $id = insertQuestion($question, $details, $anonymously, $tags);
            // redirects to question page
            header("Location: $BASE_URL"."pages/questions/view.php?id=".$id);
            exit;
        } catch (DatabaseException $e) {
            returnIfHasErrors($e);
        }

    }
?>
