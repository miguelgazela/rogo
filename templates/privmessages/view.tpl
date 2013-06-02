<!DOCTYPE html>
<html lang="en">
   
    {include file="../header.tpl" title="Rogo - {$pm.subject}"}

    <body>
        {include file="../navbar.tpl"}
        
        <div class="container">
            <div class="row">
                <div class="span9">
                    <div class="pm_sender">
                        <a class="pull-left avatar-frame" href="{$BASE_URL}pages/users/view.php?id={$pm.senderid}">
                            <img class="media-object" src="{$pm.gravatar}" />
                        </a>
                        <div class="media-body">
                            <h5 class="media-heading"><a href="{$BASE_URL}pages/users/view.php?id={$pm.senderid}">{$pm.username}</a></h5>
                            <p class="pm_subject">{$pm.subject}</p>
                            <span class="action-time" title="{$pm.creationdate}">sent {$pm.creationdate_p}</span>
                        </div>
                    </div>

                    <p class="pm_body">{$pm.body}</p>

                    <button type="button" class="btn reply-pm" onclick="replyToPM({$pm.senderid});"><i class="icon-reply"></i> Reply</button>
                    <button type="button" class="btn delete-pm" onclick="deletePM({$pm.usermsgid}, 2);"><i class="icon-trash"></i> Delete</button>
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
    </body>
</html>​
