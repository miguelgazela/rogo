<!DOCTYPE html>
<html lang="en">
   
    {include file="../header.tpl" title="Rogo - Add Question"}

    <body>

        {include file="../navbar.tpl"}

        <div class="container">
            <div class="row">
                <div class="span9">
                    <h2 class="add_new_question">Add Question</h2>
                    <form id="ask_question_form" class="form-horizontal" action="{$BASE_URL}actions/questions/add_action.php" method="post">
                        <div class="control-group inputQuestionTitle">
                            <label class="control-label" for="inputQuestionTitle">Title</label>
                            <div class="controls">
                                <input type="text" id="inputQuestionTitle" name="question" onblur="return validateQuestion()" placeholder="what's your question? Try to be specific." value="{$s_values.question}">
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="control-group inputQuestionDetails">
                            <label class="control-label" for="inputQuestionDetails">Details</label>
                            <div class="controls">
                                <textarea rows="8" placeholder="provide more details about your question" id="inputQuestionDetails" name="details" onblur="return validateQuestionDetails()">{$s_values.details}</textarea>
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="control-group inputQuestionTags">
                            <label class="control-label" for="inputQuestionTags">Tags</label>
                            <div class="controls">
                                <input type="text" autocomplete="off" name="tags" id="inputQuestionTags" onblur="return validateTags()" value="" placeholder="at least one tag, max 5 tags, separate with spaces">
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="tags_container"></div>
                    </form>
                    <button type="submit" form="ask_question_form" class="btn">Add Question</button>
                </div>

                <div class="span3">
                    <div class="sidebar-content affix">
                        <a href="https://google.com" class="ask-question-btn">Ask Question</a>
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
</html>​


