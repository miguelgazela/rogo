<!DOCTYPE html>
<html lang="en">
   
    {include file="../header.tpl" title="Rogo - Edit Question"}

    <body>

        {include file="../navbar.tpl"}

        <div class="container">
            <div class="row">
                <div class="span9">
                    <h2 class="add_new_question">Edit Question</h2>
                    <form id="ask_question_form" class="form-horizontal edit-question" action="{$BASE_URL}actions/questions/edit_action.php" method="post">
                        <div class="control-group inputQuestionTitle">
                            <label class="control-label" for="inputQuestionTitle">Title</label>
                            <div class="controls">
                                <input type="text" id="inputQuestionTitle" name="question" onblur="return validateQuestion()" placeholder="what's your question? Try to be specific." value="{if $s_values != ''}{$s_values.question}{else}{$question.title}{/if}">
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="control-group inputQuestionDetails">
                            <label class="control-label" for="inputQuestionDetails">Details</label>
                            <div class="controls">
                                <textarea rows="8" placeholder="provide more details about your question" id="inputQuestionDetails" name="details" onblur="return validateQuestionDetails()">{if $s_values.details != ""}{$s_values.details}{else}{$question.body}{/if}</textarea>
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="control-group inputQuestionTags">
                            <label class="control-label" for="inputQuestionTags">Tags</label>
                            <div class="controls">
                                <input type="text" autocomplete="off" name="tags" id="inputQuestionTags" onblur="return validateTags()" value="" 
                                {if $tags|@count == 5}
                                    disabled="true" placeholder="delete at least one tag if you want to change them">
                                {else}
                                    placeholder="at least one tag, max 5 tags, separate with spaces">
                                {/if}

                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="tags_container">
                            {foreach $tags as $tag}
                                <a class="post-tag">{$tag.tagname} <i class='icon-remove' onclick='return removeThisTag(this)'></i></a>
                            {/foreach}
                        </div>
                    </form>
                    <button type="submit" form="ask_question_form" class="btn save">Save</button>
                    <button type="button" class="btn cancel">Cancel</button>
                </div>

                <div class="span3">
                    <div class="sidebar-content affix">
                        <a href="{$BASE_URL}pages/questions/add.php" class="ask-question-btn">Ask Question</a>
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


