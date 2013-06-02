<?php
    // initialize
    include_once('../../common/init.php');

    // include needed database functions
    include_once($BASE_PATH . 'database/questions.php');    
    include_once($BASE_PATH . 'database/tags.php');

    if(!isset($_GET['tags'])) {
        header("Location: $BASE_URL"."index.php");
    }
    if(!isset($_GET['page']) || !is_numeric($_GET['page'])) {
        $_GET['page'] = "1";
    }

    $unchecked_tagnames = explode(",", $_GET['tags']);
    $unchecked_tags = array();
    $checked_tags = array();
    $questionids = array();
    $pageNumber = intval($_GET['page']);


    foreach($unchecked_tagnames as $unchecked_tagname) {
        $unchecked_tags[] = getTagByName($unchecked_tagname);
    }

    foreach($unchecked_tags as $tag) {
        if($tag) {
            $checked_tags[] = $tag;
            $questionids[] = getQuestionsWithTag($tag['tagid']);
        }
    }

    foreach($questionids as &$q) {
        for($i = 0; $i < count($q); $i++) {
            $q[$i] = $q[$i]['questionid'];
        }
    }

    if(count($checked_tags > 1)) {
        $intersect = $questionids[0];
        for($i = 1; $i < count($checked_tags); $i++) {
            $intersect = array_intersect($intersect, $questionids[$i]);
        }
    } else {
        $intersect = $questionids[0];
    }

    $questions = array();
    $toGet = min($pageNumber*5, count($intersect));
    $got = 0;
    $i = 0;

    do {
        if($intersect[$i]) {
            $questions[] = getQuestionById($intersect[$i]);
            $got++;
        }
        $i++;
    } while($got != $toGet);

    $questiontags = array();

    foreach($questions as &$question) {
        $questiontags[] = getTagsOfQuestion($question['questionid']);
        $question['creationdate_p'] = getPrettyDate($question['creationdate']);
        $question['title'] = htmlspecialchars(stripslashes($question['title']));
        $question['body'] = getSmallerText(htmlspecialchars(stripslashes($question['body'])), 330);
        $question['gravatar'] = "http://www.gravatar.com/avatar/".md5(strtolower(trim($question['email'])))."?s=50&r=pg&d=identicon";
    }

    $smarty->assign("questions", $questions);
    $smarty->assign('total_number_questions', count($intersect));
    $smarty->assign('number_presented_questions', count($questions));
    $smarty->assign("tags", $checked_tags);
    $smarty->assign("page", $pageNumber);
    $smarty->assign("questiontags", $questiontags);

    // display smarty template
    $smarty->display('tags/view.tpl');
    exit;
?>