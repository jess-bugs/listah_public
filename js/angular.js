// Swal.fire({
//     title: 'Server Response',
//     html:"Success: " + response.data.status ,
//     icon: 'info',
//     confirmButtonText: 'OK'
// });


var app = angular.module('angularApp', []);

app.controller('angular_controller', function($scope, $http, $timeout) {
    
    $scope.login_username = "";
    $scope.login_password = "";
    $scope.invalid_log = "";
    $scope.login_success = false;
    
    
    $scope.login = function() {        
        
        if($scope.login_username == '' || $scope.login_username == '') {

            $scope.invalid_log = "Username and password are required.";

        } else {


            

            $http({
                method: 'POST',
                url: "backend/login.php",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    
                data: $.param({ 
                    username : $scope.login_username,
                    password : $scope.login_password,                
                })
                
            }).then(function (response) {
                
                
                $scope.invalid_log = response.data.message;
                $scope.login_success = response.data.status;
                

                if(response.data.status > 0) {                    

                    $timeout(function() {                    
                        $scope.invalid_log = ""; 
                    }, 3000);


                    window.location.href = "/listah/notes/notes.php";                    
                    
                } else {

                    $scope.invalid_log = "Invalid username or password.";
                    $scope.invalid_log = response.data.message;                    

                }

                

                
                
            }, function (error) {
                
                // handle erro
                $scope.invalid_log = "Could not reach backend.";
                
            });
            
            
        }
                
    }



    $scope.password_field_type = "password";
    $scope.password_show = false;
    
    $scope.show_password = function() {

        $scope.password_show = $scope.password_show ? false : true;

        $scope.password_field_type = $scope.password_show ? "text" : "password";
    }
    

});