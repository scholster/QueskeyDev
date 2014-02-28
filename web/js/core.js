$(document).ready(function()
    {
        
        $('input[name="category[]"]').click(function() { 
            if ($(this).is(':checked')) {
                checkSubCategories($(this).val());
            } 
            
            else 
            {
                uncheckSubCategories($(this).val());
            }
        });
        
        
        
        $("#filter_btn").click(function(){
                           
            var selectedSubCat = new Array();
        
            $("input[name='subCategory[]']:checked").each(function(){
          
                selectedSubCat.push($(this).val()) ;
          
            });
            $("#default_courses").html('');
            $("#filtered_courses").html('');            
            filteredResults(selectedSubCat);

        });
        
        
        
        
        $("#search_btn").click(function(){
           
           $("#default_courses").html('');
           $("#filtered_courses").html('');
           search();       
            
        });
        
        $("#myCourses").hide();
        $("#myCourses_btn").click(function(){
            $("#myCourses").toggle();
        });
        
        });






function checkSubCategories(selectedCat){
    
    $("."+selectedCat).attr('checked', true);
    return;
    
 }



function uncheckSubCategories(selectedCat){
    
    $("."+selectedCat).attr('checked', false);
    return;
    
 }



function filteredResults(selectedSubCat){
    
        
        $.post("/searchFilter", 
                { 'subcat' : selectedSubCat}, 
                        function(courses){
        $("#filtered_courses").empty();
        $("#filtered_courses").append('<div id="course_list">Courses :<br></div>');
        $.each(courses, function(key, value) { 
           $("#course_list").append('<ul><h4><a href="'+value.url+'">'+value.name+'</a></h4><li><b>CatName</b> : '+value.catname+'</li><li><b>SubCatName</b> : '+value.subcatname+'</li><li><b>Description</b> : '+value.description+'</li></ul><br>'); 
        });
            
                         }, 'json');
    
}




function search(){
   
   
   $.post("/search", 
          { string : $("#search_string").val() }, 
          function(searchResult){
       
            if(searchResult[0] === "fail")
                {
                    alert("Incorrect");
                }
            else
                {
                    $("#filtered_courses").html('');
                    $("#filtered_courses").append('<div id="course_list">Courses :<br></div>');
                    $.each(searchResult, function(key, value){
                    $("#course_list").append('<ul><h4><a href="'+value.url+'">'+value.name+'</a></h4><li><b>CatName</b> : '+value.catname+'</li><li><b>SubCatName</b> : '+value.subcatname+'</li><li><b>Description</b> : '+value.description+'</li></ul><br>');                   
                        });        
           
                }}
        , 'json');
}