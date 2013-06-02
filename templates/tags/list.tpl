<!DOCTYPE html>
<html lang="en">
   
    {include file="../header.tpl" title="Rogo - {$sort_method} Tags"}

    <body class="_tags">
        {include file="../navbar.tpl"}
        
        <div class="container">
            <div class="row">
                <div class="span9">
                    <ul class="nav nav-tabs">
                        <li{if $sort_method == "popular"} class='active'{/if}><a href="{$BASE_URL}pages/tags/list.php?sort=popular">popular</a></li>
                        <li{if $sort_method == "name"} class='active'{/if}><a href="{$BASE_URL}pages/tags/list.php?sort=name">name</a></li>
                        <li{if $sort_method == "new"} class='active'{/if}><a href="{$BASE_URL}pages/tags/list.php?sort=new">new</a></li>
                    </ul>

                    <input type="text" class="find-user" onkeyup="findTags(this);" placeholder="type to find tags" />

                    <section id="tags">
                        {foreach $sorted_tags as $tag}
                            <div class="tag">
                                <a href="{$BASE_URL}pages/tags/view.php?tags={$tag.tagname}" class="post-tag">{$tag.tagname}</a>
                                <span class="tag-multiplier">&times {$tag.used}</span>
                                <p class="created" title="{$tag.creationdate}">{$tag.creationdate_p}</p>
                            </div>
                        {/foreach}
                        {if $total_number_tags != $number_tags}
                            <a href="{$BASE_URL}pages/tags/list.php?sort={$sort_method}&page={$page+1}" class="load-questions">Load more tags...</a>
                        {/if}
                    </section>
                </div>
                <div class="span3">
                    <div class="sidebar-content affix">
                        <a href="{$BASE_URL}pages/questions/add.php" class="ask-question-btn">Ask Question</a>
                        <div class="questions-count">
                            <h3 class="questions-counter">{$total_number_tags}</h3>
                            <p>tags</p>
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
