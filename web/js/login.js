$(document).ready(function(){
   
   $("#login_btn").click(function(){
       login();       
   });
   
   
   
});


function login(){
   
   
   $.post("/login", {
       email: $("#login_email").val(),
       pwd:    $("#login_pwd").val()
       
   }, function(data){
       
       if(data.status == "success")
       {
           window.location.href = '/';
           
       }
       else
       {
           alert(data.message);
       }}
   , 'json');
}



function emailValidate(email){
    
}


function lengthValidation(val, len){
    
}
