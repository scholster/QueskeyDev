$(document).ready(function(){
    $("#createquiz_subject").bind("change",(function(){
    $("#createquiz_topic").empty();
   populate_createquiz_topic();
   $("#rightValues").empty();
   populate_sub_questions();
}));

$("#createquiz_topic").bind("change",(function(){
    $("#createquiz_lesson").empty();
   populate_createquiz_lesson();
   $("#rightValues").empty();
   populate_topic_questions();
}));

$("#createquiz_lesson").bind("change",(function(){
   $("#rightValues").empty();
   populate_lesson_questions();
}));

$("#btnLeft").click(function () {
    var selectedItem = $("#rightValues option:selected");
    if(!((selectedItem).val()))
      alert("No Question Selected");
    else{
    $("#leftValues").append(selectedItem);
    add_ques(selectedItem);}
});

$("#btnRight").click(function () {
    var selectedItem = $("#leftValues option:selected");
    $("#rightValues").append(selectedItem);
    rem_ques(selectedItem);
});

$("#createquiz_btn").click(function(){
    var id=$("#createquiz_btn").val();
       storequiz(id);        
});

});

var quiz = new Array();

function add_ques(selectedItem)
{
    quiz.push((selectedItem).val());
}

function rem_ques(selectedItem)
{
    var removeItem = (selectedItem).val();
    quiz.splice( $.inArray(removeItem,quiz) ,1 );
}

function populate_createquiz_topic()
{
    $.post("/instructor/view/topics", {
        lesson_subject: $("#createquiz_subject").val()
       }, function(data) {

        if (data[0] === "fail")
        {
            alert("No topics in selected subject");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#createquiz_topic").append($('<option></option>').val(data[i].id).html(data[i].topicname));
            }
            $("#createquiz_lesson").empty();
   populate_createquiz_lesson();
        }
    }, 'json');
}

function populate_createquiz_lesson()
{
    $.post("/instructor/view/lessons", {
        topic_lesson: $("#createquiz_topic").val()
    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No lessons in selected topic");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#createquiz_lesson").append($('<option></option>').val(data[i].id).html(data[i].lessonname));
                if(i===(data.length-1))
                $("#createquiz_lesson").append($('<option></option>').val('0').html('None'));
            }
        }
    }, 'json');
}

function populate_sub_questions()
{
    $.post("/instructor/view/subquestions", {
        subid: $("#createquiz_subject").val()
    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No questions in selected subject");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#rightValues").append($('<option></option><br><br><br><br><br><br>').val(data[i].id).html(data[i].question));
            }
        }
    }, 'json');
}

function populate_topic_questions()
{
    $.post("/instructor/view/topicquestions", {
        topicid: $("#createquiz_topic").val()
    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No questions in selected topic");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#rightValues").append($('<option></option><br><br><br><br><br><br>').val(data[i].id).html(data[i].question));
            }
        }
    }, 'json');
}

function populate_lesson_questions()
{
    $.post("/instructor/view/lessonquestions", {
        topicid: $("#createquiz_topic").val(),
        lessonid: $("#createquiz_lesson").val()
    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No questions in selected lesson");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#rightValues").append($('<option></option><br><br><br><br><br><br>').val(data[i].id).html(data[i].question));
            }
        }
    }, 'json');
}

function storequiz(id)
    {
        $.post("/instructor/store/quiz",{
            courseid: id,
            topicid: $("#createquiz_topic").val(),
            lessonid: $("#createquiz_lesson").val(),
            quiztype: $("#createquiz_type").val(),
            subjectid: $("#createquiz_subject").val(),
            quesid: JSON.stringify(quiz)
        },function(data){
            if(data[0]==="success")
                {
                    window.location.href = '/instructor/create/topics/'+id;
                }
                else
                    {
                        alert("fails");
                        window.location.href = '/instructor';
            //redirect to further page to enter courses
        }}
    ,'json');
    }

