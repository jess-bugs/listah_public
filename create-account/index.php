<?php
session_start();

if(isset($_SESSION['user_logged_in'])) {

    header('location: /listah/notes/');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta name="author" content="Jess Baggs">
    <meta name="keywords" content="listah, listahan, notes, notes list, note taking">
    <meta name="description" content="Create notes seamlessly anytime, anywhere with Listah!">
    
    
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listah!</title>
    <link rel="icon" type="image/png" href="/listah/images/listah-logo.png">
    
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    
    <!-- angular -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script> 
    
    <!-- Bootstrap -->
    <link href="../notes/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    
    <!-- google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    
    
    
    <!-- lottie js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    
    
    
    
    <!-- swal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    
    
    <!-- custom css -->
    <link href="../notes/css/styles.css" rel="stylesheet">
    
    
    <style>
        
        
        body {
            background-color: #191b1d;
        }
        
        
        [ng-cloak] {
            display: none !important;
        }
        
    </style>
    
    
    
</head>


<body ng-app="angularApp" ng-controller="angular_controller">
    
    
    <div class="container">
        <div class="d-flex flex-column justify-content-center min-vh-100 p-2">
            
            
            
            <div style="background-color: #141414; height: 90vh; overflow-y: scroll;" class="p-2 rounded col-12 col-lg-6 col-xl-5 mx-auto text-center shadow d-flex flex-column">
                
                <h3 class="text-white display-4">Create Account</h3>
                
                
                
                
                
                <!-- ******** BLOCK - choose unique name -->
                <div ng-cloak ng-show="unique_name_block" class="col-md-8 mx-auto mt-3 ">
                    
                    <p class="text-secondary">Choose a unique name to get started.</p>
                    
                    <!-- unique name input -->
                    <p class="mt-5 text-secondary text-start">Username</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        
                        <!-- username -->
                        <input ng-model="unique_username" style="border: 1px solid #36bcba; color: #36bcba;" type="text" class="form-control bg-dark" aria-label="Username" aria-describedby="basic-addon1" required>
                    </div>
                    
                    <p class="text-start">
                        <small ng-class="{'text-danger' : !login_success, 'text-success' : login_success}" class="text-start">{{error_message}}</small>
                    </p>
                    
                    <!-- submit button -->
                    <div class="mb-3 mt-5 text-center d-flex">
                        <button ng-click="submit_unique_name()" class="ms-auto btn btn-link link-light text-decoration-none">Next <i class="bi bi-arrow-right"></i></button>                    
                    </div>


                    <div class="mt-5 text-center text-white">

                        <p class="text-secondary" style="font-size: 14px;">Already have an account?</p>
                        <p style="font-size: 14px;">Login <a href="/listah/notes/" class="link link-info">here.</a></p>
                    </div>
                    
                </div>
                
                
                
                
                
                
                
                <!-- ******** BLOCK - password, firstname, lastname -->
                <div ng-cloak ng-show="user_details_block" class="col-md-8 mx-auto mt-3 ">
                    
                    <p class="text-secondary">Complete important details.</p>
                    
                    
                    <!-- username - view only -->
                    <p class="m-0 text-secondary text-start">Username</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        
                        
                        <input ng-model="unique_username" style="border: 1px solid #36bcba;" type="text" class="form-control text-secondary bg-dark" aria-label="Username" aria-describedby="basic-addon1" disabled>
                    </div>
                    
                    
                    <!-- password -->
                    <p class="m-0 text-secondary text-start">Password</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        
                        <input ng-style="{'border' : password_valid ? '1px solid #36bcba' : '1px solid red'}" ng-change="validate_password()" ng-model="reg_password" style="border: 1px solid #36bcba; color: #36bcba;" type="{{password_field_type}}" class="form-control bg-dark" aria-label="Username" aria-describedby="basic-addon1" required>
                        
                        <span ng-style="{'border' : password_valid ? '1px solid #36bcba' : '1px solid red'}" class="input-group-text bg-dark text-white" id="basic-addon1">
                            <a ng-click="show_password()" href="javascript:;" class="link link-light">
                                <i ng-class="{'bi-eye-slash-fill': !password_show, 'bi-eye-fill': password_show}" class="bi"></i>
                            </a>
                        </span>
                        
                    </div>
                    
                    
                    <!-- first name -->
                    <p class="m-0 text-secondary text-start">First Name</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        
                        
                        <!-- style="border: 1px solid #36bcba; color: #36bcba;" -->
                        <input ng-model="reg_fname" style="color: #36bcba;" ng-style="{'border' : reg_fname.length <= 1 ? '1px solid red' : '1 px solid #36bcba'}" type="text" class="form-control bg-dark" aria-label="Username" aria-describedby="basic-addon1" required>
                    </div>
                    
                    
                    <!-- last name -->
                    <p class="m-0 text-secondary text-start">Last Name</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        
                        
                        <input ng-model="reg_lname" ng-style="{'border' : reg_lname.length <= 1 ? '1px solid red' : '1 px solid #36bcba'}" style="color: #36bcba;" type="text" class="form-control bg-dark" aria-label="Username" aria-describedby="basic-addon1" required>
                    </div>
                    
                    
                    
                    
                    <!-- gender -->
                    <p class="m-0 text-secondary text-start">Gender</p>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="bi bi-person-circle"></i>
                        </span>
                        
                        
                        <!-- <input  style="border: 1px solid #36bcba; color: #36bcba;" type="text" class="form-control bg-dark" aria-label="Username" aria-describedby="basic-addon1" required> -->
                        
                        <select ng-model="reg_gender" style="border: 1px solid #36bcba;" class="form-control bg-dark text-white" aria-label="Default select example">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    
                    
                    
                    <p class="text-start">
                        <small ng-class="{'text-danger' : !login_success, 'text-success' : login_success}" class="text-start text-danger">{{error_message}}</small>
                    </p>
                    
                    
                    <!-- submit button -->
                    <div class="mb-3 mt-5 text-center d-flex">
                        <button ng-click="unique_name_block = true; user_details_block = false; profile_pic_block = false; error_message = '';" class="btn btn-link link-light text-decoration-none"><i class="bi bi-arrow-left"></i> Go back</button>                    
                        <button ng-click="submit_personal_info()" type="button" class="ms-auto btn btn-link link-light text-decoration-none">Next <i class="bi bi-arrow-right"></i></button>                    
                    </div>
                    
                </div>
                
                
                
                
                
                
                
                <!-- ******** BLOCK - profile picture uploaded -->
                <div ng-cloak ng-show="profile_pic_block" class="col-md-8 mx-auto mt-3 ">
                    
                    
                    <p class="text-secondary"><span class="text-white">You're almost done!</span> <br>Upload your picture to make your profile look better</p>
                    
                    <img ng-show="!show_lottie_gender" class="border border-info reg_profile_image" style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover;" alt="grunkle-stan">
                    
                    
                    <!-- boy icon for male -->
                    <div ng-show="reg_gender == 'Male' && show_lottie_gender" id="boy-icon" style="height: 200px;"></div>
                    
                    <!-- girl icon for female -->
                    <div ng-show="reg_gender == 'Female' && show_lottie_gender" id="girl-icon" style="height: 200px;"></div>
                    
                    <p class="text-center mt-3">
                        <button ng-disabled="form_submitted" ng-click="upload_profile_pic()" class="btn btn-link link-info">Upload Profile</button>
                    </p>
                    
                    
                    <!-- do it later - under debugging -->
                    <p style="font-size: 13px;" class="text-white d-none">
                        <input ng-model="upload_later" class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        Do it later
                    </p>
                    
                    <input class="d-none" id="user-img-fileinput" type="file" accept=".png, .jpg, .jpeg, .gif">
                    
                    <p class="text-start">
                        <small ng-class="{'text-danger' : !login_success, 'text-success' : login_success}" class="text-start text-danger">{{error_message}}</small>
                    </p>
                    
                    
                    <!-- submit button -->
                    <div class="mb-3 mt-5 text-center d-flex">
                        <button ng-disabled="form_submitted" ng-click="unique_name_block = false; user_details_block = true; profile_pic_block = false; error_message = '';" class="btn btn-link link-light text-decoration-none"><i class="bi bi-arrow-left"></i> Go back</button>                    
                        <button ng-show="!form_submitted" ng-click="submit_registration()" type="button" class="ms-auto btn btn-sm btn-info">Finish <i class="bi bi-arrow-right"></i></button>                    
                        
                        
                        <!-- loading button -->
                        <button ng-show="form_submitted" class="ms-auto btn btn-sm btn-info" type="button" disabled>
                            <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
                            <span style="font-size: 13px;" role="status">Processing...</span>
                        </button>
                    </div>
                    
                    <div class="mt-5">
                        
                    </div>
                    
                    
                </div>
                
                
                
                
                
                
                
                
                <!-- ******** BLOCK - preview details -->
                <div ng-cloak ng-show="preview_details_block" class="col-md-8 mx-auto mt-3 ">
                    
                    <h3 class="text-white display-4 text-start">Preview Details</h3>
                    <img class="border border-info reg_profile_image" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;" alt="grunkle-stan">
                    
                    <!-- submit button -->
                    <div class="mb-3 mt-5 text-center d-flex">
                        <button ng-click="profile_pic_block = true; preview_details_block = false;" class="btn btn-link link-light text-decoration-none"><i class="bi bi-arrow-left"></i> Go back</button>                    
                        <button type="button" class="ms-auto btn btn-link link-light text-decoration-none">Next <i class="bi bi-arrow-right"></i></button>                    
                    </div>
                    
                    
                    
                </div>
                
                
                
                
                
                <hr class="text-secondary mt-auto">
                
                <div class="mt-1 text-start text-secondary">
                    <p class="m-0 lobster-regular">Listah! &trade;</p>
                    A Jess Baggs Production <span style="font-size: 12px;"><i class="bi bi-circle-fill"></i> 2025</span>
                </div>
                
            </div>        
            
        </div>
    </div>
    
    
    
    
    
    <script src="js/angular.js"></script> 
    <script src="js/lotties.js"></script> 
</body>



</html>