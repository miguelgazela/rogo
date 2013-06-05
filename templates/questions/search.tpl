<!DOCTYPE html>
<html lang="en">
   
    {include file="../header.tpl"}

    <body>
        {include file="../navbar.tpl"}
        
        <div class="container">
            <div class="row">
                
                <div class="span9 tagged">

                    <input type="text" class="find-user" onkeyup="findTaggedQuestions(this);" placeholder="type to narrow the search" />

                    <section id="questions">
                        {foreach $questions as $question}
                            <div class="question-summary">
                                <div class="summary">
                                    <div class="media">
                                        <a class="pull-left avatar-frame" href="{$BASE_URL}pages/users/view.php?id={$question.ownerid}">
                                            <img class="media-object" src="{$question.gravatar}" />
                                        </a>
                                        <div class="media-body">
                                            <h5 class="media-heading"><a href="{$BASE_URL}pages/questions/view.php?id={$question.questionid}" class="question-title">{$question.title}</a></h5>
                                            <p class="excerpt">{$question.body}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="question-stats pull-right">
                                    {if $question.answercount > 0}
                                        {if $question.acceptedanswerid != null}
                                            <div class="stats-answers answer-accepted">
                                        {else}
                                            <div class="stats-answers answered">
                                        {/if}
                                    {else}
                                        <div class="stats-answers">
                                    {/if}
                                        <span class="stat-number">{$question.answercount}</span>
                                        <span class="stat-description">answers</span>
                                    </div>
                                    {if $question.score != 0}
                                        <div class="stats-votes voted">
                                    {else}
                                        <div class="stats-votes">
                                    {/if}
                                        <span class="stat-number">{$question.score}</span>
                                        <span class="stat-description">votes</span>
                                    </div>
                                    {if $question.viewcount > 0}
                                        <div class="stats-views answered">
                                    {else}
                                        <div class="stats-views">
                                    {/if}
                                        <span class="stat-number">{$question.viewcount}</span>
                                        <span class="stat-description">views</span>
                                    </div>
                                </div>
                                
                                <div class="tags">
                                    {foreach $questiontags[$question@index] as $tag}
                                    <a href="#" class="post-tag">{$tag.tagname}</a>
                                    {/foreach}
                                </div>

                                <div class="started">
                                    <span class="action-time" title="{$question.creationdate}">asked {$question.creationdate_p}</span>
                                    <div class="user-info">
                                        <a href="{$BASE_URL}pages/users/view.php?id={$question.ownerid}" class="username">{$question.username}</a>
                                        <span class="reputation"><i class="icon-trophy"></i> {$question.reputation}</span>
                                    </div>
                                </div>
                            </div>
                        {foreachelse}
                            <p>No results...</p>
                        {/foreach}
                    </section>
                </div>
                <div class="span3">
                    <div class="sidebar-content affix">
                        <a href="{$BASE_URL}pages/questions/add.php" class="ask-question-btn">Ask Question</a>
                        <div class="questions-count">
                            <h3 class="questions-counter">{$total_number_questions}</h3>
                            <p>{if $total_number_questions == 1}question{else}questions{/if}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="search_words">
            {foreach $query_words as $query_word}
                {if $query_word != ""}
                    <span class="search_word hide">{$query_word}</span>
                {/if}
            {/foreach}
        </div>

        <!--{include file="../footer.tpl"} -->
    
    <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        {include file="../common-js.tpl"}

    </body>
</html>​
