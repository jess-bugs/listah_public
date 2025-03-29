<?php
session_start();

if(isset($_SESSION['user_logged_in'])) {

    header('location: notes/');
}

?>


<html lang="en">
<head>

    <meta name="author" content="Jess Baggs">
    <meta name="keywords" content="listah, listahan, notes, notes list, note taking">
    <meta name="description" content="Create notes seamlessly anytime, anywhere with Listah!">
    


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listah!</title>
    <link rel="icon" type="image/png" href="images/listah-logo.png">
    
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    
    <!-- angular -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script> 
    
    <!-- Bootstrap -->
    <link href="notes/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    
    
    
    
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    
    
    <!-- custom css -->
    <link href="notes/css/styles.css" rel="stylesheet">
    
    
    <style>
        
        body {
            background-color: #191b1d;
        }
        
    </style>
    
    
    
</head>


<body ng-app="angularApp" ng-controller="angular_controller">
    
    <div class="container">
        
        <div class="d-flex flex-column justify-content-center h-100">
            
            <form ng-submit="login()" style="background-color: #141414; border: 1px solid #36bcba;" class="p-2 rounded col-lg-6 mx-auto text-center">
                
                <h1 class="text-white lobster-regular display-1">Listah!</h1>
                
                <p class="text-white">Login to your account</p>
                
                
                <div class="col-xl-8 mx-auto">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        <!-- username -->
                        <input ng-model="login_username" style="border: 1px solid #36bcba; color: #36bcba;" type="text" class="form-control bg-dark" aria-label="Username" aria-describedby="basic-addon1" required>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-fingerprint"></i>
                        </span>
                        <input ng-model="login_password" style="border: 1px solid #36bcba; color: #36bcba;" type="{{password_field_type}}" class="form-control bg-dark" aria-label="Username" aria-describedby="basic-addon1" required>
                        <span style="border: 1px solid #36bcba;" class="input-group-text bg-dark text-white" id="basic-addon1">
                            <a ng-click="show_password()" href="javascript:;" class="link link-light">
                                <i ng-class="{'bi-eye-slash-fill': !password_show, 'bi-eye-fill': password_show}" class="bi"></i>
                            </a>
                        </span>
                    </div>

                    <p class="text-start">
                        <small ng-class="{'text-danger' : !login_success, 'text-success' : login_success}" class="text-start">{{invalid_log}}<small>
                    </p>
                    
                </div>


                <div class="col-xl-8 mx-auto mb-3 text-center">
                    <button style="border: 1px solid #36bcba;" type="submit" class="btn btn-outline-dark text-white">Login</button>                    
                    
                    <p class="mt-3">
                        <a href="/listah/create-account/" style="font-size: 14px;" class="link link-secondary">Create Account</a>  
                    </p>
                </div>
            
                <hr class="text-secondary mt-4">
                
                <div class="mt-1 text-start text-secondary">
                    <p class="m-0 lobster-regular">Listah! &trade;</p>
                    A Jess Baggs Production <span style="font-size: 12px;"><i class="bi bi-circle-fill"></i> 2025</span>
                </div>

            </form>
            

            
            
        </div>
        
    </div>
    
    
    
    
    
    
    <script src="js/angular.js"></script> 
    <!-- <script src="js/jQuery.js"></script>  -->
</body>



</html>