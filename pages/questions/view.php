<?php
    // initialize
    include_once('../../common/init.php');

    // include needed database functions
    include_once($BASE_PATH . 'database/questions.php');    
    include_once($BASE_PATH . 'database/answers.php');    
    include_once($BASE_PATH . 'database/comments.php');
    include_once($BASE_PATH . 'database/tags.php');
    include_once($BASE_PATH . 'database/votes.php');

    $id = $_GET['id'];

    // fetch data
    try {
        $question = getQuestionById($id);
        if(!$question) {
            $smarty->assign('warning_msg', "We don't have any question with that id");
            $smarty->display("showWarning.tpl");
            exit();
        }
        incQuestionViews($id);

        $question['creationdate_p'] = getPrettyDate($question['creationdate']);
        $question['lasteditdate_p'] = getPrettyDate($question['lasteditdate']);
        $question['title'] = htmlspecialchars(stripslashes($question['title']));
        $question['body'] = nl2br(htmlspecialchars(stripslashes($question['body'])));
        $question['gravatar'] = "http://www.gravatar.com/avatar/".md5(strtolower(trim($question['email'])))."?s=48&r=pg&d=identicon";
    } catch(Exception $e) {
        $smarty->assign('warning_msg', "He need a valid question id to show you something useful");
        $smarty->display("showWarning.tpl");
        exit();
    }

    // get answers, votes, comments and dates to array
    $answers = getAnswersOfQuestion($id);
    $votes = array();
    $comments = array();

    $questionComments = getCommentsOfPost($id);
    foreach($questionComments as &$comment) {
        $comment['creationdate_p'] = getPrettyDate($comment['creationdate']);
        $comment['body'] = htmlspecialchars(stripslashes($comment['body']));
    }

    // add comments to this question and check if user has voted on it
    $comments[] = $questionComments;
    if(($vote = getVoteOfPost($id))) {
        $votes[] = array("voted" => true, "votetype" => $vote['votetype']);
    } else {
        $votes[] = array("voted" => false);
    }

    foreach($answers as &$answer) {
        $answerComments = getCommentsOfPost($answer['postid']);
        foreach($answerComments as &$comment) {
            $comment['creationdate_p'] = getPrettyDate($comment['creationdate']);
        }
        $comments[] = $answerComments;
        $answer['creationdate_p'] = getPrettyDate($answer['creationdate']);
        $answer['lasteditdate_p'] = getPrettyDate($answer['lasteditdate']);
        $answer['body'] = htmlspecialchars(stripslashes($answer['body']));

        //  working this
        if(($vote = getVoteOfPost($answer['postid']))) {
            $votes[] = array("voted" => true, "votetype" => $vote['votetype']);
        } else {
            $votes[] = array("voted" => false);
        }
    }

    // send data to smarty and display template
    $smarty->assign('question', $question);
    $smarty->assign('tags',getTagsOfQuestion($id));
    $smarty->assign('answers', $answers);
    $smarty->assign("comments", $comments);
    $smarty->assign("votes", $votes);

    $smarty->display("questions/view.tpl");
?>
