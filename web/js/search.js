$(document).ready(function(){
   
   $("#search_btn").click(function(){
       $("p").empty();
       search();       
   });
   
});


function search(){
   
   
   $.post("/search", {
       text: $("#search_string").val()
       
    }, function(data){
       
       if(data[0] === "fail")
       {
           //window.location.href = '/';
            alert("Incorrect");
       }
       else
       {
           for(var i=0;i<data.length;i++)
               {
                   var b = document.createElement("p");
                   b.innerHTML=data[i][0] + "    " + data[i][1];
                   $(".search").append(b);
                      
               }       
           
       }}
   , 'json');
}