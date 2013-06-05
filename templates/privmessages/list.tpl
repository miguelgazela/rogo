<!DOCTYPE html>
<html lang="en">

	{include file="../header.tpl" title="Rogo - {$s_username} Private Messages"}

    <body class="_user">     
		{include file="../navbar.tpl"}
		
        <div class="container">
            <div class="row">
                <div class="span9">
                    <h3 class="inbox-header">Inbox <span class="badge badge-info unread">{if $number_unread_messages != 0}{$number_unread_messages}{/if}</span></h3>
                    <div class="priv_messages">
                    	{if $number_private_messages == 0}
                    		<p>You don't have any private messages...</p>
                    	{else}
						{foreach $private_messages as $message}
						{if $message.read == false}
							<div class="priv_message unread" id="priv-message-{$message.usermsgid}">
						{else}
							<div class="priv_message" id="priv-message-{$message.usermsgid}">
						{/if}
						
						<img class="media-object pull-left" src="{$message.gravatar}">
						<div class="priv_message_info_1 pull-left">
							From <a href="{$BASE_URL}pages/users/view.php?id={$message.senderid}" class="username">{$message.username}</a>
							<span class="action-time" title="{$message.creatiodate}">{$message.creationdate_p}</span>
						</div>
						<div class="priv_message_info_2 pull-left">
							Subject: <a href="{$BASE_URL}pages/privmessages/view.php?id={$message.usermsgid}" class="subject">{$message.subject}</a>
							<span class="message-excerpt">{$message.body}</span>
						</div>
						<div class="priv_message_actions pull-left">
							<a href="{$BASE_URL}pages/privmessages/view.php?id={$message.usermsgid}">
								<div class="read">
									<i class="icon-bookmark"></i>
									<span class="action">read</span>
								</div>
							</a>
							<div class="remove" onclick="deletePM({$message.usermsgid}, 1);">
								<i class="icon-trash"></i>
								<span class="action">delete</span>
							</div>
						</div>
						</div>                                 
						{/foreach}
						{/if}
					</div>
					</div>
                <div class="span3">
                    <div class="sidebar-content affix">
                       <a href="#" class="ask-question-btn">Ask Question</a>
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