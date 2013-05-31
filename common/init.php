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

    function returnIfHasErrors($errors, $url) {
        global $BASE_URL;
        if($errors->hasErrors()) {
            $_SESSION['s_error'] = $errors->getErrors();
            $_SESSION['s_values'] = $_POST;
            header("Location: $BASE_URL".$url);
            exit;
        }
    }

    function getSmallerText($text, $n) {

        if(strlen($text) <= $n) {
            return $text;
        } else {
            return (substr($text, 0, $n)."...");
        }
    }

    function getNormalDate($date) {
        $date = getdate(strtotime($date));
        return $date[mday]." ".substr($date[month], 0, 3)." ".$date[year];
    }

    function getPrettyDate($date) {
        $now = time();
        $diff_sec = $now - strtotime($date);

        if($diff_sec < 60) {
            if($diff_sec != 0) {
                return $diff_sec."s ago";
            } else {
                return "1s ago";
            }
        } else {
            $diff_min = ceil($diff_sec / 60);
            if($diff_min < 60) {
                return $diff_min."min ago";
            } else {
                $diff_hour = round($diff_min / 60);
                if($diff_hour < 24) {
                    return $diff_hour."h ago";
                } else {
                    $diff_day = round($diff_hour / 24);
                    if($diff_day < 7) {
                        return $diff_day."d ago";
                    } else {
                        $date = getdate(strtotime($date));
                        $now = getdate($now);

                        if($date[year] == $now[year]) {
                            return substr($date[month], 0, 3)." ".$date[mday]." at ".$date[hours].":".$date[minutes];
                        } else {
                            return $date[year]." ".substr($date[month], 0, 3)." ".$date[mday]." at ".$date[hours].":".$date[minutes];
                        }
                    }
                }
            }
        }
    }
?>