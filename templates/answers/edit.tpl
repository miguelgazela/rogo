<!DOCTYPE html>
<html lang="en">
   
    {include file="../header.tpl" title="Rogo - Edit Answer"}

    <body>

        {include file="../navbar.tpl"}

        <div class="container">
            <div class="row">
                <div class="span9">
                    <h2 class="add_new_question">Edit Answer</h2>
                    <form id="ask_question_form" class="form-horizontal ask-question" action="{$BASE_URL}actions/answers/edit_action.php" method="post">
                        <div class="control-group inputQuestionDetails">
                            <label class="control-label" for="inputQuestionDetails">Details</label>
                            <div class="controls">
                                <textarea rows="8" id="inputAnswer" name="answer" onblur="return validateAnswerText()">{if $s_values.answer != ""}{$s_values.answer}{else}{$answer.body}{/if}</textarea>
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </form>
                    <button type="submit" form="ask_question_form" class="btn save">Save</button>
                    <button type="button" class="btn cancel" onclick="cancelAnswerEdit({$answer.questionid});">Cancel</button>
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


