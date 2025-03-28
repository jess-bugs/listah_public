var app = angular.module('angularApp', []);

app.controller('angular_controller', function($scope, $http, $timeout) {
    
    $scope.error_message = "";
    
    $scope.unique_name_block = true;
    $scope.user_details_block = false;
    $scope.profile_pic_block  = false;
    $scope.preview_details_block = false;


    $scope.show_lottie_gender = true;
    $scope.upload_later = false;
    
    
    
    // submit unique username
    $scope.submit_unique_name = function() {
        
        if(validate_input($scope.unique_username)) {
            
            
            // clear message 
            $scope.error_message = "";
            
            $scope.unique_name_block = false;
            $scope.user_details_block = true;
            $scope.profile_pic_block  = false;
            
        } else {
            
            $scope.error_message = "Enter a valid username.";
            
        }
        
    }
    
    
    
    // submit password, fname, lname
    $scope.submit_personal_info = function() {
        
        if(validate_input($scope.unique_username) && validate_input($scope.reg_password)  && validate_input($scope.reg_fname) && validate_input($scope.reg_lname) && validate_input($scope.reg_gender)) {
            
            $scope.error_message = "";
            
            
            $scope.unique_name_block = false;
            $scope.user_details_block = false;
            $scope.profile_pic_block  = true;
            
        } else {
            
            $scope.error_message = "Please input all required fields.";
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