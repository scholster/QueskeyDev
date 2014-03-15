function subscribe(flag, expired, courseId, coursePid, courseExpirytime){
            if(flag)
                {
                if(expired)
                 {
                     alert('Your subscription has expired. Please Re-subscribe the course to continue reading');
                 }
                }
            $('#subscribe_btn').click(function()
                {
                        $.post('/subscribe', {
                                                'course_id' : courseId,//course_id,//$('#course_id').val(),
                                                'pid' : coursePid,//pid,//$('#pid').val(),
                                                'expiryTime' : courseExpirytime//expiryTime//$('#expiryTime').val()
                                              },
                               function(msg)
                                  {
                                      if(msg.success === 1)
                                                {
                                                    alert('successfully subscribed. Redirecting to dashboard');
                                                    window.location.href = '/';
                                                }
                                            else if(msg.success === 0)
                                                {
                                                    alert('some error occurred, please try again');
                                                }
                                                else
                                                {
                                                    alert('course has been resubscribed');
                                                    window.location.href = '/';
                                                }
                                        },
                                        'json'
                           );
                    });
}