$(document).ready(function(){
    
$("#edit_btnLeft").click(function () {
    var selectedItem = $("#edit_rightValues option:selected");
    $("#edit_leftValues").append(selectedItem);
    edit_add_ques(selectedItem);
});

$("#edit_btnRight").click(function () {
    var selectedItem = $("#edit_leftValues option:selected");
    $("#edit_rightValues").append(selectedItem);
    edit_rem_ques(selectedItem);
});

$("#viewquiz_quiz").bind("change",(function(){
    $("#edit_leftValues").empty();
   populate_quiz_questions();
}));

$("#edit_quiz_subject").bind("change",(function(){
    $("#edit_quiz_topic").empty();
   populate_edit_quiz_topic();
   $("#edit_rightValues").empty();
   populate_edit_sub_questions();
}));

$("#edit_quiz_topic").bind("change",(function(){
    $("#edit_quiz_lesson").empty();
   populate_edit_quiz_lesson();
   $("#edit_rightValues").empty();
   populate_edit_topic_questions();
}));

$("#edit_quiz_lesson").bind("change",(function(){
   $("#edit_rightValues").empty();
   populate_edit_lesson_questions();
}));
});

var quiz = new Array();

function edit_add_ques(selectedItem)
{
    quiz.push((selectedItem).val());
}

function edit_rem_ques(selectedItem)
{
    var removeItem = (selectedItem).val();
    quiz.splice( $.inArray(removeItem,quiz) ,1 );
}

function populate_edit_quiz_topic()
{
    $.post("/instructor/view/topics", {
        lesson_subject: $("#edit_quiz_subject").val()
       }, function(data) {

        if (data[0] === "fail")
        {
            alert("No topics in selected subject");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#edit_quiz_topic").append($('<option></option>').val(data[i].id).html(data[i].topicname));
            }
            $("#edit_quiz_lesson").empty();
   populate_edit_quiz_lesson();
        }
    }, 'json');
}

function populate_edit_quiz_lesson()
{
    $.post("/instructor/view/lessons", {
        topic_lesson: $("#edit_quiz_topic").val()
    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No lessons in selected topic");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#edit_quiz_lesson").append($('<option></option>').val(data[i].id).html(data[i].lessonname));
                if(i===(data.length-1))
                $("#edit_quiz_lesson").append($('<option></option>').val('0').html('None'));
            }
        }
    }, 'json');
}

function populate_edit_sub_questions()
{
    $.post("/instructor/view/subquestions", {
        subid: $("#edit_quiz_subject").val()
    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No questions in selected subject");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#edit_rightValues").append($('<option></option><br><br><br><br><br><br>').val(data[i].id).html(data[i].question));
            }
        }
    }, 'json');
}

function populate_edit_topic_questions()
{
    $.post("/instructor/view/topicquestions", {
        topicid: $("#edit_quiz_topic").val()
    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No questions in selected topic");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#edit_rightValues").append($('<option></option><br><br><br><br><br><br>').val(data[i].id).html(data[i].question));
            }
        }
    }, 'json');
}

function populate_edit_lesson_questions()
{
    $.post("/instructor/view/lessonquestions", {
        topicid: $("#edit_quiz_topic").val(),
        lessonid: $("#edit_quiz_lesson").val()
    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No questions in selected lesson");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#edit_rightValues").append($('<option></option><br><br><br><br><br><br>').val(data[i].id).html(data[i].question));
            }
        }
    }, 'json');
}

function populate_quiz_questions()
{
    $.post("/instructor/view/quizquestions", {
        quizid: $("#viewquiz_quiz").val()
    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No questions in selected lesson");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#edit_leftValues").append($('<option></option><br><br><br><br><br><br>').val(data[i].id).html(data[i].question));
            }
            $("#editquiz_btn").val($("#viewquiz_quiz").val());
        }
    }, 'json');
}