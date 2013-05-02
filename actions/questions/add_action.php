<?php
    // initialize
    include_once('../../common/init.php');

    include_once($BASE_PATH . 'common/DatabaseException.php');
    include_once($BASE_PATH . 'database/questions.php');
    include_once($BASE_PATH . 'database/tags.php');

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

        // validate tags?
        returnIfHasErrors($errors);

        try {
            $db->beginTransaction();
            $questionid = insertQuestion($question, $details, $anonymously);
            $tags = explode(",", $tags);

            // insert the tags
            foreach($tags as $tagname) {

                $tag = getTagByName($tagname);

                if(!$tag) { // doesn't exist yet
                    try {
                        $tag['tagid'] = insertTag($tagname);
                    } catch(DatabaseException $e) {
                        $db->rollBack();
                        returnIfHasErrors($e);
                    }
                }

                // associate the tags with the question
                try {
                    addTagToQuestion($questionid, $tag['tagid']);
                } catch(DatabaseException $e) {
                    $db->rollBack();
                    returnIfHasErrors($e);
                }
            }

            // redirects to question page
            $db->commit();
            header("Location: $BASE_URL"."pages/questions/view.php?id=".$questionid);
            exit;
        } catch (DatabaseException $e) {
            $db->rollBack();
            returnIfHasErrors($e);
        }

    }
?>
