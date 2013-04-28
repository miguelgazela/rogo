var BASE_URL = "http://gnomo.fe.up.pt/~lbaw12201/rogo/";
var SPACE_KEY = 32;

$(document).ready(function() {
    console.log("JQuery working");

    $("#signup_form").submit(function() {
        return (validateUsername() && validateEmail() && validatePassword() && confirmPassword());
    })

    $("#signin_form").submit(function() {
        return (validateUsername() && validatePassword());
    })

    $("#ask_question_form").submit(function() {

        if(validateQuestion() && validateQuestionDetails() && validateTags()) {
            var tags = "";

            // add each tag to a comma separated list
            $("a.post-tag").each(function(index) {
                if(index == 0) {
                    var tag = $(this).text();
                    tag = tag.substr(0, tag.length-1);
                    tags += tag;
                } else {
                    tags += ("," + $(this).text());
                }
            });

            return true;
        }
       return false;
    })

    $("#inputQuestionTags").keyup(function(event) {
        if(event.which == SPACE_KEY) {
            var tag = $("#inputQuestionTags").val();
            if(tag.length > 1) {

                // check if tag already exists
                var exists = false;
                $("a.post-tag").each(function() {
                    if(!exists && $(this).text().match(tag)) {
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
})

function validateTags() {
    if($("a.post-tag").length == 0) {
        $("div.inputQuestionTags").addClass("error");
        $("div.inputQuestionTags span.help-block").text("You have to enter at least one tag.");
        return false;
    } else {
        $("div.inputQuestionTags").removeClass("error");
        $("div.inputQuestionTags span.help-block").text("");
        return true;
    }
}

function removeThisTag(event) {
    if($("a.post-tag").length == 5) {
        $("#inputQuestionTags").prop('disabled', false);
        $("#inputQuestionTags").attr('placeholder', 'at least one tag, max 5 tags');
    }
    $(event).parent().remove();
}

function validateUsername() {
    var inputUsername = $('#inputUsername');
    var validUsernamePattern = /^[A-Z0-9a-z_ .]{4,20}$/; /* allows spaces and underscores, digits, no special chars, 4 to 20 chars long */

    if(!validUsernamePattern.test(inputUsername.val())) { 
        $('div.inputUsername').addClass("error");
        $('div.inputUsername span.help-block').text('Only alfanumeric chars, spaces and underscores. Between 4 and 20 chars.');
        return false;
    } else { /* valid */
        $('div.inputUsername').removeClass("error");
        $('div.inputUsername span.help-block').text('');
        return true;
    }
} 

function validateEmail() {

    $("#inputEmail").verimail({messageElement: "div.inputEmail span.help-inline"});
    return true; /* TODO NOT WORKING

    if($('#inputEmail').getVerimailStatus() < 0)
        console.log("invalid email");
    } else {
        console.log($('input#inputEmail').getVerimailStatus());
        console.log("valid email");
    }
    */
}

function validatePassword() {
    var inputPassword = $('#inputPassword');
    var validPasswordPattern = /^[a-zA-Z0-9!@#$%^&*-_]{6,30}$/ /* 6 to 30 chars, numbers, !@#$%^&*_ */

    if(!validPasswordPattern.test(inputPassword.val())) {
        $('div.inputPassword').addClass("error");
        $('div.inputPassword span.help-block').text('Between 6 and 30 chars, digits and !@#$%^&*_');
        return false;
    } else {
        $('div.inputPassword').removeClass("error");
        $('div.inputPassword span.help-block').text('');
        return true;
    }
}

function confirmPassword() {
    if($('#inputPassword').val() != $('#inputPasswordConfirmation').val()) {
        $('div.confirmPassword').addClass("error");
        $('div.confirmPassword span.help-block').text('The passwords don\'t match');
        return false;
    } else {
        $('div.confirmPassword').removeClass("error");
        $('div.confirmPassword span.help-block').text('');
        return true;
    }
}

function validateQuestion() {
    // at least 15 characters
    var numChars = $('#inputQuestionTitle').val().length;
    if(numChars < 15) {
        $('div.inputQuestionTitle').addClass("error");
        $('div.inputQuestionTitle span.help-block').text("Title must be at least 15 characters long.");
        return false;
    } else {
        $('div.inputQuestionTitle').removeClass("error");
        $('div.inputQuestionTitle span.help-block').text("");
        return true;
    }
    
}

function validateQuestionDetails() {
    console.log("validateQuestionDetails");
    // at least 30 characters
    var numChars = $('#inputQuestionDetails').val().length;
    if(numChars < 30) {
        $('div.inputQuestionDetails').addClass("error");
        $('div.inputQuestionDetails span.help-block').text("Details must be at least 30 characters. You entered "+numChars+".")
        return false;
    } else {
        $('div.inputQuestionDetails').removeClass("error");
        $('div.inputQuestionDetails span.help-block').text("");
        return true;
    }
}




