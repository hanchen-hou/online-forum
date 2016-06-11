            $('#user_form').submit(function(event) {

                var error_message = checkform();
                if (error_message != "")
                    event.preventDefault();

                $('#error').html(error_message);
            });
            function checkform() {
                var error_M = "";
                var error_color="rgb(217,83,79)";
                var correct_color="rgb(76,175,80)";
                if ($('#user_name').val() == "") {
                    error_M += "User name is empty<br>";
                    $('#user_name').css("background-color",error_color);
                }
                else
                {
                    $('#user_name').css("background-color",correct_color);
                }
                if ($('#email').val() == "") {
                    error_M += "Email is empty<br>";
                    $('#email').css("background-color",error_color);
                }
                else
                {
                    $('#email').css("background-color",correct_color);
                }
                
                if ($('#password').val() == "") {
                    error_M += "Password field is empty<br>";
                     $('#password').css("background-color",error_color);
                }
                 else
                {
                    $('#password').css("background-color",correct_color);
                }              
                if ($('#confirm_password').val() == "") {
                    error_M += "Confirm password is empty<br>";
                     $('#confirm_password').css("background-color",error_color);
                }
                 else
                {
                    $('#confirm_password').css("background-color",correct_color);
                }
                if ($('#password').val() != $('#confirm_password').val()) {
                    error_M += "Confirm password does not match the new password";
                     $('#password').css("background-color",error_color);
                     $('#confirm_password').css("background-color",error_color);
                }                
                return error_M;
            }
