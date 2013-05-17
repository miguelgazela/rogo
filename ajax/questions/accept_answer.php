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
        if(!isset($_POST['questionid'])) {
            returnErrorJSON($response, 2, "We need a valid question id to accept an answer");
        }
        if(!isset($_POST['answerid'])) {
            returnErrorJSON($response, 3, "We need a valid answer id to accept it as a valid answer");
        }
        if(!isset($_POST['intention'])) {
        	returnErrorJSON($response, 4, "We need an intention to validate your request");
        }

        $questionid = $_POST['questionid'];
        $answerid = $_POST['answerid'];
        $intention = $_POST['intention'];


        if(!is_numeric($questionid) || !is_numeric($answerid)) {
            returnErrorJSON($response, 5, "Invalid id");
        }
        if($intention != "accept" && $intention != "remove-accept") {
        	returnErrorJSON($response, 6, "Invalid intention");
        } else {
        	$intention = ($intention == "accept");
        }

        try {

        	$question = getQuestionById($questionid);
        	$answer = getAnswerById($answerid);

        	if(!$question) {
        		returnErrorJSON($response, 8, "No question with that id", array($questionid));
        	}
        	if(!$answer) {
        		returnErrorJSON($response, 9, "No answer with that id", array($answerid));
        	}
        	if($answer['questionid'] != $questionid) {
    			returnErrorJSON($response, 10, "That answer doesn't belong to that question", array("answerid" => $answerid, "questionid" => $questionid));
    		}
    		if($question['ownerid'] != $_SESSION['s_user_id']) {
    			returnErrorJSON($response, 13, "That question doesn't belong to you", array("answerid" => $answerid, "questionid" => $questionid, "ownerid" => $question['ownerid']));
    		}

    		if($intention) { // wants to accept an answer
    			if($question['acceptedanswerid'] != null && $question['acceptedanswerid'] != $answerid) {
    				returnErrorJSON($response, 11, "The question already has a different answer acepted", array($question));
    			} else {
    				$db->beginTransaction();
        			acceptedAsValidAnswer($answerid, $intention);
        			acceptValidAnswer($questionid, $answerid);
        			$db->commit();
            		$response['requestStatus'] = "OK";
            		returnOkJSON($response, "Answer was accepted.", array("answerId" => $answerid, "questionid" => $questionid, "intention" => $intention));
    			}
    		} else { // wants to remove an accepted answer
    			if($question['acceptedanswerid'] == null) {
    				returnErrorJSON($response, 12, "The question doesn't have an accepted answer", array($question));
    			} else {
    				$db->beginTransaction();
    				acceptedAsValidAnswer($answerid, $intention);
    				removeAcceptedAnswer($questionid, $answerid);
    				$db->commit();
    				$response['requestStatus'] = "OK";
    				returnOkJSON($response, "Answer was removed as accepted.", array("answerId" => $answerid, "questionid" => $questionid, "intention" => $intention));
    			}
    		}
        } catch(DatabaseException $e) {
        	$db->rollBack();
            returnErrorJSON($response, 7, "Error accepting answer. Something went wrong in the database", $e->getErrors());
        }

    } else {
        returnErrorJSON($response, 1, "You don't have permission to accept an answer");
    }
?>
