
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
                                                            'course_id' : $('#course_id').val(),
                                                            'pid' : $('#pid').val(),
                                                            'expiryTime' : $('#expiryTime').val()
                                                            },
                                        function(msg)
                                        {
                                            alert(msg.success);
                                        },
                                        'json'
                           );
                    })
                }
            );
        }
        );