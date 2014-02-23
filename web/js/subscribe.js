$(document).ready(function()
        {
            $('#confirm').hide();
            $('#subscribe').click(function()
                {
                    $("#course_details").hide();
                    $('#confirm').show();
                    $('#confirm_btn').click(function()
                            {
//                                var course_id = $('#course_id').val();
//                                var pid = $('#pid').val();
//                                var expiryTime = $('#expiryTime').val();
                                $.post('/subscribe', {
                                                            'course_id' : {{ course[0].id}},//course_id,//$('#course_id').val(),
                                                            'pid' : {{ course[0].pid}},//pid,//$('#pid').val(),
                                                            'expiryTime' : {{ course[0].expirytime}}//expiryTime//$('#expiryTime').val()
                                                            },
                                        function()
                                        {
                                
                                        },
                                        'json'
                           );
                    })
                }
            );
        }
        );