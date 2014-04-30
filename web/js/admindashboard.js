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
            if(data[0]!== '0')
                {
                    var id=data[0];
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
                        $("#course_view").append('<h3><a href="'+value.url+'">'+value.name+'</a></h3><p>'+value.description+'</p>' );
                    });

                }
            },'json');
}

/*function validateForm()
{
    console.log("bcj");
var x=document.forms["CourseDetails"]["coursename"].value;
var y=document.forms["CourseDetails"]["category"].value;
var a=document.forms["CourseDetails"]["subcategory"].value;

if (x===null || x==="")
  {
  alert("Course name must be filled out");
  return false;
  }
if (y===null || y==="")
  {
  alert("Category must be filled out");
  return false;
  }
if (a===null || a==="")
  {
  alert("SubCategory must be filled out");
  return false;
  }
  else{
      return true;
  }

}*/