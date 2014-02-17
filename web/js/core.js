$(document).ready(function()
    {
        var selectedCat = new Array();
        var selectedSubCat = new Array();
        
        $("#filter_btn").click(function(){
            
            $("#results").empty();
            $("input[name='category[]']:checked").each(function(){
                
                selectedCat.push($(this).val()) ;
            
        });
        
        $("input[name='subCategory[]']:checked").each(function(){
          
          selectedSubCat.push($(this).val()) ;
          
      });    
            
filteredResults(selectedSubCat);

})
            
       $("#search_btn").click(function(){
           
           $("#results").empty();
            search();       
            
        });
            
        });



function checkSubCategories(selectedCat){
 
 
}



function filteredResults(selectedSubCat){
    
        $.post("/searchFilter", 
                { 'subcat' : selectedSubCat}, 
                        function(courses){
        
        $.each(courses, function(key, value) { 
           
           $("#results").append('<h4><a href="/course?id="'+value.id+'>'+value.name+'</a></h4><p>'+value.description+'</p>');
            
        });
            
}, 'json');
    
}


function search(){
   
   
   $.post("/search", {
       string : $("#search_string").val()
       
    }, function(searchResult){
       
       if(searchResult[0] === "fail")
       {
            alert("Incorrect");
       }
       else
       {
         $("#results").empty();
         $.each(searchResult, function(key, value){
             $("#results").append('<h4><a href="/course">'+value.name+'</a></h4><p>'+value.description+'</p>')
         });        
           
       }}
   , 'json');
}