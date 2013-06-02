<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/answers.php');
    include_once($BASE_PATH . 'database/votes.php');

    header('Content-Type: application/json');
    $response['requestStatus'] = "NOK";

    if(isset($_SESSION['s_username'])) {
        if(!isset($_POST['id']) || !is_numeric($_POST['id'])) {
            returnErrorJSON($response, 2, "We need a valid post id to remove answer");
        }

        $answerid = $_POST['id'];
        try {
            $answer = getAnswerById($answerid);

            if(!$answer) {
                returnErrorJSON($response, 3, "no answer with this id: $answerid");
            }

            if($answer['ownerid'] != $_SESSION['s_user_id'] && $_SESSION['s_user_permission'] == 1) {
                returnErrorJSON($response, 5, "you don't have permission to delete this answer");
            }

            // check if it has been accepted
            if($answer['accepted']) {
                returnErrorJSON($response, 6, "You can't delete an answer that has been accepted");
            }

            // check if answer has any votes
            $votes = getNumberOfVotes($answer['answerid']);

            if($votes['total'] == 0) {
                removeAnswer($answerid);
                $response['requestStatus'] = "OK";
                returnOkJSON($response, "Answer was deleted from database");
            } else {
                returnErrorJSON($response, 7, "You can't delete an answer that has votes");
            }
        } catch(DatabaseException $e) {
            returnErrorJSON($response, 4, "Error with database operation", $e->getErrors());
        }
    } else {
        returnErrorJSON($response, 1, "You don't have permission to remove answers");
    }

?>
