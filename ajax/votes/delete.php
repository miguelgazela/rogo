<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/votes.php');
    include_once($BASE_PATH . 'database/answers.php');
    include_once($BASE_PATH . 'database/questions.php');
    include_once($BASE_PATH . 'database/users.php');

    header('Content-Type: application/json');
    $response['requestStatus'] = "NOK";

    if(isset($_SESSION['s_username'])) {
        if(!isset($_POST['id'])) {
            returnErrorJSON($response, 2, "We need a valid post id to delete a vote");
        }

        $postid = $_POST['id'];
        if(!is_numeric($postid)) {
            returnErrorJSON($response, 3, "Invalid id");
        }

        try {
            $vote = getVoteOfPost($postid);

            if(!$vote) {
                returnErrorJSON($response, 4, "Vote doesn't exist", array("existed" => false, "action" => "failed"));
            } else {
            	$db->beginTransaction();
            	removeVote($vote['voteid']);
            	
            	// needs to update score of post, call 2 functions because it can be a question or a post
            	if($vote['votetype'] == 1) {
            		updateAnswerScore($postid, -1);
            		updateQuestionScore($postid, -1);

                    // get owner
                    $answer = getAnswerById($postid);
                    if($answer) {
                        updateUserReputation($answer['ownerid'], -10);
                    } else {
                        $question = getQuestionById($postid);
                        if($question) {
                            updateUserReputation($question['ownerid'], -5);
                        }
                    }

            	} else {
            		updateAnswerScore($postid, 1);
            		updateQuestionScore($postid, 1);

                    // get owner
                    $answer = getAnswerById($postid);
                    if($answer) {
                        updateUserReputation($answer['ownerid'], +2);
                    } else {
                        $question = getQuestionById($postid);
                        if($question) {
                            updateUserReputation($question['ownerid'], +2);
                        }
                    }
            	}

            	$db->commit();
            	$response['requestStatus'] = "OK";
            	returnOkJSON($response, "Vote was removed from the database", array("vote" => $vote, "existed" => true, "action" => "deleted"));
            }
        } catch(DatabaseException $e) {
        	$db->rollBack();
            returnErrorJSON($response, 5, "Error deleting vote of database", $e->getErrors());
        }
    } else {
        returnErrorJSON($response, 1, "You don't have permission to vote");
    }
?>
