<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/votes.php');
    
    header('Content-Type: application/json');
    $response['requestStatus'] = "NOK";

    if(isset($_SESSION['s_username'])) {
        if(!isset($_POST['id'])) {
            returnErrorJSON($response, 2, "We need a valid post id to control vote");
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
            returnErrorJSON($response, 5, "Invalid id type");
        }

        try {
            $vote = getVoteOfPost($postid);

            if(!$vote) { // make post request to add.php
                $data = array('id' => $postid, 'voteType' => $voteType);
                $options = array(
                    'http' => array(
                        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method' => 'POST',
                        'content' => http_build_query($data)
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($BASE_URL."ajax/votes/add.php", false, $context);

                /*
                $vote = insertVote($postid, $voteType);
                $response['voteid'] = $vote;
                $response['existed'] = false;
                */
               $response['result'] = $result;
            } else {
                $response['existed'] = true;
                $response['voteid'] = $vote['voteid'];

                if($vote['votetype'] == $voteType) {
                    removeVote($vote['voteid']);
                    $response['action'] = "removed";
                } else {
                    updateVote($vote['voteid'], $voteType);
                    $response['action'] = "updated";
                }
            }
            
            $response['errorCode'] = -1;
            $response['requestStatus'] = "OK";
            die(json_encode($response));
        } catch(DatabaseException $e) {
            returnErrorJSON($response, 6, "Error with database operation", $e->getErrors());
        }

    } else {
        returnErrorJSON($response, 1, "You don't have permission to vote");
    }
    
?>
