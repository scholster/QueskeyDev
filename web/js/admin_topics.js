$(document).ready(function(){
    
    $("#update_btn").click(function(){
       updatecourse();        
});

$("#addsub_btn").click(function(){
       storesubject();        
});

$("#addtopic_btn").click(function(){
       storetopic();        
});

$("#lesson_subject").bind("change",(function(){
    $("#lesson_topic").empty();
   populatetopic(); 
}));

$("#addlesson_btn").click(function(){
       storelesson();        
});

$("#content_subject").bind("change",(function(){
    $("#content_topic").empty();
   populatecontenttopic(); 
}));

$("#content_topic").bind("change",(function(){
    $("#content_lesson").empty();
   populatecontentlesson(); 
}));

$("#addcontent_btn").click(function(){
    var val= CKEDITOR.instances['contents'].getData();
       storecontent(val);        
});

$("#addquestion_btn").click(function(){
    var ques= CKEDITOR.instances['question'].getData();
    var soln= CKEDITOR.instances['question_solution'].getData();
       storequestion(ques,soln);        
});
});

function populatetopic()
{
    $.post("/instructor/view/topics", {
        lesson_subject: $("#lesson_subject").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No topics in selected subject");
        }
        else
        {

            for (var i = 0; i < data.length; i++)
            {
                $("#lesson_topic").append($('<option></option>').val(data[i].id).html(data[i].topicname));
            }
        }
    }, 'json');
}

function populatecontenttopic()
{
    $.post("/instructor/view/topics", {
        lesson_subject: $("#content_subject").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No topics in selected subject");
        }
        else
        {

            for (var i = 0; i < data.length; i++)
            {
                $("#content_topic").append($('<option></option>').val(data[i].id).html(data[i].topicname));
            }
        }
    }, 'json');
}

function populatecontentlesson()
{
    $.post("/instructor/view/lessons", {
        topic_lesson: $("#content_topic").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No topics in selected subject");
        }
        else
        {

            for (var i = 0; i < data.length; i++)
            {
                $("#content_lesson").append($('<option></option>').val(data[i].id).html(data[i].lessonname));
            }
        }
    }, 'json');
}

function updatecourse()
    {
        $.post("/instructor/update",{
            courseid: $("#update_btn").val(),
            category: $("#category").val(),
            coursename: $("#cname").val(),
            subcat: $("#subcategory").val(),
            description: $("#course_desc").val()
        },function(data){
            if(data[0]==="success")
                {
                    window.location.href = '/instructor/create/topics';
                }
                else
                    {
                        alert("fails");
                        window.location.href = '/instructor';
            //redirect to further page to enter courses
        }}
    ,'json');
    
    
    }
    
function storesubject()
    {
        $.post("/instructor/store/subject",{
            courseid: $("#addsub_btn").val(),
            subjectname: $("#sname").val(),
            subdescription: $("#subject_desc").val(),
            type: $("#subtype").val()
        },function(data){
            if(data[0]==="success")
                {
                    window.location.href = '/instructor/create/topics';
                }
                else
                    {
                        alert("fails");
                        window.location.href = '/instructor';
            //redirect to further page to enter courses
        }}
    ,'json');
    
    
    }
    
function storetopic()
    {
        $.post("/instructor/store/topic",{
            topicname: $("#tname").val(),
            topicdescription: $("#topic_desc").val(),
            subjectid: $("#topic_subject").val(),
            type: $("#topictype").val()
        },function(data){
            if(data[0]==="success")
                {
                    window.location.href = '/instructor/create/topics';
                }
                else
                    {
                        alert("fails");
                        window.location.href = '/instructor';
            //redirect to further page to enter courses
        }}
    ,'json');
    
    
    }    
    
function storelesson()
    {
        $.post("/instructor/store/lesson",{
            lessonname: $("#lname").val(),
            lesson_topicid: $("#lesson_topic").val(),
            lessontype: $("#lessontype").val()
        },function(data){
            if(data[0]==="success")
                {
                    window.location.href = '/instructor/create/topics';
                }
                else
                    {
                        alert("fails");
                        window.location.href = '/instructor';
            //redirect to further page to enter courses
        }}
    ,'json');
    
    
    } 
    
function storecontent(con)
    {
        $.post("/instructor/store/content",{
            contentname: $("#ctname").val(),
            content_lessonid: $("#content_lesson").val(),
            contenttype: $("#contenttype").val(),
            content:con
        },function(data){
            if(data[0]==="success")
                {
                    window.location.href = '/instructor/create/topics';
                }
                else
                    {
                        alert("fails");
                        window.location.href = '/instructor';
            //redirect to further page to enter courses
        }}
    ,'json');
    
    
    }   
    
function storequestion(ques,soln)
    {
        obj = {};
            
            $("#Options:input[name*='mytext[]']").each(function (index) {
                obj['option' + index] = $(this).val();
            });
            console.log(obj);
        $.post("/instructor/store/question",{
            options: obj,
            question: ques,
            topicid: $("#ques_lesson_topic").val(),
            /*option1: $("#option_1").val(),
            option2: $("#option_2").val(),
            option3: $("#option_3").val(),
            option4: $("#option_4").val(),
            option5: $("#option_5").val(),*/
            correctopt:  $("#answer").val(),
            solution: soln,
            level:  $("#ques_level").val()
        },function(data){
            if(data[0]==="success")
                {
                    window.location.href = '/instructor/create/topics';
                }
                else
                    {
                        alert("fails");
                        window.location.href = '/instructor';
            //redirect to further page to enter courses
        }}
    ,'json');  
    } 