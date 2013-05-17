<?php
    // initialize
    include_once('../../common/init.php');
    include_once($BASE_PATH . 'database/comments.php');

    header('Content-Type: application/json');
    $response['requestStatus'] = "NOK";

    if(isset($_SESSION['s_username'])) {
        if(!isset($_POST['id'])) {
            returnErrorJSON($response, 2, "We need a valid post id to remove comment");
        }

        $commentid = $_POST['id'];
        if(!is_numeric($commentid)) {
            returnErrorJSON($response, 3, "Invalid id type");
        }

        try {
            removeComment($commentid);
            $response['requestStatus'] = "OK";
            returnOkJSON($response, "Comment was deleted from the database");
        } catch(DatabaseException $e) {
            returnErrorJSON($response, 4, "Error with database operation", $e->getErrors());
        }
    } else {
        returnErrorJSON($response, 1, "You don't have permission to remove comments");
    }
?>
