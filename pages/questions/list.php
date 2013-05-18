<?php
    // initialize
    include_once('../../common/init.php');

    // include needed database functions
    include_once($BASE_PATH . 'database/questions.php');
    include_once($BASE_PATH . 'database/tags.php');

    if(!isset($_GET['sort']) || !validSorting($_GET['sort'])) {
        $_GET['sort'] = "newest";
    }

    $questions = getQuestionsWithSorting($_GET['sort']);

    $tags = array();

    foreach($questions as &$question) {
        $tags[] = getTagsOfQuestion($question['questionid']);
        $question['creationdate'] = getPrettyDate($question['creationdate']);
    }

    // send data to smarty
    $smarty->assign('sorted_questions', $questions);
    $smarty->assign('sort_method', $_GET['sort']);
    $smarty->assign('number_questions', count($questions));
    $smarty->assign('tags', $tags);

    // display smarty template
    $smarty->display('questions/list.tpl');

    // sorting available: newest, votes, active, unanswered
    function validSorting($sort) {
        return ($sort == 'newest' || $sort == 'votes' || $sort == 'active' || $sort == 'unanswered');
    }
?>
