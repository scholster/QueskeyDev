$(document).ready(function(){

$("#course_list").click(function(){
    $("#course_view").empty();
  viewcourse();  
});

$("#category").bind("change",(function(){
    $("#subcategory").empty();
   populate(); 
}));

$("#submit_btn").click(function(){
       storecourse();       
   });
   

   
});

function populate()
{
    $.post("/instructor", {
        categoryid: $("#category").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No subcategory");
        }
        else
        {

            for (var i = 0; i < data.length; i++)
            {
                $("#subcategory").append($('<option></option>').val(data[i].id).html(data[i].name));
            }
        }
    }, 'json');
}

function storecourse()
    {
        $.post("/instructor/store",{
            coursename: $("#cname").val(),
            subcat: $("#subcategory").val(),
            description: $("#course_desc").val()
        },function(data){
            
            //redirect to further page to enter courses
        }
    ,'json');
    }
    
function viewcourse()
{
    $.post("/instructor/view",
            function(courses) {
                if (courses[0] === "fail")
                {
                    alert("No courses created by you.");
                }
                else
                {
                    $.each(courses,function(key,value){
                        $("#course_view").append('<h3><a href="/course_id='+value.id+'">'+value.name+'</a></h3><p>'+value.description+'</p>' );
                    });

                }
            },'json');
}