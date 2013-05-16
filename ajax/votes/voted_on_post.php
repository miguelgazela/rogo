<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/votes.php');

    header('Content-Type: application/json');
    $response['requestStatus'] = "NOK";

    if(!isset($_GET['id'])) {
        returnErrorJSON($response, 1, "We need a valid post id to run search");
    }

    $postid = $_GET['id'];

    if(!is_numeric($postid)) {
        returnErrorJSON($response, 2, "Invalid type of id");
    }

    $response['errorCode'] = -1;
    $response['requestStatus'] = "OK";
    $response['postId'] = $postid;
    if(($vote = getVoteOfPost($postid))) {
        $response['voted'] = true;
        $response['type'] = $vote['votetype'];
    } else {
        $response['voted'] = false;
    }
    die(json_encode($response));
?>
