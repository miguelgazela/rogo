<!DOCTYPE html>
<html lang="en">
   
    {include file="../header.tpl" title="Rogo - {$sort_method} Questions"}

    <body>
        {include file="../navbar.tpl"}
        
        <div class="container">
            <div class="row">
                <div class="span9">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#">newest</a></li>
                        <li><a href="#">votes</a></li>
                        <li><a href="#">active</a></li>
                        <li><a href="#">unanswered</a></li>
                    </ul>

                    <section id="questions">
                        {foreach $sorted_questions as $question}
                            <div class="question-summary">
                                <div class="summary">
                                    <div class="media">
                                        <a class="pull-left avatar-frame" href="#">
                                            <img class="media-object" src="{$BASE_URL}img/imageholder.png" />
                                        </a>
                                        <div class="media-body">
                                            <h5 class="media-heading"><a href="{$BASE_URL}pages/questions/view.php?id={$question.questionid}">{$question.title}</a></h5>
                                            <p class="excerpt">{$question.body}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="question-stats pull-right">
                                    <div class="stats-answers answered">
                                        <span class="stat-number">{$question.answercount}</span>
                                        <span class="stat-description">answers</span>
                                    </div>
                                    <div class="stats-votes voted">
                                        <span class="stat-number">{$question.score}</span>
                                        <span class="stat-description">votes</span>
                                    </div>
                                    <div class="stats-views viewed">
                                        <span class="stat-number">{$question.viewcount}</span>
                                        <span class="stat-description">views</span>
                                    </div>
                                </div>
                                
                                <div class="tags">
                                    <a href="#" class="post-tag">java</a>
                                    <a href="#" class="post-tag">python</a>
                                    <a href="#" class="post-tag">css</a>
                                </div>

                                <div class="started">
                                    <span class="action-time">{$question.creationdate}</span>
                                    <div class="user-info">
                                        <a href="{$BASE_URL}pages/users/view.php?id={$question.ownerid}" class="username">{$question.username}</a>
                                        <span class="reputation"><i class="icon-trophy"></i> {$question.reputation}</span>
                                    </div>
                                </div>
                            </div>
                        {foreachelse}
                            <p>No questions. This is extremely temporary</p>
                        {/foreach}
                    </section>
                </div>
                <div class="span3">
                    <div class="sidebar-content affix">
                        <a href="{$BASE_URL}pages/questions/add.php" class="ask-question-btn">Ask Question</a>
                        <div class="questions-count">
                            <h3 class="questions-counter">{$number_questions}</h3>
                            <p>questions</p>
                        </div>
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
