<!DOCTYPE html>
<html lang="en">
   
    {include file="../header.tpl" title="Rogo - {$sort_method} Questions"}

    <body>
        {include file="../navbar.tpl"}
        
        <div class="container">
            <div class="row">
                <div class="question-header">
                    <h3>{$question.title}</h3>
                </div>
                <div class="span9">
                    <div class="question-info ">
                        <div class="question-side-container pull-left">
                            <img class="media-object" src="{$BASE_URL}img/imageholder.png">
                            <div class="vote-area" id="vote-area-{$question.questionid}">
                                <span class="vote-up"></span>
                                <span class="vote-counter text-center">{$question.score}</span>
                                <span class="vote-down"></span>
                            </div>
                        </div>
                        <div class="question-container">
                            <p class="question-body">{$question.body}</p>
                            <div class="tags">
                                {foreach $tags as $tag}
                                <a href="#" class="post-tag">{$tag.tagname}</a>
                                {/foreach}
                            </div>
                            <div class="question-footer">
                                <div class="started">
                                    <span class="action-time">{$question.creationdate}</span>
                                    <div class="user-info">
                                        <a href="{$BASE_URL}pages/users/view.php?id={$question.ownerid}" class="username">{$question.username}</a>
                                        <span class="reputation"><i class="icon-trophy"></i> {$question.reputation}</span>
                                    </div>
                                </div>
                                {if $question.creationdate != $question.lasteditdate}
                                    <div class="started edited">
                                        <span class="action-time">{$question.lasteditdate}</span>
                                        <div class="user-info">
                                            <a href="{$BASE_URL}pages/users/view.php?id={$question.lasteditorid}" class="username">{$question.lasteditorid}</a>
                                            <!-- needs more information from the php page file-->
                                            <span class="reputation"><i class="icon-trophy"></i> 1200</span>
                                        </div>
                                    </div>
                                {/if}
                            </div>
                            {if $s_username != ""}
                            <div class="comments" id="comments-{$question.questionid}">
                                {foreach $comments as $comment}
                                    {if $comment@index > 4}
                                    <div class="comment hide" id="comment-{$comment.commentid}">
                                    {else}
                                    <div class="comment" id="comment-{$comment.commentid}">
                                    {/if}
                                        {$comment.body} - <a href="{$BASE_URL}pages/users/view.php?id={$comment.ownerid}" class="username">{$comment.username}</a><span class="action-time"> {$comment.creationdate}</span>
                                    </div>
                                    {if $comment@index == 4}
                                        <a href="#" class="showMore">Show more comments...</a>
                                    {/if}
                                {/foreach}
                                <form class="add_comment_form">
                                    <div class="control-group inputComment">
                                        <div class="controls">
                                            <textarea rows="3" placeholder="Write a comment..." class="inputComment" name="comment"></textarea>
                                        </div>
                                        <span class="help-block"></span>
                                    </div>
                                </form>
                            </div>
                            {/if}
                        </div>
                    </div>
                    <div class="question-answers">
                        <div class="answers-header">
                            {if $question.answercount == 1}
                                <h4><span class="answers-counter">{$question.answercount}</span> Answer</h4>
                            {else}
                                <h4><span class="answers-counter">{$question.answercount}</span> Answers</h4>
                            {/if}
                        </div>
                        <div class="answers-container">
                            {foreach $answers as $answer}
                            <div class="answer">
                                <div class="vote-area pull-left" id="vote-area-{$answer.postid}">
                                    <span class="vote-up"></span>
                                    <span class="vote-counter text-center">{$answer.score}</span>
                                    <span class="vote-down"></span>
                                    {if $question.ownerid == $s_user_id}
                                        <span class="accept-answer text-center"><i class="icon-ok-circle icon-2x"></i></span>
                                    {/if}
                                </div>
                                <div class="answer-container">
                                    <p class="answer-body">{$answer.body}</p>
                                    <div class="started">
                                        <span class="action-time">{$answer.creationdate}</span>
                                        <div class="user-info">
                                            <a href="{$BASE_URL}pages/users/view.php?id={$answer.ownerid}" class="username">{$answer.username}</a>
                                            <span class="reputation"><i class="icon-trophy"></i> {$answer.reputation}</span>
                                        </div>
                                    </div>
                                    {if $answer.creationdate != $answer.lasteditdate}
                                        <div class="started edited">
                                            <span class="action-time">{$answer.lasteditdate}</span>
                                            <div class="user-info">
                                                <a href="{$BASE_URL}pages/users/view.php?id={$answer.lasteditorid}" class="username">{$answer.lasteditorid}</a>
                                                <span class="reputation"><i class="icon-trophy"></i> 1409</span>
                                            </div>
                                        </div>
                                    {/if}
                                    {if $s_username != ""}
                                    <div class="comments" id="comments-{$answer.postid}">
                                        <!-- comments will be inserted dynamically here -->
                                        <form class="add_comment_form">
                                            <div class="control-group inputComment">
                                                <div class="controls">
                                                    <textarea rows="3" placeholder="Write a comment..." class="inputComment" name="comment"></textarea>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </form>
                                    </div>
                                    {/if}
                                </div>
                            </div>
                            {/foreach}
                        </div>
                    </div>
                    {if $s_username != ""}
                    <h3 class="add_new_answer">Add your answer</h3>
                    <form id="add_answer_form" action="/">
                        <div class="control-group inputAnswer">
                            <div class="controls">
                                <textarea rows="8" placeholder="Write an answer..." id="inputAnswer" name="answer"></textarea>
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <label class="checkbox">
                            <input type="checkbox" value="" name="anonymously">
                            Add Anonymously
                        </label>
                        <button type="button" class="btn" onclick="return addAnswer({$question.questionid})">Post answer</button>
                        <button type="button" class="btn">Save draft</button>
                    </form>
                    {/if}
                </div>
                <div class="span3">
                    <div class="sidebar-content affix">
                        <a href="{$BASE_URL}pages/questions/add.php" class="ask-question-btn">Ask Question</a>
                    </div>
                </div>
            </div>
        </div>

        <!--{include file="../footer.tpl"} -->
    
    <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        {include file="../common-js.tpl"}
        
        <script>

            $(document).ready(function() {

                $(".showMore").click(function(){
                    $(this).nextAll(".comment").removeClass('hide');
                    $(this).remove();
                });


                $("span.vote-up").click(function(){
                    var postid = $(this).parent(".vote-area").attr("id").slice(10);

                    {literal}
                    $.post(BASE_URL+"ajax/votes/add.php", {id: postid, voteType: 1}, function(response){
                    {/literal}
                        console.log(response);
                    });
                });

                $("span.vote-down").click(function(){
                    console.log("Voting down on "+$(this).parent(".vote-area").attr("id").slice(10));
                });
            });

            // event handler for comment textareas
            $("textarea.inputComment").keypress(function(event){
                var comment = $(this).val();
                var inputCommentCtrl = $(this).parents("div.inputComment");

                if(event.which == ENTER_KEY && event.shiftKey) {
                    // do nothing
                }
                else if (event.which == ENTER_KEY) {
                   event.preventDefault(); // stops enter from creating a new line

                    if(comment.length < 15) {
                        inputCommentCtrl.addClass("error");
                        inputCommentCtrl.children('span.help-block').text("Write at least 15 characters");
                    } else {
                        inputCommentCtrl.removeClass("error");
                        inputCommentCtrl.children('span.help-block').text("");
                        var postid = inputCommentCtrl.parents("div.comments").attr("id").slice(9);

                        {literal}
                        $.post(BASE_URL+"ajax/comments/add.php", {id: postid, text: comment}, function(response){
                        {/literal}
                            console.log(response); // TODO remove
                            if(response.requestStatus == "OK") {
                                var newComment = "<div class='comment' id='comment-'"+response.commentId+">"+comment;
                                newComment += " - <a href='{$BASE_URL}pages/users/view.php?id={$s_user_id}' class='username'>{$s_username}</a><span class='action-time'> "+new Date()+"</span></div>";
                                inputCommentCtrl.parent("form").before(newComment);
                                $("#comments-"+postid+" textarea").val("");
                            } else {
                                alert("Ups! An error occurred while trying to add your comment. Please try again later."); // TODO improve warning quality
                            }
                        });
                    }
                }
            });

            function loadAnswersComments() {
                $(".answer .comments").each(function(){
                    var postid = $(this).attr("id").slice(9);
                    {literal}
                    $.get(BASE_URL+"ajax/comments/get.php", {id: postid}, function(response){
                    {/literal}
                        console.log(response); // TODO remove
                        if(response.requestStatus == "OK") {
                            var comments = response.data.comments;
                            for(var i = 0; i < comments.length; i++) {
                                var comment;
                                if(i > 4) {
                                    comment = "<div class='comment hide' id='comment-'"+comments[i].commentid+"'>";
                                } else {
                                    comment = "<div class='comment' id='comment-'"+comments[i].commentid+"'>";
                                }
                                comment += comments[i].body+" - <a href='{$BASE_URL}pages/users/view.php?id="+comments[i].ownerid+"' class='username'>"+comments[i].username+"</a><span class='action-time'> "+comments[i].creationdate+"</span>";
                                if(i == 4) {
                                    comment += '<a href="#" class="showMore">Show more comments...</a>';
                                }
                                $("#comments-"+postid).children('form').before(comment);
                            }
                        } else {
                            console.log("An error occurred while trying to get comments of post "+postid);
                        }
                    });
                    
                });
            }

            function addAnswer(questionID) {
                var answerText = $("#inputAnswer").val();
                var questionTitle = $(".question-header h3").text();

                console.log("HULK SMASH!");

                if(answerText.length < 20) {
                    $(".inputAnswer > span.help-block").text("You need to write at least 20 characters");
                    $(".inputAnswer").addClass("error");
                } else {
                    // clear possible previous error
                    $(".inputAnswer > span.help-block").text("");
                    $(".inputAnswer").removeClass("error");

                    {literal}
                    $.post(BASE_URL+'ajax/answers/add.php', {id: questionID, text: answerText, title: questionTitle}, function(response) {
                    {/literal}
                        console.log(response); // TODO remove
                        if(response.requestStatus == "OK") {
                            var answer = "<div class='answer' id='"+response.answerID+"'>";
                            answer += "<div class='vote-area pull-left'><span class='vote-up'></span>";
                            answer += "<span class='vote-counter text-center'>0</span>";
                            answer += "<span class='vote-down'></span>";
                            answer += "<span class='accept-answer' text-center accepted'><i class='icon-ok-circle icon-2x'></i></span></div>";
                            answer += "<div class='answer-container'><p class='answer-body'>"+answerText+"</p>";
                            answer += "<div class='started'><span class='action-time'>"+new Date()+"</span>";
                            answer += "<div class='user-info'><a href='{$BASE_URL}pages/users/view.php?id={$s_user_id}' class='username'>{$s_username}</a>";
                            answer += "<span class='reputation'><i class='icon-trophy'></i> 666</span></div></div></div>";
                            $("div.answers-container").append(answer);

                            $("#inputAnswer").val(""); // clear textarea

                            // update answer counter
                            var current = parseInt($("span.answers-counter").text()) + 1;
                            if(current == 1) {
                                $('.answers-header > h4').html("<span class='answers-counter'>"+current+"</span> Answer");
                            } else {
                                $('.answers-header > h4').html("<span class='answers-counter'>"+current+"</span> Answers");
                            }
                        } else {
                            alert("Ups! An error occurred while trying to add your answer. Please try again later."); // TODO improve warning quality
                        }
                    });
                }
            }
        </script>

        {if $s_username != ""}
            <script>loadAnswersComments();</script>
        {/if}

    </body>
</html>​
