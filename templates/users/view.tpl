<!DOCTYPE html>
<html lang="en">
   
    {include file="../header.tpl" title="Rogo - {$user.username}"}

    <body class="_user">
        {include file="../navbar.tpl"}
        
        <div class="container">
            <div class="row">
                
                <div class="span9">
                    <h4 class="profile-header">{$user.username}</h4>
                    <div class="profile-user-info">
                        <a class="pull-left big-avatar-frame" href="#">
                            <img class="media-object" src="{$user.gravatar}" />
                        </a>
                        <table>
                            <tbody>
                                <tr>
                                    <th>email</th>
                                    <td>{$user.email}</td>
                                </tr>
                                <tr>
                                    <th>member since</th>
                                    <td title="{$user.registrationdate}">{$user.registrationdate_p}</td>
                                </tr>
                                <tr>
                                    <th>last seen</th>
                                    <td title="{$user.lastaccess}">{$user.lastaccess_p}</td>
                                </tr>
                                <tr>
                                    <th>reputation</th>
                                    <td>{$user.reputation}</td>
                                </tr>
                                <tr>
                                    <th>profile views</th>
                                    <td>{$user.viewcount}</td>
                                </tr>
                                <tr>
                                    <th>up votes</th>
                                    <td>{$user.upvotes}</td>
                                </tr>
                                <tr>
                                    <th>down votes</th>
                                    <td>{$user.downvotes}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <ul class="nav nav-tabs">
                        <li{if $sort_method == "questions"} class="active"{/if}><a href="{$BASE_URL}pages/users/view.php?id={$user.userid}&sort=questions">{if $sort_method == "questions" && $total_number_questions == 1}1 question{elseif $sort_method == "questions"}{$total_number_questions} questions{else}questions{/if}</a></li>
                        <li{if $sort_method == "answers"} class="active"{/if}><a href="{$BASE_URL}pages/users/view.php?id={$user.userid}&sort=answers">{if $sort_method == "answers" && $total_number_answers == 1}1 answer{elseif $sort_method == "answers"}{$total_number_answers} answers{else}answers{/if}</a></li>
                        {if $s_user_id == $user.userid}
                            <li{if $sort_method == "drafts"} class="active"{/if}><a href="{$BASE_URL}pages/users/view.php?id={$user.userid}&sort=drafts">{if $sort_method == "drafts" && $total_number_drafts == 1}1 draft{elseif $sort_method == "drafts"}{$total_number_drafts} drafts{else}drafts{/if}</a></li>
                        {/if}
                    </ul>

                    {if $sort_method == "questions" && $total_number_questions > 1}
                        <input type="text" class="find-user" onkeyup="findQuestions(this);" placeholder="type to find questions" />
                    {elseif $sort_method == "answers" && $total_number_answers > 1}
                        <input type="text" class="find-user" onkeyup="findAnswers(this);" placeholder="type to find answers" />
                    {elseif $sort_method == "drafts" && $total_number_drafts > 1}
                        <input type="text" class="find-user" onkeyup="findDrafts(this);" placeholder="type to find drafts" />
                    {/if}

                    <div class="data">
                        {if $sort_method == "questions"}
                            {foreach $questions as $question}
                                <div class="question-summary">
                                    <div class="summary">
                                        <h5 class="media-heading">
                                            <a href="{$BASE_URL}pages/questions/view.php?id={$question.questionid}" class="question-title">{$question.title}</a>
                                        </h5>
                                        <p class="excerpt">{$question.body}</p>
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
                                        {foreach $tags[$question@index] as $tag}
                                            <a href="#" class="post-tag">{$tag.tagname}</a>
                                        {/foreach}
                                    </div>
                                    <div class="started">
                                        <span class="action-time" title="{$question.creationdate}">asked {$question.creationdate_p}</span>
                                    </div>
                                </div>
                            {foreachelse}
                                <p>No questions...</p>
                            {/foreach}
                            {if $total_number_questions != $number_presented_questions}
                                <a href="{$BASE_URL}pages/users/view.php?id={$question.ownerid}&sort=questions&page={$page+1}" class="load-questions">Load more questions...</a>
                            {/if}
                        {elseif $sort_method == "answers"}
                            {foreach $answers as $answer}
                                <div class="answer">
                                    <div class="answer-container">
                                        <p class="answer-body">{$answer.body}<a href="{$BASE_URL}pages/questions/view.php?id={$answer.questionid}"> - go to question</a>
                                        </p>
                                        <div class="started">
                                            <span class="action-time" title="{$answer.creationdate}">answered {$answer.creationdate_p}</span>
                                        </div>
                                    </div>
                                </div>
                            {foreachelse}
                                <p>No answers...</p>
                            {/foreach}
                            {if $total_number_answers != $number_presented_answers}
                                <a href="{$BASE_URL}pages/users/view.php?id={$answer.ownerid}&sort=answers&page={$page+1}" class="load-questions">Load more answers...</a>
                            {/if}
                        {elseif $sort_method == "drafts"}
                            {foreach $drafts as $draft}
                                <div class="answer">
                                    <div class="answer-container">
                                        <p class="answer-body">{$draft.body}<a href="{$BASE_URL}pages/questions/view.php?id={$draft.questionid}"> - go to question</a>
                                        </p>
                                        <div class="started">
                                            <span class="action-time" title="{$draft.lastactivitydate}">last saved {$draft.lastactivitydate_p}</span>
                                        </div>
                                    </div>
                                </div>
                            {foreachelse}
                                <p>No drafts...</p>
                            {/foreach}
                        {/if}
                    </div>
                </div>
                <div class="span3">
                    <div class="sidebar-content affix">
                        <a href="{$BASE_URL}pages/questions/add.php" class="ask-question-btn">Ask Question</a>
                        <div class="user-actions">
                            {if $s_user_id != $user.userid}
                                <a href="{$BASE_URL}pages/privmessages/add.php?id={$user.userid}" class="ask-question-btn">Send message</a>
                            {/if}
                        </div>
                    </div>
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
