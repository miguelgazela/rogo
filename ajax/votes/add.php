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

        $id = $_POST['id'];
        $voteType = $_POST['voteType'];

        if($voteType != 1 && $voteType != 2) {
            returnErrorJSON($response, 4, "Invalid vote type");
        }
        if(!is_numeric($id)) {
            returnErrorJSON($response, 5, "Invalid id");
        }

        try {
            $voteid = getVoteOfPost($id);
            if(!$voteid) {
                $voteid = insertVote($id, $voteType);
                $response['voteid'] = $voteid;
            } else {
                updateVote($id, $voteType);
            }
            $response['errorCode'] = -1;
            $response['requestStatus'] = "OK";
            
            die(json_encode($response));
        } catch(DatabaseException $e) {
            returnErrorJSON($response, 6, "Error inserting vote into database");
        }

    } else {
        returnErrorJSON($response, 1, "You don't have permission to vote");
    }
?>
