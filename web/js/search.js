$(document).ready(function(){
   
<<<<<<< HEAD
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
=======
   $("#submit").click(function(){
       $("p").empty();
       var jee = false;
       var cat = false;
       var gre = false;
       if($("#jee").is(':checked')){
           jee = "jee";
       }
       
       if($("#cat").is(':checked')){
           cat = "cat";
       }   
       
       if($("#gre").is(':checked')){
           gre = "gre";
       }
       
      searchFilter(jee, cat, gre);
   });
});
   
   function searchFilter(jee, cat, gre){
      
      $.post("/searchFilter", {
                                '0' : jee,
                                '1' : cat,
                                '2' : gre
      }, function(data){
          var a = 0;
          if(data[0]==="error"){
              alert("everything went wrong");
              
          }
      
      else {
          while(data[a]){
              if(data[a])
                  {
                      var b = document.createElement('p');
                      b.innerHTML = data[a];
                      $( ".search" ).append( b );
                      a++;
                  }
                  else
                      {
                          alert("something went wrong");
                          a++;
                      }
          }
          
      }
      }, 'json')
   }
   
>>>>>>> origin/akshat
