$(document).ready(function(){
   
   $("#signup_btn").click(function(){
       register();       
   });
   
   
   
});


function register(){
   
   
   $.post("/register", {
       email: $("#register_email").val(),
       username: $("#register_username").val(),
       pwd:    $("#register_pwd").val()
       
   }, function(data){
       
       if(data.status === "success")
       {
           alert(data.message);
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
