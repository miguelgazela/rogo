<?php
    // This file is included in every page

    // Define some base paths
    $BASE_PATH = '/opt/lbaw/lbaw12201/public_html/rogo/';
    $BASE_URL = 'http://gnomo.fe.up.pt/~lbaw12201/rogo/';

    require_once('database.php');
    require_once('session.php');
    require_once('smarty.php');

    function returnErrorJSON($response, $errorCode, $errorMessage) {
        $response['errorCode'] = $errorCode;
        $response['errorMessage'] = $errorMessage;
        die(json_encode($response));
    }
?>