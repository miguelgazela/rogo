<?
    // initialize
    include_once('../../common/init.php');

    // include needed database functions
    include_once($BASE_PATH . 'database/questions.php');

    if(!isset($_GET['sort'])) {
        $_GET['sort'] = "newest";
    }
    if(!validSorting($_GET['sort'])) {
        $_GET['sort'] = "newest";
    }        

    $questions = getQuestionsWithSorting($_GET['sort']);

    // send data to smarty
    $smarty->assign('sorted_questions', $questions);
    $smarty->assign('sort_method', $_GET['sort']);

    // display smarty template
    $smarty->display('questions/list.tpl');

    // sorting available: newest, votes, active, unanswered
    function validSorting($sort) {
        return ($sort == 'newest' || $sort == 'votes' || $sort == 'active' || $sort == 'unanswered');
    }

?>
