$(document).ready(function(){


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
            
            
        }
    ,'json');
    }