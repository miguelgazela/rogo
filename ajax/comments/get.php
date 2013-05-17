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

        $comments = getCommentsOfPost($id);
        $response['requestStatus'] = "OK";
        returnOkJSON($response, "Returning comments for post", array("totalItems" => count($comments), "comments" => $comments));
    } else {
        returnErrorJSON($response, 1, "You don't have permission to get comments");
    }
?>
