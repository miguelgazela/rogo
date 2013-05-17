<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/votes.php');
    
    header('Content-Type: application/json');
    $response['requestStatus'] = "NOK";

    if(isset($_SESSION['s_username'])) {
        if(!isset($_POST['id'])) {
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
        if(!is_numeric($postid)) {
            returnErrorJSON($response, 5, "Invalid id");
        }

        try {
            $vote = getVoteOfPost($postid);

            if(!$vote) {
                $vote = insertVote($postid, $voteType);
                $response['requestStatus'] = "OK";
                returnOkJSON($response, "Vote was added to the database", array("voteid" => $vote, "existed" => false, "action" => "inserted"));
            } else {
                returnErrorJSON($response, 6, "Vote already exists", array("vote" => $vote, "existed" => true, "action" => "failed"));
            }
        } catch(DatabaseException $e) {
            returnErrorJSON($response, 7, "Error inserting vote into database", $e->getErrors());
        }
    } else {
        returnErrorJSON($response, 1, "You don't have permission to vote");
    }
?>
