$(document).ready(function() {
    $("#editsubject_id").bind("change", (function() {
        $("#editsubject_desc").empty();
        populate_sub_desc();
    }));

    $("#editsub_btn").click(function() {
        editsubject();
    });

    $("#edittopic_sub_id").bind("change", (function() {
        $("#edit_topic_id").empty();
        populate_topic_topic();
    }));

    $("#edit_topic_id").bind("change", (function() {
        $("#edittopic_desc").empty();
        populate_top_desc();
    }));

    $("#edittopic_btn").click(function() {
        edittopic();
    });

    $("#editlesson_sub_id").bind("change", (function() {
        $("#editlesson_topic_id").empty();
        populate_lesson_topic();
    }));

    $("#editlesson_topic_id").bind("change", (function() {
        $("#editlesson_lesson_id").empty();
        populate_lesson_lesson();
    }));

    $("#editlesson_lesson_id").bind("change", (function() {
        $("#editlesson_lesson_type").empty();
        populate_lesson_type();
    }));

    $("#editlesson_btn").click(function() {
        editlesson();
    });

    $("#editinnerlesson_sub_id").bind("change", (function() {
        $("#editinnerlesson_topic_id").empty();
        populate_innerlesson_topic();
    }));

    $("#editinnerlesson_topic_id").bind("change", (function() {
        $("#editinnerlesson_lesson_id").empty();
        populate_innerlesson_lesson();
    }));

    $("#editinnerlesson_lesson_id").bind("change", (function() {
        $("#editinnerlesson_innerlesson_id").empty();
        populate_innerlesson_innerlesson();
    }));

    $("#editinnerlesson_innerlesson_id").bind("change", (function() {
        $("#editilname").empty();
        $("#editinnerlesson_innerlesson_content").empty();
        populatinnerlesson_innerlesson_content();
    }));

    $("#editinnerlesson_btn").click(function() {
        editinnerlesson();
    });
});

function editsubject()
{
    $.post("/instructor/edit/subject", {
        courseid: $("#editsub_btn").val(),
        subjectid: $("#editsubject_id").val(),
        subname: $("#editsname").val(),
        description: $("#editsubject_desc").val()
    }, function(data) {
        if (data[0] === "success")
        {
            window.location.href = '/instructor/create/topics/' + id;
        }
        else
        {
            alert("fails");
            window.location.href = '/instructor';
            //redirect to further page to enter courses
        }
    }
    , 'json');
}

function edittopic()
{
    $.post("/instructor/edit/topic", {
        topicid: $("#edit_topic_id").val(),
        topname: $("#edittname").val(),
        description: $("#edittopic_desc").val()
    }, function(data) {
        if (data[0] === "success")
        {
            window.location.href = '/instructor/create/topics/' + id;
        }
        else
        {
            alert("fails");
            window.location.href = '/instructor';
            //redirect to further page to enter courses
        }
    }
    , 'json');
}

function editlesson()
{
    $.post("/instructor/edit/lesson", {
        lessonid: $("#editlesson_lesson_id").val(),
        lesname: $("#editlname").val(),
        lestype: $("#editlesson_lesson_type").val()
    }, function(data) {
        if (data[0] === "success")
        {
            window.location.href = '/instructor/create/topics/' + id;
        }
        else
        {
            alert("fails");
            window.location.href = '/instructor';
            //redirect to further page to enter courses
        }
    }
    , 'json');
}

function editinnerlesson()
{
    $.post("/instructor/edit/innerlesson", {
        innerlessonid: $("#editinnerlesson_innerlesson_id").val(),
        innerlesname: $("#editilname").val(),
        contentid: $("#editinnerlesson_btn").val(),
        content: $("#editinnerlesson_innerlesson_content").val()
        
    }, function(data) {
        if (data[0] === "success")
        {
            window.location.href = '/instructor/create/topics/' + id;
        }
        else
        {
            alert("fails");
            window.location.href = '/instructor';
            //redirect to further page to enter courses
        }
    }
    , 'json');
}

function populate_sub_desc()
{
    $.post("/instructor/view/subdesc", {
        subject: $("#editsubject_id").val()

    }, function(data) {
        if (data[0] === "fail")
        {
            alert("No Description in selected subject");
        }
        else
        {
            $("#editsname").val(data[0].subjectname);
            $("#editsubject_desc").val(data[0].subjectdescription);
        }
    }, 'json');
}

