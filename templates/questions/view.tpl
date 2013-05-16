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
                                {foreach $comments[0] as $comment}
                                    {if $comment@index > 4}
                                    <div class="comment hide" id="comment-{$comment.commentid}">
                                    {else}
                                    <div class="comment" id="comment-{$comment.commentid}">
                                    {/if}
                                        {$comment.body} - <a href="{$BASE_URL}pages/users/view.php?id={$comment.ownerid}" class="username">{$comment.username} </a><span class="action-time"> {$comment.creationdate}</span>
                                        {if $comment.username == $s_username}
                                             <i class="icon-remove-sign"></i>
                                        {/if}
                                    </div>
                                    {if $comment@index == 4 && $comment@total > 5}
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
                            <div class="answer" id="answer-{$answer.postid}">
                                <div class="vote-area pull-left" id="vote-area-{$answer.postid}">
                                    <span class="vote-up"></span>
                                    <span class="vote-counter text-center">{$answer.score}</span>
                                    <span class="vote-down"></span>
                                    {if $question.ownerid == $s_user_id}
                                        <span class="accept-answer text-center"><i class="icon-ok-circle icon-2x"></i></span>
                                        {if $answer.commentcount == 0}
                                        <span class="remove-answer text-center"><i class="icon-remove-sign icon-2x"></i></span>
                                        {/if}
                                        <span class="edit-answer text-center"><i class="icon-edit icon-2x"></i></span>
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
                                        {foreach $comments[$answer@index+1] as $comment}
                                            {if $comment@index > 4}
                                            <div class="comment hide" id="comment-{$comment.commentid}">
                                            {else}
                                            <div class="comment" id="comment-{$comment.commentid}">
                                            {/if}
                                                {$comment.body} - <a href="{$BASE_URL}pages/users/view.php?id={$comment.ownerid}" class="username">{$comment.username} </a><span class="action-time"> {$comment.creationdate}</span>
                                                {if $comment.username == $s_username}
                                                    <i class="icon-remove-sign"></i>
                                                {/if}
                                            </div>
                                            {if $comment@index == 4 && $comment@total > 5}
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

                    {foreach $votes as $vote}
                        <p>{$vote['requestStatus']}</p>
                    {/foreach}

                </div>
            </div>
        </div>

        <!--{include file="../footer.tpl"} -->
    
    <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        {include file="../common-js.tpl"}
    </body>
</html>​
