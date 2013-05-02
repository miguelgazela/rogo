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
                    <div class="question-info">
                        <div class="question-side-container pull-left">
                            <img class="media-object" src="{$BASE_URL}img/imageholder.png">
                            <div class="vote-area">
                                <span class="vote-up text-center active"><i class="icon-sort-up icon-3x"></i></span>
                                <span class="vote-counter text-center large-number">{$question.score}</span>
                                <span class="vote-down text-center"><i class="icon-sort-down icon-3x"></i> </span>
                            </div>
                        </div>
                        <div class="question-container">
                            <p class="question-body">{$question.body}</p>
                            <!-- the tags are temporary, no time to implement in prototype -->
                            <div class="tags">
                                <a href="#" class="post-tag">java</a>
                                <a href="#" class="post-tag">python</a>
                                <a href="#" class="post-tag">json</a>
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
                        </div>
                    </div>
                    <div class="question-answers">
                        <div class="answers-header">
                            <h4><span class="anwers-counter">{$question.answercount}</span> Answers</h4>
                        </div>
                        <div class="answers-container">
                            {foreach $answers as $answer}
                            <div class="answer">
                                <div class="vote-area pull-left">
                                    <span class="vote-up text-center"><i class="icon-sort-up icon-3x"></i></span>
                                    <span class="vote-counter text-center">{$answer.score}</span>
                                    <span class="vote-down text-center"><i class="icon-sort-down icon-3x"></i> </span>
                                    <span class="accept-answer text-center accepted"><i class="icon-ok-circle icon-2x"></i></span>
                                </div>
                                <div class="answer-container">
                                    <p class="answer-body">{$answer.body}</p>
                                    <div class="started">
                                        <span class="action-time">{$answer.creationdate}</span>
                                        <div class="user-info">
                                            <a href="{$BASE_URL}pages/users/view.php?id={$answer.username}" class="username">{$answer.username}</a>
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
                                </div>
                            </div>
                            {foreachelse}
                                <p>No answers. This is extremely temporary</p>
                            {/foreach}
                        </div>
                    </div>
                </div>
                <div class="span3">
                    <div class="sidebar-content affix">
                        <a href="{$BASE_URL}pages/questions/add.php" class="ask-question-btn">Ask Question</a>
                    </div>
                </div>
            </div>
        </div>

        {include file="../footer.tpl"}
    
    <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        {include file="../common-js.tpl"}
    </body>
</html>​
