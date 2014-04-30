$(document).ready(function(){
    $("#dataquestion_subject").bind("change",(function(){
    $("#dataquestion_topic").empty();
   populate_dataquestion_topic(); 
}));

$("#dataquestion_topic").bind("change",(function(){
    $("#dataquestion_lesson").empty();
   populate_dataquestion_lesson(); 
}));

$("#add_dataquestion_btn").click(function(){
    
    var data= CKEDITOR.instances['question_data'].getData();
    
    var ques_1= CKEDITOR.instances['dataquestion_1'].getData();
    var soln_1= CKEDITOR.instances['dataquestion_solution_1'].getData();
    
    var ques_2= CKEDITOR.instances['dataquestion_2'].getData();
    var soln_2= CKEDITOR.instances['dataquestion_solution_2'].getData();
    
    var ques_3= CKEDITOR.instances['dataquestion_3'].getData();
    var soln_3= CKEDITOR.instances['dataquestion_solution_3'].getData();
    
    store_dataquestion(data,ques_1,soln_1,ques_2,soln_2,ques_3,soln_3);        
});

});

function populate_dataquestion_topic()
{
    $.post("/instructor/view/topics", {
        lesson_subject: $("#dataquestion_subject").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No topics in selected subject");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#dataquestion_topic").append($('<option></option>').val(data[i].id).html(data[i].topicname));
            }
            $("#dataquestion_lesson").empty();
   populate_dataquestion_lesson();
        }
    }, 'json');
}

function populate_dataquestion_lesson()
{
    $.post("/instructor/view/lessons", {
        topic_lesson: $("#dataquestion_topic").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No lessons in selected topic");
        }
        else
        {
            
            for (var i = 0; i < data.length; i++)
            {
                $("#dataquestion_lesson").append($('<option></option>').val(data[i].id).html(data[i].lessonname));
                if(i===(data.length-1))
                    $("#dataquestion_lesson").append($('<option></option>').val('0').html('None'));
            }
        }
    }, 'json');
}

function store_dataquestion(data,ques_1,soln_1,ques_2,soln_2,ques_3,soln_3)
    {
        /*obj = {};
            
            $("#Options:input[name*='mytext[]']").each(function (index) {
                obj['option' + index] = $(this).val();
            });
            console.log(obj);*/
    var options_1 = new Array();
    options_1.push($("#data_option_1_1").val());
    options_1.push($("#data_option_2_1").val());
    options_1.push($("#data_option_3_1").val());
    options_1.push($("#data_option_4_1").val());
    options_1.push($("#data_option_5_1").val());
    
    var options_2 = new Array();
    options_2.push($("#data_option_1_2").val());
    options_2.push($("#data_option_2_2").val());
    options_2.push($("#data_option_3_2").val());
    options_2.push($("#data_option_4_2").val());
    options_2.push($("#data_option_5_2").val());
    
    var options_3 = new Array();
    options_3.push($("#data_option_1_3").val());
    options_3.push($("#data_option_2_3").val());
    options_3.push($("#data_option_3_3").val());
    options_3.push($("#data_option_4_3").val());
    options_3.push($("#data_option_5_3").val());
    
        $.post("/instructor/store/dataquestion",{
            subjectid: $("#dataquestion_subject").val(),
            topicid: $("#dataquestion_topic").val(),
            lessonid: $("#dataquestion_lesson").val(),
            level:  $("#dataques_level").val(),
            ques_data: data,
            question_1: ques_1,
            options_1: JSON.stringify(options_1),
            correctopt_1:  $("#data_answer_1").val(),
            solution_1: soln_1,
            question_2: ques_2,
            options_2: JSON.stringify(options_2),
            correctopt_2:  $("#data_answer_2").val(),
            solution_2: soln_2,
            question_3: ques_3,
            options_3: JSON.stringify(options_3),
            correctopt_3:  $("#data_answer_3").val(),
            solution_3: soln_3
            
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