var BASE_URL = "http://gnomo.fe.up.pt/~lbaw12201/rogo/";
var SPACE_KEY = 32;
var ENTER_KEY = 13;

$(document).ready(function() {
    console.log("JQuery working");

    // display pretty dates
    $(".action-time").each(function(){
        $(this).text(getPrettyDate(new Date($(this).text())));
    });

    // define the show more action for comments
    $(".showMore").click(function(e){
        e.preventDefault();
        $(this).nextAll(".comment").removeClass('hide');
        $(this).remove();
    });

    // define the remove action for comments
    $(".comment i").click(function(e){
        var commentId = parseInt($(this).parent(".comment").attr("id").slice(8));
        $.post(BASE_URL+"ajax/comments/delete.php", {id: commentId}, function(response) {
            //console.log(response); // TODO remove
            if(response.requestStatus == "OK") {
                $("#comment-"+commentId).remove();
            } else {
                alert("Ups! An error occurred while trying to remove your comment. Please try again later."); // TODO improve warning quality
            }
        });
    });

    // define the remove action for answers
    $(".remove-answer").click(function(e){
        var answerId = parseInt($(this).parent(".vote-area").attr("id").slice(10));
        console.log("HERE!");
        $.post(BASE_URL+"ajax/answers/delete.php", {id: answerId}, function(response) {
            console.log(response); // TODO remove
            if(response.requestStatus == "OK") {
                $("#answer-"+answerId).remove();
            } else {
                alert("Ups! An error occurred while trying to remove your answer. Please try again later."); // TODO improve warning quality
            }
        });
    });

    $("span.vote-up").click(function(event){
        var postid = $(this).parent(".vote-area").attr("id").slice(10);
        $.post(BASE_URL+"ajax/votes/control.php", {id: postid, voteType: 1}, function(response){
            console.log(response); // TODO remove
            if(response.requestStatus == "OK") {
                var currentScore = parseInt($(event.target).siblings('.vote-counter').text());

                if(response.existed == false) {
                    $(event.target).addClass("active");
                    $(event.target).siblings('.vote-counter').text(currentScore+1);
                } else {
                    if(response.action == "updated") {
                        $(event.target).addClass("active");
                        $(event.target).siblings("span.vote-down").removeClass("active");
                        $(event.target).siblings('.vote-counter').text(currentScore+2);
                    } else {
                        $(event.target).removeClass("active");
                        $(event.target).siblings('.vote-counter').text(currentScore-1);
                    }
                }
            }
        });
    });

    $("span.vote-down").click(function(event){
        var postid = $(this).parent(".vote-area").attr("id").slice(10);

        $.post(BASE_URL+"ajax/votes/add.php", {id: postid, voteType: 2}, function(response){
            //console.log(response); // TODO remove
            if(response.requestStatus == "OK") {
                var currentScore = parseInt($(event.target).siblings('.vote-counter').text());

                if(response.existed == false) {
                    $(event.target).addClass("active");
                    $(event.target).siblings('.vote-counter').text(currentScore-1);
                } else {
                    if(response.action == "updated") {
                        $(event.target).addClass("active");
                        $(event.target).siblings("span.vote-up").removeClass("active");
                        $(event.target).siblings('.vote-counter').text(currentScore-2);
                    } else {
                        $(event.target).removeClass("active");
                        $(event.target).siblings('.vote-counter').text(currentScore+1);
                    }
                }
            }
        });
    });

    $(".vote-area").each(function(){
        var postid = $(this).attr("id").slice(10);
        $.get(BASE_URL+"ajax/votes/voted_on_post.php", {id: postid}, function(response){
            //console.log(response); // TODO remove
            if(response.voted == true) {
                if(response.type == 1) {
                    $("#vote-area-"+postid).children(".vote-up").addClass("active");
                } else if(response.type == 2) {
                    $("#vote-area-"+postid).children(".vote-down").addClass("active");
                }
            }
        });
    });

    // event handler for comment textareas
    $("textarea.inputComment").keypress(function(event){
        var comment = $(this).val();
        var inputCommentCtrl = $(this).parents("div.inputComment");

        if(event.which == ENTER_KEY && event.shiftKey) {
            // do nothing
        }
        else if (event.which == ENTER_KEY) {
           event.preventDefault(); // stops enter from creating a new line

            if(comment.length < 15) {
                inputCommentCtrl.addClass("error");
                inputCommentCtrl.children('span.help-block').text("Write at least 15 characters");
            } else {
                inputCommentCtrl.removeClass("error");
                inputCommentCtrl.children('span.help-block').text("");
                var postid = inputCommentCtrl.parents("div.comments").attr("id").slice(9);

                $.post(BASE_URL+"ajax/comments/add.php", {id: postid, text: comment}, function(response){
                    //console.log(response); // TODO remove
                    if(response.requestStatus == "OK") {
                        var newComment = "<div class='comment' id='comment-'"+response.commentId+">"+response.commentText;
                        newComment += " - <a href='"+BASE_URL+"pages/users/view.php?id="+response.commentOwnerId+"' class='username'>"+response.commentOwnerUsername+"</a><span class='action-time'> "+getPrettyDate(new Date())+"</span></div>";
                        inputCommentCtrl.parent("form").before(newComment);
                        $("#comments-"+postid+" textarea").val("");
                    } else {
                        alert("Ups! An error occurred while trying to add your comment. Please try again later."); // TODO improve warning quality
                    }
                });
            }
        }
    });

})

