<!DOCTYPE html>
<html lang="en">
   
    {include file="../header.tpl" title="Rogo - Send Private Message"}

    <body>

        {include file="../navbar.tpl"}

        <div class="container">
            <div class="row">
                <div class="span9">
                    {if $message_sent != ""}
                        <p>Message sent!</p>
                    {else}
                    <h2 class="send_pm pull-left">Send PM to <a href="{$BASE_URL}pages/users/view.php?id={$receiver.userid}">{$receiver.username}</a></h2>
                    <a class="pull-left avatar-frame" href="{$BASE_URL}pages/users/view.php?id={$receiver.userid}">
                            <img class="media-object" src="{$receiver.gravatar}" />
                    </a>
                    <form id="send_pm_form" class="form-horizontal" action="{$BASE_URL}actions/privmessages/add_action.php" method="post">
                        <div class="control-group inputMessageSubject">
                            <label class="control-label" for="inputMessageSubject">Subject</label>
                            <div class="controls">
                                <input type="text" id="inputMessageSubject" name="subject" onblur="validateSubject();" placeholder="what's the message subject?" value="{$s_values.subject}">
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="control-group inputMessageDetails">
                            <label class="control-label" for="inputMessageDetails">Details</label>
                            <div class="controls">
                                <textarea rows="8" placeholder="what's your message?" id="inputMessageDetails" name="details" onblur="validateMessageDetails();">{$s_values.details}</textarea>
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </form>
                    <button type="submit" form="send_pm_form" class="btn">Send message</button>
                    {/if}
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

        <script>
            var SPACE_KEY = 32;
            var COMMA_KEY = 188;
            $("#inputQuestionTags").keyup(function(event) {
                if(event.which == SPACE_KEY) {
                    var tag = $("#inputQuestionTags").val().toLowerCase();
                    tag = tag.replace(",","");
                    if(tag.length > 1) {

                        // check if tag already exists
                        var exists = false;
                        $("a.post-tag").each(function() {
                            if(!exists && $(this).text() == tag) {
                                exists = true;
                            }
                        });

                        if(!exists) {
                            $("div.tags_container").append("<a class='post-tag'>"+tag+"<i class='icon-remove' onclick='return removeThisTag(this)'></i></a>");

                            // disable input when 5 tags are added
                            if($("a.post-tag").length == 5) {
                                $("#inputQuestionTags").prop('disabled', true);
                                $("#inputQuestionTags").attr('placeholder', 'no more tags allowed');
                            }
                        }
                    }
                    $("#inputQuestionTags").val("");
                }
            })

            $('#inputQuestionTags').keydown(function(event){
                if(event.which == COMMA_KEY) {
                    return false;
                }
            })

            $("#ask_question_form").submit(function(event) {

                //event.preventDefault();
                if(validateQuestion() && validateQuestionDetails() && validateTags()) {
                    $("#inputQuestionTags").prop("disabled", false);
                    var tags = "";
                    // add each tag to a comma separated list
                    $("a.post-tag").each(function(index) {
                        var tag = $(this).text();
                        tag = tag.substr(0, tag.length-1);
                        if(index == 0) {
                            tags += tag;
                        } else {
                            tags += (","+tag);
                        }
                    });
                    $('#inputQuestionTags').val(tags);
                    return true;
                }
               return false;
            })
        </script>
    </body>
</html>?