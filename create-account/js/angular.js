var app = angular.module('angularApp', []);

app.controller('angular_controller', function($scope, $http, $timeout) {
    
    $scope.error_message = "";
    
    $scope.unique_name_block = true;
    $scope.user_details_block = false;
    $scope.profile_pic_block  = false;
    $scope.preview_details_block = false;
    
    
    
    $scope.show_lottie_gender = true;
    $scope.upload_later = false;
    $scope.reg_gender = "Male";
    $scope.form_submitted = false;
    $scope.password_valid = false;
    $scope.reg_lname = "";
    $scope.reg_fname = ""; 
    
    
    // submit unique username
    $scope.submit_unique_name = function() {
        
        if(validate_input($scope.unique_username) && $scope.unique_username.length >= 4) {
            
            
            $http({
                method: 'POST',
                url: "api/check_user.php",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                data: $.param({ 
                    username : $scope.unique_username,                    
                })
                
            }).then(function(response) {
                
                
                if(response.data.message > 0) {
                    
                    $scope.error_message = "This username already exists.";
                
                } else {
                    
                    
                    // clear message 
                    $scope.error_message = "";
                    
                    // proceed to next page
                    $scope.unique_name_block = false;
                    $scope.user_details_block = true;
                    $scope.profile_pic_block  = false;
                    

                    // pass uniquename to username to remain unchanged
                    $scope.username = $scope.unique_username;
                }
                
                
            }, function(error) {
                
                // handle error
                
            });
            
            
            
        } else {
            
            $scope.error_message = "Valid username must contain at least 4 characters.";
            
        }
        
    }
    
    



    // validate password input
    $scope.validate_password = function() {

        if($scope.reg_password.length <= 5) {

            $scope.password_valid = false;
        } else {

            $scope.password_valid = true;
        }

    }





    
    // submit password, fname, lname
    $scope.submit_personal_info = function() {
        
        if(!validate_input($scope.unique_username) || !validate_input($scope.reg_password)  || !validate_input($scope.reg_fname) || !validate_input($scope.reg_lname) || !validate_input($scope.reg_gender)) {
            

            $scope.error_message = "Please complete all required fields.";
            
        } else if(!$scope.password_valid) {
            
            $scope.error_message = "Password must be at least 6 characters.";

        
        } else if($scope.reg_fname.length <= 1 || $scope.reg_lname.length <= 1) {
            
            $scope.error_message = "First name and last name must be at least 6 characters.";

            
        }  else {


            $scope.error_message = "";
            
            
            $scope.unique_name_block = false;
            $scope.user_details_block = false;
            $scope.profile_pic_block  = true;
        }
        
    }
    
    
    
    
    $scope.file_uploaded = false;
    // upload profile pic
    $scope.upload_profile_pic = function() {
        
        $("#user-img-fileinput").click();
        
        
    }
    
    
    // file input listener for profile pic
    $("#user-img-fileinput").on('change', function() {
        
        $timeout(function() {
            $scope.show_lottie_gender = false;
        });
        
        
        
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(".reg_profile_image").attr("src", e.target.result).show();
                
                $scope.file_uploaded = true;
                
                
            };
            reader.readAsDataURL(file);
        }
        
        
    })
    
    
    
    
    
    
    
    
    
    
    
    
    
    // function to submit form
    $scope.submit_registration = function() {
        
        $scope.form_submitted = true;
        
        var formData = new FormData();
        
        // Append form fields
        formData.append("username", $scope.username);
        formData.append("password", $scope.reg_password);
        formData.append("firstname", $scope.reg_fname);
        formData.append("lastname", $scope.reg_lname);
        formData.append("gender", $scope.reg_gender);
        
        // Append file input using jQuery
        var fileInput = $("#user-img-fileinput")[0];
        
        if (fileInput.files.length > 0) {
            formData.append("profile_image", fileInput.files[0]);
        }
        
        
        
        $http({
            method: 'POST',
            url: "api/create_account.php",
            headers: { 'Content-Type': undefined },
            data: formData,
            transformRequest: angular.identity
        }).then(function(response) {
            
            
            if(response.data.status > 0) {

                // clear error message
                $scope.error_message = "";

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Account created successfully!',
                    html: "You will be redirected shortly...",
                    showConfirmButton: false,
                    showCancelButton: false,
                    timer: 2000,
                    timerProgressBar: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false 
                });
                

                // redirect to notes
                $timeout(function() {

                    window.location.href = "/listah/notes/";
                }, 2000);
                

            } else {

                $scope.error_message = "Error: " + response.data.message;
            }
            
            
        }, function(error) {
            
            // handle error
            
        });
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // sanitinze user input
    function validate_input(data) {
        
        if(data !== "" && data !== undefined) {
            
            return true;
        }
    }
    
    
    
    
    
    
    // password seek
    $scope.password_field_type = "password";
    $scope.password_show = false;
    
    $scope.show_password = function() {
        
        $scope.password_show = $scope.password_show ? false : true;
        
        $scope.password_field_type = $scope.password_show ? "text" : "password";
    }
    
    
    
});