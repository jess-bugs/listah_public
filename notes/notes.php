
<?php

include 'php_config/functions.php';

if(!isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] != true) {

    header('location: /listah/');
}




if(isset($_GET['logout'])) {
        
    $logout = $_GET['logout'];
    
    if($logout == 'y') {
        
        session_unset();
        session_destroy();
        
        header('location: /listah/');
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listah!</title>
    
    <meta name="author" content="Jess Baggs">
    <meta name="keywords" content="listah, listahan, notes, notes list, note taking">
    <meta name="description" content="Create notes seamlessly anytime, anywhere with Listah!">
    
    
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    
    <!-- angular -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script> 
    
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- custom css -->
    <link href="css/styles.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

    <!-- lottie js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    
    <style>
        @media screen and (max-width: 991px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
            }
            .content {
                margin-left: 0;
            }
        }
        
        
    </style>
    
</head>
<body ng-app="angularApp" ng-controller="angular_controller">
    
    
    <div class="d-lg-none">

        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <p class="fw-bold lobster-regular fs-1">Listah!</p>
                </a>
                
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                

                <!-- logout -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <button ng-click="logout()" class="btn btn-link link-danger text-decoration-none"><i class="bi bi-power text-danger" ></i> Logout</button>
                        </li>                
                        
                        
                    </ul>                    
                </div>
                
                
                <div class="d-none">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <button ng-class="{'active_btn': active_btn, 'link-secondary': !active_btn}" ng-click="active_btn = true; archive_btn = false; starred_btn = false; note_fetcher('active')" class="btn btn-link text-decoration-none"><i class="bi bi-circle text-success" ></i> Active</button>
                            </li>
                            
                            <li class="nav-item">
                                <button ng-class="{'active_btn': archive_btn, 'link-secondary': !archive_btn}" ng-click="active_btn = false; archive_btn = true; starred_btn = false; note_fetcher('archived')" class="btn btn-link text-decoration-none link-secondary"><i class="bi bi-archive-fill"></i> Archived</button>
                            </li>
                            
                            <li class="nav-item">
                                <button ng-class="{'active_btn': starred_btn, 'link-secondary': !starred_btn}" ng-click="active_btn = false; archive_btn = false; starred_btn = true; fetch_all_starred()" class="btn btn-link link-secondary text-decoration-none"><i class="bi bi-star-fill text-warning"></i> Starred</button>
                            </li>                        
                        </ul>                    
                    </div>
                </div>
                
            </div>
        </nav>
    </div>
    
    
    <!-- the sidebar -->
    
    <div class="sidebar p-3 d-none d-lg-flex flex-column">
        <h1 class="fw-bold lobster-regular display-4">Listah!</h1>
        
        
        <!-- normal / priority -->
        <div class="mt-4 d-flex flex-column  justify-content-between rounded p-2" style="background-color: #111315;">
            <div class="">                
                <button ng-class="{'active_btn': active_btn, 'link-secondary': !active_btn}" ng-click="active_btn = true; archive_btn = false; starred_btn = false; note_fetcher('active')" class="btn btn-link text-decoration-none"><i class="bi bi-circle text-success" ></i> Active</button>
                <!-- ng-class="{'active_btn': default_note_stat === 'active', 'link-secondary': default_note_stat !== 'active'}" -->
            </div>
            
            <div class="">                
                <button ng-class="{'active_btn': archive_btn, 'link-secondary': !archive_btn}" ng-click="active_btn = false; archive_btn = true; starred_btn = false; note_fetcher('archived')" class="btn btn-link text-decoration-none link-secondary"><i class="bi bi-archive-fill"></i> Archived</button>
                <!-- ng-class="{'active_btn': default_note_stat === 'archived', 'link-secondary': default_note_stat !== 'archived'}" -->
            </div>
            
            <div class="">
                <button ng-class="{'active_btn': starred_btn, 'link-secondary': !starred_btn}" ng-click="active_btn = false; archive_btn = false; starred_btn = true; fetch_all_starred()" class="btn btn-link link-secondary text-decoration-none"><i class="bi bi-star-fill text-warning"></i> Starred</button>
                
            </div>
        </div>
        
        
        
    </div>    
    
    
    
    <!-- the content -->
    <div class="content">
        
        
        
        <div class="row g-0 justify-content-between">
            
            <div class="col-lg-5 col-xl-4 col-xxl-3">
                <div class="h-100">
                    
                    
                    <!--  -->
                    
                    <!-- style="max-height: 100vh; overflow-y: scroll;" -->
                    <div class="p-2" >
                        





                        <!-- note heads for desktop -->
                        <div class="d-none d-lg-block" >

                        
                            <!-- search for a note -->
                            <div class="my-3">
                                <p class="fw-bold"><i class="bi bi-search"></i> Search for a note</p>
                                <input ng-model="search_note" style="border: 1px solid #1282A2; font-size: 16px;" class="ms-auto form-control form-control-lg form-control-sm text-dark" type="text" placeholder="keyword...">                            
                            </div>
                            
                            
                            <!-- create a note -->
                            <div class="mb-4 d-grid">
                                <!-- for lg -->
                                <button ng-click="show_view_note_block = false; show_create_note_block = true;" class="btn text-white d-none d-lg-block" style="background-color: #36bcba;"><i class="bi bi-plus-circle-dotted"></i> Create New</button>
                                
                                <!-- for mobile -->
                                <!-- data-bs-toggle="modal" data-bs-target="#create-note-modal" -->
                                <button ng-click="show_create_note_mobile()" class="btn text-white d-lg-none" style="background-color: #36bcba;"><i class="bi bi-plus-circle-dotted"></i> Create New</button>
                            </div>
                            
                        

                        

                        
                            <h3 class="fw-bold mb-2 mt-5">Notes</h3>


                            <!-- active, archived, starred buttons for mobile view -->
                            <div class="my-3 d-lg-none">
                                <div class="btn-group" role="group">
                                    <button ng-class="{'active_btn': active_btn, 'link-secondary': !active_btn}" ng-click="active_btn = true; archive_btn = false; starred_btn = false; note_fetcher('active')" class="btn btn-link text-decoration-none"><i class="bi bi-circle text-success" ></i> Active</button>
                                    <button ng-class="{'active_btn': archive_btn, 'link-secondary': !archive_btn}" ng-click="active_btn = false; archive_btn = true; starred_btn = false; note_fetcher('archived')" class="btn btn-link text-decoration-none link-secondary"><i class="bi bi-archive-fill"></i> Archived</button>
                                    <button ng-class="{'active_btn': starred_btn, 'link-secondary': !starred_btn}" ng-click="active_btn = false; archive_btn = false; starred_btn = true; fetch_all_starred()" class="btn btn-link link-secondary text-decoration-none"><i class="bi bi-star-fill text-warning"></i> Starred</button>
                                </div>
                            </div>
                        </div>


                    

                        <!-- note heads for mobile -->
                        <div class="d-lg-none" ng-show="note_headers">
                        
                            <!-- search for a note -->
                            <div class="my-3">
                                <p class="fw-bold"><i class="bi bi-search"></i> Search for a note</p>
                                <input ng-model="search_note" style="border: 1px solid #1282A2; font-size: 16px;" class="ms-auto form-control form-control-lg form-control-sm text-dark" type="text" placeholder="keyword...">                            
                            </div>
                            
                            
                            <!-- create a note -->
                            <div class="mb-4 d-grid">
                                <!-- for lg -->
                                <button ng-click="show_view_note_block = false; show_create_note_block = true;" class="btn text-white d-none d-lg-block" style="background-color: #36bcba;"><i class="bi bi-plus-circle-dotted"></i> Create New</button>
                                
                                <!-- for mobile -->
                                <!-- data-bs-toggle="modal" data-bs-target="#create-note-modal" -->
                                <button ng-click="show_create_note_mobile()" class="btn text-white d-lg-none" style="background-color: #36bcba;"><i class="bi bi-plus-circle-dotted"></i> Create New</button>
                            </div>
                            
                        

                        

                        
                            <h3 class="fw-bold mb-2 mt-5">Notes</h3>


                            <!-- active, archived, starred buttons for mobile view -->
                            <div class="my-3 d-lg-none">
                                <div class="btn-group" role="group">
                                    <button ng-class="{'active_btn': active_btn, 'link-secondary': !active_btn}" ng-click="active_btn = true; archive_btn = false; starred_btn = false; note_fetcher('active')" class="btn btn-link text-decoration-none"><i class="bi bi-circle text-success" ></i> Active</button>
                                    <button ng-class="{'active_btn': archive_btn, 'link-secondary': !archive_btn}" ng-click="active_btn = false; archive_btn = true; starred_btn = false; note_fetcher('archived')" class="btn btn-link text-decoration-none link-secondary"><i class="bi bi-archive-fill"></i> Archived</button>
                                    <button ng-class="{'active_btn': starred_btn, 'link-secondary': !starred_btn}" ng-click="active_btn = false; archive_btn = false; starred_btn = true; fetch_all_starred()" class="btn btn-link link-secondary text-decoration-none"><i class="bi bi-star-fill text-warning"></i> Starred</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-2" style="max-height: 100vh; overflow-y: scroll;">
                            
                            
                            
                            
                            <!-- DESKTOP - note card -->
                            <a href="javascript:void(0)"  ng-repeat="note in notes_list | filter:search_note" class="link link-light text-decoration-none d-none d-lg-block">
                                <div class="rounded-2 p-3 mb-3 " 
                                ng-click="selectNote(note.ID); view_note(note.ID)" 
                                ng-style="{
                                    'border': default_note_stat === 'active' ? '1px solid #36bcba' : '1px solid grey',
                                    'background-color': selectedNoteId === note.ID ? 'rgba(54, 188, 186, 0.1)' : 'transparent'
                                 }">
                                    
                                    <!-- header -->
                                    <div class="d-flex">
                                        <!-- note title -->
                                        <div class="me-auto">
                                            <h2 class="fw-bold text-white">
                                                {{note.title}}
                                            </h2>
                                        </div>
                                        
                                        <!-- priority / normal indicator -->
                                        <div class="ms-auto">
                                            <!-- <a href="javascript:void(0)" class="link link-warning fs-3 d-none">
                                                <i class="bi bi-star"></i>
                                            </a> -->
                                            <p ng-class="{'text-warning': note.starred === 'true', 'text-secondary': note.starred !== 'true'}" class="fs-3 m-0">
                                                <!-- <i class="bi bi-star"></i> -->
                                                <!-- <i ng-class="{'bi-star-fill': note.starred == 'true', 'bi-star': note.starred == 'false'}" class="bi "></i> -->
                                                <i class="bi" ng-class="{'bi-star-fill': note.starred === 'true', 'bi-star': note.starred !== 'true'}"></i>
                                            </p>
                                        </div>                            
                                    </div>
                                    <small class="mb-3">{{note.date_created | date:'MMM dd, yyyy' }}</small>
                                    
                                    
                                    <p class="mt-5">
                                        <small class="text-secondary" >                                        
                                            {{ note.fetch_note_subj }}
                                        </small>                            
                                    </p>
                                </div>
                            </a>
                            
                            
                            
                            <div class="mt-2" ng-show="loading_anim">
                                <div id="loading-note" style="height: 200px;"></div>
                                <p style="font-size: 14px;" class="m-0 text-center text-secondary">fetching your note...</p>
                            </div>

                            
                            <!-- MOBILE - notecard -->
                            <!-- data-bs-toggle="modal" data-bs-target="#update-note-modal" -->
                            <a ng-show="note_headers" href="javascript:void(0)"  ng-repeat="note in notes_list | filter:search_note" class="link link-light text-decoration-none d-lg-none">
                                <div class="rounded-2 p-3 mb-3 " 
                                ng-click="selectNote(note.ID); view_note(note.ID)" 
                                ng-style="{
                                    'border': default_note_stat === 'active' ? '1px solid #36bcba' : '1px solid grey',
                                    'background-color': selectedNoteId === note.ID ? 'rgba(54, 188, 186, 0.1)' : 'transparent'
                                 }">
                                    
                                    <!-- header -->
                                    <div class="d-flex">
                                        <!-- note title -->
                                        <div class="me-auto">
                                            <h2 class="fw-bold text-white">
                                                {{note.title}}
                                            </h2>
                                        </div>
                                        
                                        <!-- priority / normal indicator -->
                                        <div class="ms-auto">
                                            <!-- <a href="javascript:void(0)" class="link link-warning fs-3 d-none">
                                                <i class="bi bi-star"></i>
                                            </a> -->
                                            <p ng-class="{'text-warning': note.starred === 'true', 'text-secondary': note.starred !== 'true'}" class="fs-3 m-0">
                                                <!-- <i class="bi bi-star"></i> -->
                                                <!-- <i ng-class="{'bi-star-fill': note.starred == 'true', 'bi-star': note.starred == 'false'}" class="bi "></i> -->
                                                <i class="bi" ng-class="{'bi-star-fill': note.starred === 'true', 'bi-star': note.starred !== 'true'}"></i>
                                            </p>
                                        </div>                            
                                    </div>
                                    <small class="mb-3">{{note.date_created | date:'MMM dd, yyyy' }}</small>
                                    
                                    
                                    
                                    <p class="mt-5">
                                        <small class="text-secondary" >                                        
                                            {{ note.fetch_note_subj }}
                                        </small>                            
                                    </p>
                                </div>
                            </a>




                            



                            <!-- view and edit for mobile -->
                            <div ng-show="edit_note_mobile" class="mt-1 d-lg-none">
                                

                                
                                <!-- note contents -->
                                <div class="mt-2">

                                    <div class="d-flex">
                                        <!-- note title -->
                                        <h1 class="fw-bold" ng-hide="show_title_edit">
                                            {{update_title}}
                                            <span style="font-size: 16px;"><button ng-click="show_title_edit = true;" class="btn btn-link"><i class="bi bi-pencil-square"></i></button></span>
                                        </h1>         


                                        <!-- note title hidden input -->
                                        <div class="text-end" ng-show="show_title_edit">
                                            <input style="border-bottom: 1px solid #36bcba;" ng-model="update_title" type="text" class="form-control-plaintext fs-2 fw-bold text-white" placeholder="Note title...">
                                            <a style="font-size: 13px;" ng-click="show_title_edit = false;" href="javascript:;" class="link link-danger text-decoration-none">Cancel</a>
                                        </div>
                                    


                                        <!-- close button -->
                                        <div class="ms-auto">
                                            <button ng-click="edit_note_mobile = false; note_headers = true;" class="btn btn-link link-danger text-decoration-none fs-5"><i class="bi bi-x-lg"></i></button>
                                        </div>                                    
                                        
                                    </div>
                                    
                                    
                                    
                                    <!-- subject -->
                                    <div class="mt-3 mb-3">
                                        <input maxlength="100" ng-model="update_subject" style="width: auto; display: inline-block;" id="createnote_subj" type="text" class="form-control form-control-sm bg-dark border border-warning text-white" placeholder="subject">
                                    </div>
                    

                                    <p class="text-secondary m-0">Created on <span class="text-info">{{update_date_created | date:'MMM dd, yyyy'}}</span></p>
                                    <p class="text-secondary m-0">Modified on <span class="text-info">{{update_last_mod | date:'MMM dd, yyyy HH:mm a'}}</span></p>
                    
                                    <!-- tools -->
                                    <div class="d-flex align-items-center mt-4">
                                        
                                        <button ng-click="update_note_mobile()" class="btn btn-link link-success fs-4"><i class="bi bi-check2"></i></i></button>
                                        
                                        <div class="ms-auto">
                                            <button ng-click="delete_note()" class="btn btn-link link-danger"><i class="bi bi-x-circle-fill"></i></button>
                                            <button data-bs-toggle="tooltip" title="Star this note" data-bs-placement="top" ng-click="updatenote_starred()" class="btn  btn-link link-warning"><i ng-class="{'bi-star-fill': update_starred === 'true', 'bi-star': update_starred !== 'true'}" class="bi "></i></button>
                                            <button ng-hide="update_archived == 'archived'" ng-click="archive_note()" data-bs-toggle="tooltip" title="Archive this note" data-bs-placement="top" class="btn  btn-link link-info"><i class="bi bi-archive-fill"></i></button>
                                            <button ng-click="unarchive_note()" ng-show="update_archived == 'archived'" data-bs-toggle="tooltip" title="Unarchive" data-bs-placement="top" class="btn  btn-link link-success text-decoration-none"><i class="bi bi-archive"></i></button>
                                        </div>
                                        
                                    </div>
                    

                                    <!-- quill text editor -->
                                    <div class=" rounded-2" >                        
                                        <div id="editor-view-note-mobile"  style="max-height: 400px; width: 100%; overflow-y: scroll;"></div>                        
                                    </div>                    
                    
                    
                                </div>                                

                            </div>




                            <!-- create note for mobile -->
                            <div ng-show="create_note_mobile" class="mt-1 d-lg-none">
                                

                                <!-- headers -->
                                <div class="d-flex align-items-center">
                                    <h2 class="fw-bold">Create a Note</h2>

                                    <div class="ms-auto">
                                        <!-- close button -->
                                        <div class="ms-auto">
                                            <button ng-click="create_note_mobile = false; note_headers = true;" class="btn btn-link link-danger text-decoration-none fs-5"><i class="bi bi-x-lg"></i></button>
                                        </div>                                    
                                    </div>
                                </div>


                                <!-- main content -->
                                <div class="mt-3">

                                    <!-- note title -->
                                    <input ng-model="createnote_title" type="text" class="form-control-plaintext display-4 fw-bold text-white" placeholder="Note title...">

                                    <!-- subject -->
                                    <div class="mt-2 mb-3">
                                        <input maxlength="100" ng-model="createnote_subject" style="width: auto; display: inline-block;" id="createnote_subj" type="text" class="form-control form-control-sm text-white bg-dark border border-warning" placeholder="subject">
                                    </div>
                
                                    <p class="text-info m-0">
                                        Today <i class="bi bi-dash-lg"></i> <span class="fw-bold text-white">{{ currentDate | date:'MMM dd, yyyy' }}</span>
                                    </p>
                

                                    <!-- error message -->
                                    <p class="text-danger mt-2">{{createnote_err_message}}</p>
                                    <div class="my-3" ng-repeat="error in error_messages">
                                        <p class="text-danger">Error: {{error}}</p>
                                    </div>
                

                                    <!-- note star -->
                                    <div class="d-flex align-items-center mt-3">
                                        
                                        
                                        <button data-bs-toggle="tooltip" title="Star this note" data-bs-placement="top"  ng-click="star_note()" class="btn  btn-link link-warning fs-3"><i ng-class="{'bi-star-fill' : createnote_starred, 'bi-star' : !createnote_starred}" class="bi"></i></button>                            
                                        <div class="ms-auto">
                                            <button ng-click="create_new_note_mobile()" class="btn btn-link link-info text-decoration-none">Create <i class="bi bi-arrow-right"></i></button>
                                        </div>
                                        
                                    </div>
                
                                    <!-- quill text editor -->
                                    <div class=" rounded-2" >                        
                                        <div id="editor-create-note-mobile" style="height: 300px; overflow-y: scroll;"></div>                        
                                    </div>                    
                

                                </div>


                

                            </div>
                            
                            
                            
                        </div>
                        
                    </div>
                    
                </div>
            </div>
            
            
            
            <div class="col-lg-7 col-xl-8 col-xxl-9 d-none d-lg-block p-1" >
                <div class="rounded-2 p-2" style="min-height: 100vh; background-color: #26282a;">
                    
                    <!-- BLOCK - creating note -->
                    <div id="create-note" ng-show="show_create_note_block">
                        <!-- header for content -->
                        <div class="mb-3 d-flex p-1 rounded-2" >                
                            <div class="me-auto">
                                <p class="text-white d-none">Last logged: Feb 31, 2025</p>
                            </div>
                            
                            <div class="ms-auto me-2 d-inline">                                                                
                                <button ng-click="logout()" class="btn btn-link link-danger border border-danger"><i class="bi bi-power text-danger"></i></button>
                            </div>                                
                        </div>
                        
                        
                        <h3 class="fw-bold" style="color: #36bcba;">Create a Note</h3>
                        
                        <!-- note title -->                        
                        <div class="mt-2">
                            <input ng-model="createnote_title" type="text" class="form-control-plaintext text-white display-4 fw-bold" placeholder="Note title...">
                        </div>
                        
                        
                        <!-- subject -->
                        <div class="mt-2 mb-3">
                            <input maxlength="100" ng-model="createnote_subject" style="width: auto; display: inline-block;" id="createnote_subj" type="text" class="form-control form-control-sm text-white bg-dark border border-warning" placeholder="subject">
                        </div>
                        
                        <p class="text-warning m-0">
                            Today <i class="bi bi-dash-lg"></i> <span class="text-white fw-bold">{{ currentDate | date:'MMM dd, yyyy' }}</span>
                        </p>
                        
                        
                        <!-- error message -->
                        <p class="text-danger mt-5">{{createnote_err_message}}</p>
                        <div class="my-3" ng-repeat="error in error_messages">
                            <p class="text-danger">Error: {{error}}</p>
                        </div>
                        
                        
                        <!-- note star -->
                        <div class="d-flex align-items-center justify-content-end">
                            <button data-bs-toggle="tooltip" title="Star this note" data-bs-placement="top" ng-click="star_note()" class="btn  btn-link link-warning fs-3"><i ng-class="{'bi-star-fill' : createnote_starred, 'bi-star' : !createnote_starred}" class="bi"></i></button>                            
                            <button ng-click="create_new_note()"  class="btn btn-sm btn-info">Create <i class="bi bi-arrow-right"></i></button>                            
                        </div>
                        
                        
                        
                        <!-- quill text editor -->
                        <div class="rounded-2 mt-2" >                        
                            <div class="text-white bg-dark" id="editor-create-note" style="height: 50%;"></div>                        
                        </div>
                                                
                    </div>
                    
                    
                    
                    
                    
                    
                    <!-- BLOCK - viewng and updating note -->
                    <div id="view-note" ng-show="show_view_note_block">
                        
                        <!-- header for content -->
                        <div class="mb-3 d-flex p-1 rounded-2" >                
                            <div class="me-auto">
                                <p class="text-white d-none">Last logged: Feb 31, 2025</p>
                            </div>
                            
                            <div class="ms-auto me-2 d-inline">                                                                
                                <button ng-click="logout()" class="btn btn-link link-danger border border-danger"><i class="bi bi-power text-danger"></i></button>
                            </div>                                
                        </div>
                        
                        
                        <div class="my-3" ng-repeat="error in error_messages">
                            <p class="text-danger">Error: {{error}}</p>
                        </div>
                        
                        <!-- note title -->
                        <h1 class="fw-bold text-white" ng-hide="show_title_edit">
                            {{update_title}}
                            <span style="font-size: 16px;"><button ng-click="show_title_edit = true;" class="btn btn-link link-light"><i class="bi bi-pencil-square"></i></button></span>
                        </h1>
                        
                        
                        <!-- note title hidden input -->
                        <div class="mt-2 text-end col-xl-8 col-xxl-5" ng-show="show_title_edit">
                            <input style="border-bottom: 1px solid #36bcba;" ng-model="update_title" type="text" class="form-control-plaintext text-white display-4 fw-bold" placeholder="Note title...">
                            <a style="font-size: 13px;" ng-click="show_title_edit = false;" href="javascript:;" class="link link-danger text-decoration-none">Cancel</a>
                        </div>
                        
                        
                        <!-- subject -->
                        <div class="mt-2 mb-3">
                            <input maxlength="100" ng-model="update_subject" style="width: auto; display: inline-block;" id="createnote_subj" type="text" class="form-control form-control-sm text-white bg-dark border border-warning" placeholder="subject">
                        </div>
                        
                        
                        
                        <p class="text-secondary m-0">Created on <span class="text-white">{{update_date_created | date:'MMM dd, yyyy'}}</span></p>
                        <p class="text-secondary m-0">Last modified on <span class="text-white">{{update_last_mod | date:'MMM dd, yyyy HH:mm a'}}</span></p>
                        
                        
                        
                        
                        <!-- tools -->
                        <div class="mt-4 d-flex align-items-center justify-content-end">
                            <button ng-click="update_note()" data-bs-toggle="tooltip" title="Update this note" data-bs-placement="top" class="btn btn-link link-success fs-4"><i class="bi bi-check2"></i></button>
                            <button ng-click="delete_note()" data-bs-toggle="tooltip" title="Permanently delete this note" data-bs-placement="top" class="btn btn-link link-danger"><i class="bi bi-x-circle-fill"></i></button>
                            <button data-bs-toggle="tooltip" title="Star this note" data-bs-placement="top" ng-click="updatenote_starred()" class="btn  btn-link link-warning"><i ng-class="{'bi-star-fill': update_starred === 'true', 'bi-star': update_starred !== 'true'}" class="bi "></i></button>
                            <button ng-hide="update_archived == 'archived'" ng-click="archive_note()" data-bs-toggle="tooltip" title="Archive this note" data-bs-placement="top" class="btn  btn-link link-info"><i class="bi bi-archive-fill"></i></button>
                            <button ng-click="unarchive_note()" ng-show="update_archived == 'archived'" data-bs-toggle="tooltip" title="Unarchive" data-bs-placement="top" class="btn  btn-link link-success text-decoration-none"><i class="bi bi-archive"></i></button>
                        </div>
                        
                        
                        
                        <!-- quill text editor -->
                        <div class=" rounded-2" style="height: inherit; overflow-y: scroll;">                        
                            <div class="text-white" id="editor-view-note" style="max-height: 50%; overflow-y: scroll;"></div>                        
                        </div>
                        
                    </div>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    <!-- footer -->
                    
                    <footer style="background-color: black;" class="p-4 container-fluid mt-auto d-none">
                        
                        
                        <div class="row justify-content-between">
                            
                            <!-- copy rights reserved -->
                            <div class="col-lg-4">                
                                <p><small class="text-secondary d-none d-lg-block">© 2024. All Rights Reserved. </small></p>
                                <p class="d-lg-none text-center"><small class="text-secondary">© 2024. All Rights Reserved. </small></p>
                            </div>
                            
                            
                            <!-- link buttons -->
                            <div class="col-lg-4 text-center">
                                <a data-bs-toggle="tooltip" href="https://www.facebook.com/j3ssbugz" class="link-info text-decoration-none me-2" aria-label="Check me on Facebook!" data-bs-original-title="Check me on Facebook!"><i class="bi bi-facebook"></i></a>
                                <a data-bs-toggle="tooltip" href="/" class="link-info text-decoration-none me-2" aria-label="Checkout my landing page!" data-bs-original-title="Checkout my landing page!"><i class="bi bi-globe"></i></a>
                                <a data-bs-toggle="tooltip" href="mailto:hotel121909@gmail.com" class="link-info text-decoration-none me-2" aria-label="Reach me Out!" data-bs-original-title="Reach me Out!"><i class="bi bi-envelope-at-fill"></i></a>
                            </div>
                            
                            <!-- some text -->
                            <div class="col-lg-4">
                                <p class="text-end d-none d-lg-block"><small class="text-secondary">Uncle Jess ™</small></p>
                                <p class="d-lg-none text-center"><small class="text-secondary">Uncle Jess ™</small></p>
                            </div>
                            
                            
                        </div>
                        
                        
                        
                        
                    </footer>
                    
                    <!--  -->
                    
                    
                    
                    
                    
                </div>
            </div>                        
        </div>                
    </div>
    
    
    
    <script src="js/angular.js"></script> 
    <script src="js/jquery.js"></script> 
    <script src="js/lotties.js"></script> 
    
    
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    
</body>


</html>