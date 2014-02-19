$(document).ready(function() {
var id;
    $("#paymentplan_btn").click(function() {
        var selected = $("input[type='radio'][name='course']:checked");
if (selected.length > 0) {
    id = selected.val();
}
        
        paymentplan(id);
    });

    $("#course_lists").click(function() {
        $("#course_views").empty();
        viewcourse();
    });

});

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
                    $.each(courses, function(key, value) {
                        $("#course_views").append('<h3><input type="radio" name="course" value='+ value.id + '>' + value.name + '</input></h3><p>' + value.description + '</p>');
                    });

                }
            }, 'json');
}

function paymentplan(id)
{
    $.post("/instructor/paymentplan/create", {
        course_id: id,
        price: $("#course_price").val(),
        expirytime: $("#expirytime").val(),
        discount: $("#discount").val(),
        resubprice:$("#resub_price").val(),
        paydescription: $("#payment_desc").val()
    }, function(data) {
        if (data[0] === "success")
        {
            alert("payment plan successfully created");
            window.location.href = '/instructor/paymentplan';
        }
        else
        {
            alert("failed to create payment plan. please try again");
            //redirect to further page to enter courses
        }
    }
    , 'json');

}