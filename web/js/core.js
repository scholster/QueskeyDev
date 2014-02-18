$(document).ready(function()
    {
        var selectedCat = new Array();
        var selectedSubCat = new Array();
        
        $("#filter_btn").click(function(){
            
            $("input[name='category[]']:checked").each(function(){
                
                selectedCat.push($(this).val()) ;
            
        });
        
        $("input[name='subCategory[]']:checked").each(function(){
          
          selectedSubCat.push($(this).val()) ;
          
      });    
$("#default_courses").empty();            
filteredResults(selectedSubCat);

})
            
       $("#search_btn").click(function(){
           $("#default_courses").empty();
           search();       
            
        });
            
        });



function checkSubCategories(selectedCat){
 
 
}



function filteredResults(selectedSubCat){
    
        
        $.post("/searchFilter", 
                { 'subcat' : selectedSubCat}, 
                        function(courses){
        $("#filtered_courses").empty();
        $("#filtered_courses").append('<div id="course_list">Courses :<br></div>');
        $.each(courses, function(key, value) { 
           $("#course_list").append('<ul><h4><a href="/course_id='+value.id+'">'+value.name+'</a></h4><li>'+value.description+'</li></ul>'); 
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
         $("#filtered_courses").empty();
         $("#filtered_courses").append('<div id="course_list">Courses :<br></div>');
         $.each(searchResult, function(key, value){
             $("#course_list").append('<h4><a href="/course_id='+value.id+'">'+value.name+'</a></h4><p>'+value.description+'</p>');                   
     });        
           
       }}
   , 'json');
}