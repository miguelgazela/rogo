<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'common/DatabaseException.php');
    include_once($BASE_PATH . 'database/comments.php');

    header('Content-Type: application/json');
    $response['requestStatus'] = "NOK";

    if(isset($_SESSION['s_username'])) {
        if(!isset($_POST['id'])){
            returnErrorJSON($response, 2, "We need a valid post id to add a new comment");
        }
        if(!isset($_POST['text'])) {
            returnErrorJSON($response, 3, "A comment must have a body");
        }

        $postid = $_POST['id'];
        $text = $_POST['text'];

        if(!validateCommentText($text)) {
            returnErrorJSON($response, 4, "The text of the comment is not valid");
        }
        if(!is_numeric($postid)) {
            returnErrorJSON($response, 5, "Invalid id: ".$postid);
        }

        try {
            $commentid = insertComment($postid, $text);
            $response['errorCode'] = -1;
            $response['requestStatus'] = "OK";
            $response['commentId'] = $commentid;
            die(json_encode($response));
        } catch(DatabaseException $e) {
            returnErrorJSON($response, 6, "Error inserting answer into database");
        }

    } else {
        returnErrorJSON($response, 1, "You don't have permission to add a new answer");
    }
?>
