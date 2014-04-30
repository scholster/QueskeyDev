$(document).ready(function(){
    
    $("#addinnerlesson_btn").click(function(){
       storeinnerlesson();        
});


});



function storeinnerlesson()
    {
        $.post("/instructor/store/innerlesson",{
            innerlesson_lessonid: $("#innerlesson_lesson").val(),
            innerlesson_name: $("#ilname").val(),
            innerlesson_type: $("#innerlessontype").val()
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