function populate_topic_topic()
{
    $.post("/instructor/view/topics", {
        lesson_subject: $("#edittopic_sub_id").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No topics in selected subject");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#edit_topic_id").append($('<option></option>').val(data[i].id).html(data[i].topicname));
            }
        }
        $("#edittopic_desc").empty();
        populate_top_desc();
    }, 'json');
}

function populate_top_desc()
{
    $.post("/instructor/view/topdesc", {
        topic: $("#edit_topic_id").val()

    }, function(data) {
        if (data[0] === "fail")
        {
            alert("No Description in selected subject");
        }
        else
        {
            $("#edittname").val(data[0].topicname);
            $("#edittopic_desc").val(data[0].topicdescription);
        }
    }, 'json');
}

function populate_lesson_topic()
{
    $.post("/instructor/view/topics", {
        lesson_subject: $("#editlesson_sub_id").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No topics in selected subject");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#editlesson_topic_id").append($('<option></option>').val(data[i].id).html(data[i].topicname));
            }
        }
        $("#editlesson_lesson_id").empty();
        populate_lesson_lesson();
    }, 'json');
}

function populate_lesson_lesson()
{
    $.post("/instructor/view/lessons", {
        topic_lesson: $("#editlesson_topic_id").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No lessons in selected topic");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#editlesson_lesson_id").append($('<option></option>').val(data[i].id).html(data[i].lessonname));
            }
        }
        $("#editlesson_lesson_type").empty();
        populate_lesson_type();
    }, 'json');
}

function populate_lesson_type()
{
    $.post("/instructor/view/lessontype", {
        lesson: $("#editlesson_lesson_id").val()

    }, function(data) {
        if (data[0] === "fail")
        {
            alert("No Description in selected subject");
        }
        else
        {
            if (data[0].lessontype)
            {
                $("#editlname").val(data[0].lessonname);
                $("#editlesson_lesson_type").append($('<option></option>').val('1').html('Paid'));
                $("#editlesson_lesson_type").append($('<option></option>').val('0').html('Free'));
            }
            else
            {
                $("#editlname").val(data[0].lessonname);
                $("#editlesson_lesson_type").append($('<option></option>').val('0').html('Free'));
                $("#editlesson_lesson_type").append($('<option></option>').val('1').html('Paid'));
            }
        }
    }, 'json');
}

function populate_innerlesson_topic()
{
    $.post("/instructor/view/topics", {
        lesson_subject: $("#editinnerlesson_sub_id").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No topics in selected subject");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#editinnerlesson_topic_id").append($('<option></option>').val(data[i].id).html(data[i].topicname));
            }
        }
        $("#editinnerlesson_lesson_id").empty();
        populate_innerlesson_lesson();
    }, 'json');
}

function populate_innerlesson_lesson()
{
    $.post("/instructor/view/lessons", {
        topic_lesson: $("#editinnerlesson_topic_id").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No lessons in selected topic");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#editinnerlesson_lesson_id").append($('<option></option>').val(data[i].id).html(data[i].lessonname));
            }
        }
        $("#editinnerlesson_innerlesson_id").empty();
        populate_innerlesson_innerlesson();
    }, 'json');

}

function populate_innerlesson_innerlesson()
{
    $.post("/instructor/view/innerlessons", {
        topic_lesson: $("#editinnerlesson_lesson_id").val()

    }, function(data) {

        if (data[0] === "fail")
        {
            alert("No Inner Lessons in selected lesson");
        }
        else
        {
            for (var i = 0; i < data.length; i++)
            {
                $("#editinnerlesson_innerlesson_id").append($('<option></option>').val(data[i].id).html(data[i].innerlessonname));
            }
        }
        $("#editilname").empty();
        $("#editinnerlesson_innerlesson_content").empty();
        populatinnerlesson_innerlesson_content();
    }, 'json');
}

function populatinnerlesson_innerlesson_content()
{
    $.post("/instructor/view/content", {
        ilessonid: $("#editinnerlesson_innerlesson_id").val()

    }, function(data) {
        if (data[0] === "fail")
        {
            alert("No Content in selected inner lesson");
        }
        else
        {
            $("#editilname").val(data[0][0].innerlessonname);
            $("#editinnerlesson_btn").val(data[1][0].id);
            $("#editinnerlesson_innerlesson_content").val(data[1][0].content);
        }
    }, 'json');
}

