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
        if(!isset($_POST['isdraft'])) {
            returnErrorJSON($response, 8, "The answer must define the draft state");
        }

        $id = $_POST['id'];
        $text = $_POST['text'];
        $title = $_POST['title'];
        $draft = $_POST['isdraft'];

        if(!validateAnswerText($text)) {
            returnErrorJSON($response, 5, "The text of the answer is not valid");
        }
        if(!is_numeric($id)) {
            returnErrorJSON($response, 6, "Invalid id");
        }
        if($draft !== "false" && $draft !== "true") {
            returnErrorJSON($response, 9, "Invalid draft");
        }

        try {
            $answer = getAnswerByUser($id);

            if($answer) { // answer already exists

                if($answer['ownerid'] == $_SESSION['s_user_id']) { // and the users owns it
                    
                    if($answer['draft']) { // if it's a draft, the user can update it or set it as a definitive answer
                        updateAnswerDraft($answer['answerid'], $text);
                        
                        if($draft === "true") {
                            $response['requestStatus'] = "OK";
                            returnOkJSON($response, "Draft updated", array("answerId" => $answerid, "draft" => $draft));
                        } else {
                            $db->beginTransaction();
                            setDraftAsAnswer($answer['answerid']);
                            incNumAnswers($id);
                            $db->commit();
                            $response['requestStatus'] = "OK";
                            returnOkJSON($response, "Draft is now a definitive answer", array("answerId" => $answerid, "answerText" => nl2br(htmlspecialchars(stripslashes($text))), "draft" => $draft, "username" => $_SESSION['s_username'], "userid" => $_SESSION['s_user_id'], "reputation" => $_SESSION['s_reputation']));
                        }
                    } else {
                        returnErrorJSON($response, 10, "Already answered this question", array("questionid" => $id, "isdraft" => $draft));
                    }
                } else {
                    returnErrorJSON($response, 11, "Not your answer", array("answerid" => $answer['answerid']));
                }
            } else {
                $db->beginTransaction();
                $answerid = insertAnswer($id, $text, $title, $draft);
                updLastActivityDate($id);
                $db->commit();

                $response['requestStatus'] = "OK";
                returnOkJSON($response, "Answer was added to database", array("answerId" => $answerid, "answerText" => nl2br(htmlspecialchars(stripslashes($text))), "draft" => $draft, "username" => $_SESSION['s_username'], "userid" => $_SESSION['s_user_id'], "reputation" => $_SESSION['s_reputation']));
            }
        } catch(DatabaseException $e) {
            $db->rollBack();
            returnErrorJSON($response, 7, "Error inserting answer into database", $e->getErrors());
        }

    } else {
        returnErrorJSON($response, 1, "You don't have permission to add a new answer");
    }
?>
