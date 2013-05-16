<?php
    // This file is included in every page

    // Define some base paths
    $BASE_PATH = '/opt/lbaw/lbaw12201/public_html/rogo/';
    $BASE_URL = 'http://gnomo.fe.up.pt/~lbaw12201/rogo/';

    require_once('database.php');
    require_once('session.php');
    require_once('smarty.php');

    function returnErrorJSON() {
        if(func_num_args() < 3 && func_num_args() > 4) {
            throw new Exception("returnErrorJSON: invalid number of arguments");
        }
        $response = func_get_arg(0);
        $response['errorCode'] = func_get_arg(1);
        $response['errorMessage'] = func_get_arg(2);
        if(func_num_args() == 4) {
            $response['errors'] = func_get_arg(3);
        }
        die(json_encode($response));
    }

    function returnOkJSON() {
        if(func_num_args() < 2 && func_num_args() > 3) {
            throw new Exception("returnErrorJSON: invalid number of arguments");
        }
        $response = func_get_arg(0);
        $response['errorCode'] = -1;
        $response['errorMessage'] = func_get_arg(1);

        if(func_num_args() == 3) {
            $response['data'] = func_get_arg(2);
        }
        die(json_encode($response));
    }
?>