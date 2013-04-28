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
                                <input type="text" id="inputQuestionTitle" name="question" onblur="return validateQuestion()" placeholder="what's your question? Try to be specific.">
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="control-group inputQuestionDetails">
                            <label class="control-label" for="inputQuestionDetails">Details</label>
                            <div class="controls">
                                <textarea rows="8" placeholder="provide more details about your question" id="inputQuestionDetails" name="details" onblur="return validateQuestionDetails()"></textarea>
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="control-group inputQuestionTags">
                            <label class="control-label" for="inputQuestionTags">Tags</label>
                            <div class="controls">
                                <input type="text" name="tags" id="inputQuestionTags" onblur="return validateTags()" value="" placeholder="at least one tag, max 5 tags">
                            </div>
                            <span class="help-block"></span>
                        </div>
                        <div class="tags_container"></div>
                        <label class="checkbox">
                            <input type="checkbox" value="" name="anonymously">
                            Add Anonymously
                        </label>
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
    </body>
</html>​