function getPrettyDate(date) {
    var rightNow = new Date();
    diff_sec = Math.round((rightNow.valueOf() - date.valueOf()) / 1000);
    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    if(diff_sec < 60) {
        if(diff_sec != 0)
            return diff_sec+"s ago";
        else
            return "1s ago";
    } else {
        diff_min = Math.round(diff_sec / 60);
        if(diff_min < 60) {
            return diff_min+"min ago";
        } else {
            diff_hour = Math.round(diff_min / 60);
            if(diff_hour < 24) {
                return diff_hour+"h ago";
            } else {
                diff_day = Math.round(diff_hour / 24);
                if(diff_day < 3) {
                    return diff_day+"d ago";
                } else {
                    if(date.getFullYear() == rightNow.getFullYear()) {
                        return monthNames[date.getMonth()]+" "+date.getDate()+" at "+date.getHours()+":"+date.getMinutes();
                    } else {
                        return date.getFullYear()+" "+monthNames[date.getMonth()]+" "+date.getDate()+" at "+date.getHours()+":"+date.getMinutes();
                    }
                }
            }
        }
    }
}

function addAnswer(questionID) {
    var answerText = $("#inputAnswer").val();
    var questionTitle = $(".question-header h3").text();

    if(answerText.length < 20) {
        $(".inputAnswer > span.help-block").text("You need to write at least 20 characters");
        $(".inputAnswer").addClass("error");
    } else {
        // clear possible previous error
        $(".inputAnswer > span.help-block").text("");
        $(".inputAnswer").removeClass("error");

        $.post(BASE_URL+'ajax/answers/add.php', {id: questionID, text: answerText, title: questionTitle}, function(response) {
            console.log(response); // TODO remove
            if(response.requestStatus == "OK") {
                var answer = "<div class='answer' id='"+response.answerID+"'>";
                answer += "<div class='vote-area pull-left'><span class='vote-up'></span>";
                answer += "<span class='vote-counter text-center'>0</span>";
                answer += "<span class='vote-down'></span>";
                answer += "<span class='accept-answer' text-center accepted'><i class='icon-ok-circle icon-2x'></i></span></div>";
                answer += "<div class='answer-container'><p class='answer-body'>"+response.answerText+"</p>";
                answer += "<div class='started'><span class='action-time'>"+getPrettyDate(new Date())+"</span>";
                answer += "<div class='user-info'><a href='"+BASE_URL+"pages/users/view.php?id="+response.userid+"' class='username'>"+response.username+"</a>";
                answer += "<span class='reputation'><i class='icon-trophy'></i> "+response.reputation+"</span></div></div></div>";
                $("div.answers-container").append(answer);

                $("#inputAnswer").val(""); // clear textarea

                // update answer counter
                var current = parseInt($("span.answers-counter").text()) + 1;
                if(current == 1) {
                    $('.answers-header > h4').html("<span class='answers-counter'>"+current+"</span> Answer");
                } else {
                    $('.answers-header > h4').html("<span class='answers-counter'>"+current+"</span> Answers");
                }
            } else {
                alert("Ups! An error occurred while trying to add your answer. Please try again later."); // TODO improve warning quality
            }
        });
    }
}

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




