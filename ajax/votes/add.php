<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/votes.php');
    include_once($BASE_PATH . 'database/users.php');
    include_once($BASE_PATH . 'database/questions.php');
    include_once($BASE_PATH . 'database/answers.php');
    
    header('Content-Type: application/json');
    $response['requestStatus'] = "NOK";

    if(isset($_SESSION['s_username'])) {
        if(!isset($_POST['id']) || !is_numeric($_POST['id'])) {
            returnErrorJSON($response, 2, "We need a valid post id to add a new vote");
        }
        if(!isset($_POST['voteType'])) {
            returnErrorJSON($response, 3, "A vote must have a type");
        }

        $postid = $_POST['id'];
        $voteType = $_POST['voteType'];

        if($voteType != 1 && $voteType != 2) {
            returnErrorJSON($response, 4, "Invalid vote type");
        }

        try {
            $vote = getVoteOfPost($postid);

            if(!$vote) {
                $db->beginTransaction();
                $vote = insertVote($postid, $voteType);

                // get owner
                $answer = getAnswerById($postid);
                if($answer) {
                    updateUserReputation($answer['ownerid'], 10);
                } else {
                    $question = getQuestionById($postid);
                    if($question) {
                        updateUserReputation($question['ownerid'], 5);
                    }
                }

                $db->commit();
                $response['requestStatus'] = "OK";
                returnOkJSON($response, "Vote was added to the database", array("voteid" => $vote, "existed" => false, "action" => "inserted"));
            } else {
                returnErrorJSON($response, 6, "Vote already exists", array("vote" => $vote, "existed" => true, "action" => "failed"));
            }
        } catch(DatabaseException $e) {
            $db->rollBack();
            returnErrorJSON($response, 7, "Error inserting vote into database", $e->getErrors());
        }
    } else {
        returnErrorJSON($response, 1, "You don't have permission to vote");
    }
?>
