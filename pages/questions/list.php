<?php
    // initialize
    include_once('../../common/init.php');

    // include needed database functions
    include_once($BASE_PATH . 'database/questions.php');
    include_once($BASE_PATH . 'database/tags.php');

    if(!isset($_GET['sort']) || !validSorting($_GET['sort'])) {
        $_GET['sort'] = "newest";
    }
    if(!isset($_GET['page']) || !is_numeric($_GET['page'])) {
        $_GET['page'] = "1";
    }

    $pageNumber = intval($_GET['page']);
    $questions = getQuestionsWithSorting($_GET['sort'], 5*$pageNumber, 0);
    $counter = getNumberOfQuestionsWithSorting($_GET['sort']);
    $tags = array();

    foreach($questions as &$question) {
        $tags[] = getTagsOfQuestion($question['questionid']);
        $question['creationdate_p'] = getPrettyDate($question['creationdate']);
        $question['title'] = htmlspecialchars(stripslashes($question['title']));
        $question['body'] = getSmallerText(htmlspecialchars(stripslashes($question['body'])), 330);
        $question['gravatar'] = "http://www.gravatar.com/avatar/".md5(strtolower(trim($question['email'])))."?s=50&r=pg&d=identicon";
    }

    // get popular tags
    $popular_tags = getTagsWithSorting("popular", 10, 0);
    if($popular_tags) {
        $smarty->assign("popular_tags", $popular_tags);
    }

    // send data to smarty
    $smarty->assign('sorted_questions', $questions);
    $smarty->assign('sort_method', $_GET['sort']);
    $smarty->assign('total_number_questions', $counter['total']);
    $smarty->assign('number_presented_questions', count($questions));
    $smarty->assign('tags', $tags);
    $smarty->assign("page", $pageNumber);

    // display smarty template
    $smarty->display('questions/list.tpl');

    // sorting available: newest, votes, active, unanswered
    function validSorting($sort) {
        return ($sort == 'newest' || $sort == 'votes' || $sort == 'active' || $sort == 'unanswered');
    }
?>
