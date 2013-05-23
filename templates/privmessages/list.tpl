<!DOCTYPE html>
<html lang="en">

	{include file="../header.tpl" title="Rogo - Private Messages"}

    <body>     
		{include file="../navbar.tpl"}
		
        <div class="container">
            <div class="row">
                <div class="span9">
                    <h3 class="inbox-header">Inbox <span class="badge badge-info unread">2</span></h3>
                    <div class="priv_messages">
						{foreach $private_messages as $message}
						{if $message.read == false}
							<div class="priv_message unread">
						{else}
							<div class="priv_message">
						{/if}
						
						<img class="media-object pull-left" src="../img/imageholder.png">
						<div class="priv_message_info_1 pull-left">
							<a href="#" class="username">$message.username</a>
							<span class="action-time">$message.creationdate</span>
						</div>
						<div class="priv_message_info_2 pull-left">
							<a href="#" class="subject">$message.creationdate</a>
							<span class="message-excerpt">A small excerpt of this message...</span>
						</div>
						<div class="priv_message_actions pull-left">
							<div class="read">
								<i class="icon-bookmark"></i>
								<span class="action">read</span>
							</div>
							<div class="remove">
								<i class="icon-trash"></i>
								<span class="action">delete</span>
							</div>
						</div>
						</div>                                 
					</div>
				</div>
                <div class="span3">
                    <div class="sidebar-content affix">
                        <a href="{$BASE_URL}pages/questions/add.php">Ask Question</a>
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