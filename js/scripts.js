var BASE_URL = "http://gnomo.fe.up.pt/~lbaw12201/rogo/";
var SPACE_KEY = 32;
var ENTER_KEY = 13;

$(document).ready(function() {
    console.log("JQuery working");
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
    var validUsernamePattern = /^[A-Z0-9a-z_.]{4,20}$/; /* allows spaces and underscores, digits, no special chars, 4 to 20 chars long */

    if(!validUsernamePattern.test(inputUsername.val())) { 
        $('div.inputUsername').addClass("error");
        $('div.inputUsername span.help-block').text('Invalid username. Between 4 and 20 alfanumeric chars, underscores and points.');
        return false;
    } else { /* valid */
        $('div.inputUsername').removeClass("error");
        $('div.inputUsername span.help-block').text('');
        return true;
    }
} 

function validateEmail() {

    var inputEmail = $('#inputEmail');
    var validEmailPattern = /^[[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;

    if(!validEmailPattern.test(inputEmail.val())) {
        $('div.inputEmail').addClass("error");
        $('div.inputEmail span.help-block').text('Invalid email');
        return false;
    } else {
        $('div.inputEmail').removeClass("error");
        $('div.inputEmail span.help-block').text('');
        return true;
    }
}

function validatePassword() {
    var inputPassword = $('#inputPassword');
    var validPasswordPattern = /^[a-zA-Z0-9!@#$%^&*-_]{6,30}$/ /* 6 to 30 chars, numbers, !@#$%^&*_ */

    if(!validPasswordPattern.test(inputPassword.val())) {
        $('div.inputPassword').addClass("error");
        $('div.inputPassword span.help-block').text('Between 6 and 30 alfanumeric chars and !@#$%^&*_');
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




