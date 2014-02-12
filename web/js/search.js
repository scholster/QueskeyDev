$(document).ready(function(){
   
   $("#submit").click(function(){
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
          alert(data);
          if(data[0]==="error"){
              alert("everything went wrong");
              
          }
      
      else {
          for(var a=0; a<=2; a++){
              if(data[a])
                  {
                      alert("it worked well");
                      var b = document.createElement('p');
                      b.innerHTML = data[a];
                      $( ".search" ).append( b );
                  }
                  else
                      {
                          alert("something went wrong"); 
                      }
          }
          
      }
      }, 'json')
   }
   