<?php
    // initialize
    include_once('../../common/init.php');
	include_once($BASE_PATH . 'database/votes.php');
    
    header('Content-Type: application/json');
    $response['requestStatus'] = "NOK";

    if(isset($_SESSION['s_username'])) {
        if(!isset($_POST['id'])) {
            returnErrorJSON($response, 2, "We need a valid post id to update a vote");
        }
        if(!isset($_POST['voteType'])) {
            returnErrorJSON($response, 3, "vote must have a type");
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
                returnErrorJSON($response, 6, "Vote doesn't exist", array("existed" => false, "action" => "failed"));
            } else {

            	if($vote['votetype'] == $voteType) {
            		returnErrorJSON($response, 7, "Vote is already of that type", array("vote" => $vote, "existed" => true, "action" => "failed"));
            	}
                updateVote($vote['voteid'], $voteType);
            	$response['requestStatus'] = "OK";
            	returnOkJSON($response, "Vote was updated", array("vote" => $vote, "existed" => true, "action" => "updated"));
            }
        } catch(DatabaseException $e) {
            returnErrorJSON($response, 8, "Error updating vote on database", $e->getErrors());
        }
    } else {
        returnErrorJSON($response, 1, "You don't have permission to vote");
    }

?>
