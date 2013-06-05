<?php
    // initialize
    include_once('../../common/init.php');

    // include needed database functions
    include_once($BASE_PATH . 'database/questions.php');
    include_once($BASE_PATH . 'database/tags.php');     

    if(!isset($_GET['query'])) {
        header("Location: $BASE_URL"."index.php");
        exit;
    }

    $query_words = explode(" ", $_GET['query']);
    $questions = null;

    foreach($query_words as $word) {
        if($word != "") {
            $matched_questions = getSearchQuestions($word);

            if($questions == null) {
                $questions = $matched_questions;
            } else { // check which questions are already in the array
                $diff = array_udiff($matched_questions, $questions, 'compareQuestions');
                if(count($diff) != 0) { 
                    foreach($diff as $diff_question) {
                        array_push($questions, $diff_question);
                    }
                }
            }
        }
    }

    $questiontags = array();

    foreach($questions as &$question) {
        $questiontags[] = getTagsOfQuestion($question['questionid']);
        $question['creationdate_p'] = getPrettyDate($question['creationdate']);
        $question['title'] = htmlspecialchars(stripslashes($question['title']));
        $question['body'] = getSmallerText(htmlspecialchars(stripslashes($question['body'])), 330);
        $question['gravatar'] = "http://www.gravatar.com/avatar/".md5(strtolower(trim($question['email'])))."?s=50&r=pg&d=identicon";
    }

    $smarty->assign("questions", $questions);
    $smarty->assign('total_number_questions', count($questions));
    $smarty->assign("questiontags", $questiontags);
    $smarty->assign("query_words", $query_words);
    $smarty->display("questions/search.tpl");

    function compareQuestions ($obj_a, $obj_b) {
        return $obj_a['questionid'] - $obj_b['questionid'];
    }
?>