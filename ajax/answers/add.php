<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'common/DatabaseException.php');
    include_once($BASE_PATH . 'database/answers.php');
    include_once($BASE_PATH . 'database/questions.php');

    header('Content-Type: application/json');
    $response['requestStatus'] = "NOK";

    //$response['Hulk'] = "SMASH!";
    //die(json_encode($response));
        
    if(isset($_SESSION['s_username'])) {
        if(!isset($_POST['id'])) {
            returnErrorJSON($response, 2, "We need a valid question id to add a new answer");
        }
        if(!isset($_POST['text'])) {
            returnErrorJSON($response, 3, "An answer must have a body");
        }
        if(!isset($_POST['title'])) {
            returnErrorJSON($response, 4, "The answer must have a question title associated");
        }

        $id = $_POST['id'];
        $text = $_POST['text'];
        $title = $_POST['title'];

        if(!validateAnswerText($text)) {
            returnErrorJSON($response, 5, "The text of the answer is not valid");
        }
        if(!is_numeric($id)) {
            returnErrorJSON($response, 6, "Invalid id");
        }

        try {
            $db->beginTransaction();
            $answerid = insertAnswer($id, $text, $title);
            updLastActivityDate($id);
            $db->commit();

            $response['requestStatus'] = "OK";
            returnOkJSON($response, "Answer was added to database", array("answerId" => $answerid, "answerText" => $text, "username" => $_SESSION['s_username'], "userid" => $_SESSION['s_user_id'], "reputation" => $_SESSION['s_reputation']));
        } catch(DatabaseException $e) {
            $db->rollBack();
            returnErrorJSON($response, 7, "Error inserting answer into database", $e->getErrors());
        }

    } else {
        returnErrorJSON($response, 1, "You don't have permission to add a new answer");
    }
?>
