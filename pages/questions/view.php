<?php
    // initialize
    include_once('../../common/init.php');

    // include needed database functions
    include_once($BASE_PATH . 'database/questions.php');    
    include_once($BASE_PATH . 'database/answers.php');    
    include_once($BASE_PATH . 'database/comments.php');

    $id = $_GET['id'];

    // fetch data
    try {
        $question = getQuestionById($id);
    } catch(Exception $e) {
        $_SESSION['s_error']['global'] = $e->getMessage();
        header("Location: $BASE_URL"."index.php");
    }

    // send data to smarty
    $smarty->assign('question', $question);

    // display smarty template
    $smarty->display("questions/view.tpl");
?>
