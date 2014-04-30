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

$("#content_lesson").bind("change",(function(){
    $("#content_innerlesson").empty();
   populatecontentinnerlesson(); 
}));

$("#innerquestion_subject").bind("change",(function(){
    $("#innerquestion_topic").empty();
   populateinnerquestion_topic(); 
}));

$("#innerquestion_topic").bind("change",(function(){
    $("#innerquestion_lesson").empty();
   populateinnerquestion_lesson(); 
}));

$("#innerlesson_subject").bind("change",(function(){
    $("#innerlesson_topic").empty();
   populateinnerlessontopic(); 
}));

$("#innerlesson_topic").bind("change",(function(){
    $("#innerlesson_lesson").empty();
   populateinnerlesson_lesson(); 
}));

$("#addcontent_btn").click(function(){
    var val= CKEDITOR.instances['contents'].getData();
       storecontent(val);        
});

$("#addinnerquestion_btn").click(function(){
    var ques= CKEDITOR.instances['innerquestion'].getData();
    var soln= CKEDITOR.instances['innerquestion_solution'].getData();
       storeinnerquestion(ques,soln);        
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
            $("#content_lesson").empty();
   populatecontentlesson();
        }
    }, 'json');
}



function populateinnerquestion_topic()
{
    $.post("/instructor/view/topics", {
        lesson_subject: $("#innerquestion_subject").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No topics in selected subject");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#innerquestion_topic").append($('<option></option>').val(data[i].id).html(data[i].topicname));
            }
        }
        $("#innerquestion_lesson").empty();
   populateinnerquestion_lesson();
    }, 'json'); 
}

function populateinnerquestion_lesson()
{
    $.post("/instructor/view/lessons", {
        topic_lesson: $("#innerquestion_topic").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No lessons in selected topic");
        }
        else
        {
            
            for (var i = 0; i < data.length; i++)
            {
                $("#innerquestion_lesson").append($('<option></option>').val(data[i].id).html(data[i].lessonname));
                if(i===(data.length-1))
                    $("#innerquestion_lesson").append($('<option></option>').val('0').html('None'));
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
        $("#content_innerlesson").empty();
   populatecontentinnerlesson();
    }, 'json');
}

function populatecontentinnerlesson()
{
    $.post("/instructor/view/innerlessons", {
        topic_lesson: $("#content_lesson").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No Inner Lessons in selected lesson");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#content_innerlesson").append($('<option></option>').val(data[i].id).html(data[i].innerlessonname));
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
    
function storesubject()
    {
        $.post("/instructor/store/subject",{
            courseid: $("#addsub_btn").val(),
            subjectname: $("#sname").val(),
            subdescription: $("#subject_desc").val()
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
    
function storetopic()
    {
        $.post("/instructor/store/topic",{
            topicname: $("#tname").val(),
            topicdescription: $("#topic_desc").val(),
            subjectid: $("#topic_subject").val()
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
    
function storelesson()
    {
        $.post("/instructor/store/lesson",{
            lessonname: $("#lname").val(),
            lesson_topicid: $("#lesson_topic").val(),
            lessontype: $("#lessontype").val()
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
    
function storecontent(con)
    {
        $.post("/instructor/store/content",{
            content_lessonid: $("#content_lesson").val(),
            content_inner_lessonid: $("#content_innerlesson").val(),
            content:con
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
    
/*function storequestion(ques,soln)
    {
        /*obj = {};
            
            $("#Options:input[name*='mytext[]']").each(function (index) {
                obj['option' + index] = $(this).val();
            });
            console.log(obj);*/
    /*var options = new Array();
    options.push($("#option_1").val());
    options.push($("#option_2").val());
    options.push($("#option_3").val());
    options.push($("#option_4").val());
    options.push($("#option_5").val());
        $.post("/instructor/store/question",{
            options: JSON.stringify(options),
            question: ques,
            topicid: $("#question_topic").val(),
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
    } */
    
    function populateinnerlessontopic()
{
    $.post("/instructor/view/topics", {
        lesson_subject: $("#innerlesson_subject").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No topics in selected subject");
        }
        else
        {

            for (var i = 0; i < data.length; i++)
            {
                $("#innerlesson_topic").append($('<option></option>').val(data[i].id).html(data[i].topicname));
            }
            $("#innerlesson_lesson").empty();
   populateinnerlesson_lesson();
        }
    }, 'json');
}

function populateinnerlesson_lesson()
{
    $.post("/instructor/view/lessons", {
        topic_lesson: $("#innerlesson_topic").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No topics in selected subject");
        }
        else
        {

            for (var i = 0; i < data.length; i++)
            {
                $("#innerlesson_lesson").append($('<option></option>').val(data[i].id).html(data[i].lessonname));
            }
        }
    }, 'json');
}

function storeinnerquestion(ques,soln)
    {

    var options = new Array();
    options.push($("#inneroption_1").val());
    options.push($("#inneroption_2").val());
    options.push($("#inneroption_3").val());
    options.push($("#inneroption_4").val());
    options.push($("#inneroption_5").val());
        $.post("/instructor/store/question",{
            options: JSON.stringify(options),
            question: ques,
            subjectid: $("#innerquestion_subject").val(),
            topicid: $("#innerquestion_topic").val(),
            lessonid: $("#innerquestion_lesson").val(),
            correctopt:  $("#inner_answer").val(),
            solution: soln,
            level:  $("#innerques_level").val()
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