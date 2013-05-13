<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/comments.php');

    header('Content-Type: application/json');
    $response['requestStatus'] = "NOK";

    if(isset($_SESSION['s_username'])) {
        $id = $_GET['id'];
        
        if(!isset($_GET['id'])) {
            returnErrorJSON($response, 2, "We need a valid post id to get its comments");
        }
        if(!is_numeric($id)) {
            returnErrorJSON($response, 3, "Invalid id");
        }

        $response['errorCode'] = -1;
        $response['requestStatus'] = "OK";
        $comments = getCommentsOfPost($id);
        $response['data']['totalItems'] = count($comments);
        $response['data']['comments'] = $comments;
        die(json_encode($response));
    } else {
        returnErrorJSON($response, 1, "You don't have permission to get comments");
    }
?>
