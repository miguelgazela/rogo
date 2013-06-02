<!DOCTYPE html>
<html lang="en">
   
    {include file="../header.tpl" title="Rogo - {$sort_method} Users"}

    <body class="_users">
        {include file="../navbar.tpl"}
        
        <div class="container">
            <div class="row">
                <div class="span9">
                    <ul class="nav nav-tabs">
                        <li{if $sort_method == "reputation"} class='active'{/if}><a href="{$BASE_URL}pages/users/list.php?sort=reputation">reputation</a></li>
                        <li{if $sort_method == "new"} class='active'{/if}><a href="{$BASE_URL}pages/users/list.php?sort=new">new</a></li>
                        <li{if $sort_method == "active"} class='active'{/if}><a href="{$BASE_URL}pages/users/list.php?sort=active">active</a></li>
                        <li{if $sort_method == "voters"} class='active'{/if}><a href="{$BASE_URL}pages/users/list.php?sort=voters">voters</a></li>
                        <li{if $sort_method == "popular"} class='active'{/if}><a href="{$BASE_URL}pages/users/list.php?sort=popular">popular</a></li>
                    </ul>

                    <input type="text" class="find-user" onkeyup="findUsers(this);" placeholder="type to find users" />

                    <section id="users">
                        {foreach $sorted_users as $user}
                            <div class="user">
                                <div class="media">
                                    <a class="pull-left avatar-frame" href="{$BASE_URL}pages/users/view.php?id={$user.userid}">
                                        <img class="media-object" src="{$user.gravatar}" />
                                    </a>
                                    <div class="media-body">
                                        <h6 class="media-heading username"><a href="{$BASE_URL}pages/users/view.php?id={$user.userid}">{$user.username}</a></h6>
                                        <p class="reputation"><i class="icon-trophy"></i> {$user.reputation}</p>
                                        {if $sort_method == "active"}
                                            <p class="member" title="{$user.lastaccess}">Last access: {$user.lastaccess_p}</p>
                                        {else if $sort_method == voters}
                                            <p class="member">Votes: {$user.upvotes + $user.downvotes}</p>
                                        {else if $sort_method == popular}
                                            <p class="member">Profile views: {$user.viewcount}</p>
                                        {else}
                                            <p class="member" title="{$user.registrationdate}">Member since: {$user.registrationdate_p}</p>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        {/foreach}
                        {if $total_number_users != $number_users}
                            <a href="{$BASE_URL}pages/users/list.php?sort={$sort_method}&page={$page+1}" class="load-questions">Load more users...</a>
                        {/if}
                    </section>
                </div>

                <div class="span3">
                    <div class="sidebar-content affix">
                        <a href="{$BASE_URL}pages/questions/add.php" class="ask-question-btn">Ask Question</a>
                        <div class="questions-count">
                            <h3 class="questions-counter">{$total_number_users}</h3>
                            <p>users</p>
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
