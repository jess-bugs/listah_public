var app = angular.module('angularApp', []);

app.controller('angular_controller', function($scope, $http, $timeout, $sce) {
    
    
    
    
    
    /****** 2 ways of setting the content (value) ************/
    // $scope.html_content = "<p class='text-warning'>Hello World!</p>";
    // $scope.text_content = $scope.html_content.replace(/<\/?[^>]+(>|$)/g, "");
    // quill.clipboard.dangerouslyPasteHTML($scope.html_content);
    // quill.root.innerHTML = $scope.html_content;
    
    
    $scope.error_messages = [];
    
    
    $scope.createnote_starred = false;
    $scope.createnote_err_message = "";
    $scope.show_create_note_block = true;
    $scope.show_view_note_block = false;
    $scope.current_note_id;
    
    $scope.show_title_edit = false;
    
    $scope.currentDate = new Date();
    $scope.default_note_stat = 'active';



    $scope.active_btn = true;
    $scope.archive_btn = false;
    $scope.starred_btn = false;

    
    
    // tools for quill JS
    const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block'],
        ['link', 'image', 'video', 'formula'],
        
        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        [{ 'direction': 'rtl' }],                         // text direction
        
        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        
        [{ 'color': ['red'] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],
        
        ['clean']                                         // remove formatting button
    ];
    
    
    // initialize quill JS
    const quill = new Quill('#editor-create-note', {
        
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });
    
    
    
    
    const quill_update = new Quill('#editor-view-note', {
        
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });




    const quill_update_mobile = new Quill('#editor-view-note-mobile', {
        
        modules: {
            
        },
        theme: 'snow'
    });



    
    
    // set to large font
    quill.format('size', 'large');
    quill_update.format('size', 'large');
    
    // function to create the note
    $scope.create_new_note = function() {
        
        
        $scope.createnote_quill_content = quill.root.innerHTML;
        // $scope.createnote_quill_content = quill.getSemanticHTML();;
        
        
        let quill_content =  $scope.createnote_quill_content.replace(/<\/?[^>]+(>|$)/g, "")
        // let quill_content =  $scope.createnote_quill_content;
        let note_title = $scope.createnote_title;
        
        if(quill_content == "" || note_title == "" || note_title == undefined) {
            
            
            $scope.createnote_err_message = "Fields cannot be empty.";
            
        } else {
            
            // alert($scope.createnote_quill_content);
            console.log("Quil CONTENT: " + $scope.createnote_quill_content);
            $http({
                method: 'POST',
                url: "api/create_note.php",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                data: $.param({ 
                    note_title : $scope.createnote_title,
                    note_content : $scope.createnote_quill_content,
                    note_starred : $scope.createnote_starred,
                    note_subject : $scope.createnote_subject
                })
                
            }).then(function(response) {
                
                
                
                if(response.data > 0) {
                    
                    // clear quill JS
                    quill.clipboard.dangerouslyPasteHTML("");
                    
                    // clear the title
                    $scope.createnote_title = "";
                    
                    // remove star
                    $scope.createnote_starred = false;
                    
                    // clear error message
                    $scope.createnote_err_message = "";
                    
                    // clear subject field
                    $scope.createnote_subject = "";
                    
                    
                    // re-fetch notes list
                    $scope.fetch_notes($scope.default_note_stat);
                    
                    Swal.fire({
                        title: 'Success!',
                        html: "Note Created!",
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    
                    
                } else {
                    
                    
                    // $scope.createnote_err_message = "Failed to save note: " + response.data;
                    $scope.error_messages.push('Failed to create this note.');
                }
                
                
            }, function(error) {
                
                // $scope.createnote_err_message = "Backend Error: " + (error.data ? error.data : JSON.stringify(error));
                $scope.error_messages.push('Failed to create this note: ' + (error.data ? error.data : JSON.stringify(error)));
            })
        }
        
    }
    
    
    
    
    
    // function to star a note
    $scope.star_note = function() {
        
        $scope.createnote_starred = $scope.createnote_starred == true ? false : true;
    }
    
    
    
    
    
    
    
    // function to list notes in card
    $scope.fetch_notes = function(status, starred = null) {
        
        $http({
            method: 'POST',
            url: "api/fetch_notes.php",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param({ 
                note_stat : status,
                note_starred : starred
            })
            
        }).then(function (response) {
            
            $scope.notes_list = response.data;    
            
            $scope.notes_list.forEach(note => {
                note.date_created = new Date(note.date_created);
                note.fetch_note_subj = note.subject ? note.subject : "No Subject.";
                
            });
            
        }, function (error) {
            
            
            $scope.error_messages.push('Error fetching notes - route not established.');
            
        });
        
    }
    
    $scope.fetch_notes($scope.default_note_stat);
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // function to open and view note
    $scope.view_note = function(note_id) {
        
        $scope.show_create_note_block = false;
        $scope.show_view_note_block = true;
        $scope.current_note_id = note_id;
        
        
        $http({
            method: 'POST',
            url: "api/view_note.php",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param({ 
                note_id : note_id
            })
            
        }).then(function (response) {
            
            
            $scope.update_title = response.data.title;
            $scope.update_subject = response.data.subject;
            $scope.update_starred = response.data.starred;
            $scope.update_archived = response.data.status;
            $scope.update_date_created = new Date(response.data.date_created);
            $scope.update_last_mod = new Date(response.data.last_modified);
            $scope.update_content = response.data.content;
            
            // $scope.html_content = $scope.update_content;
            $scope.html_content = $scope.update_content;
            $scope.text_content = $scope.html_content.replace(/<\/?[^>]+(>|$)/g, "");
            // quill_update.clipboard.dangerouslyPasteHTML($scope.text_content);
            quill_update.root.innerHTML = $scope.html_content;
            quill_update_mobile.root.innerHTML = $scope.html_content;

            
            
            // quill_update.root.innerHTML = $scope.update_content
            
            
            
            
        }, function (error) {
            
            $scope.error_messages.push('Failed to view this note.');
            
        });
        
    }
    
    
    
    
    
    $scope.update_note = function() {
        
        
        // updated quill content
        $scope.update_quill_content = quill_update.root.innerHTML;
        
        
        
        
        $http({
            method: 'POST',
            url: "api/update_note.php",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param({ 
                note_id : $scope.current_note_id,
                note_title : $scope.update_title,
                note_content : $scope.update_quill_content,
                note_starred : $scope.update_starred,
                note_subject : $scope.update_subject
                
            })
            
        }).then(function (response) {
            
            
            
            
            // re-fetch notes list
            $scope.fetch_notes($scope.default_note_stat);
            
            Swal.fire({
                title: 'Success!',
                html: "Changes saved!",
                icon: 'success',
                confirmButtonText: 'OK'
            });
            
            
            $scope.view_note($scope.current_note_id);
            
            
            
            
        }, function (error) {
            
            $scope.error_messages.push('An error occured while updating this note.');
            
        });
        
        
    }
    
    
    
    $scope.updatenote_starred = function() {
        
        $scope.update_starred =  $scope.update_starred == 'true' ? "false" : "true";
    }
    
    
    
    

    $scope.archive_err_message = "";
    
    $scope.archive_note = function() {
        
        Swal.fire({
            title: 'Move to Archive',
            html: "Do you want to archive this note?",
            icon: 'warning',
            confirmButtonColor: "#36bcba",
            cancelButtonColor: "red",
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                
                $http({
                    method: 'POST',
                    url: "api/archive_note.php",
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    data: $.param({ 
                        note_id : $scope.current_note_id,                        
                        
                    })
                    
                }).then(function (response) {
                   

                    if(response.data > 0) {

                        Swal.fire({
                            title: 'Success!',
                            html: "Moved to archive!",
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });


                        $scope.fetch_notes($scope.default_note_stat);
                        
                    } else {


                        $scope.archive_err_message = "There was a problem archiving this note.";
                    }
                    
                    
        
                } , function (error) {
            
                    $scope.error_messages.push('An error occured while archiving this note.');
                    
                });;
                
            }
            
        });
        
    }
    








    // function to unarchive the note
    $scope.unarchive_note = function() {

        Swal.fire({
            title: 'Move to Archive',
            html: "Do you want to remove this from the archives?",
            icon: 'warning',
            confirmButtonColor: "#36bcba",
            cancelButtonColor: "red",
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                
                $http({
                    method: 'POST',
                    url: "api/unarchive_note.php",
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    data: $.param({ 
                        note_id : $scope.current_note_id,                                                
                    })
                    
                }).then(function (response) {
                                       

                    if(response.data > 0) {

                        Swal.fire({
                            title: 'Success!',
                            html: "Moved to archive!",
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });


                        $scope.fetch_notes($scope.default_note_stat);
                        
                    } else {


                        $scope.archive_err_message = "Server Response: " + response.data;
                    }
                    
                    
        
                } , function (error) {
            
                    $scope.error_messages.push('An error occured while unarchiving this note.');
                    
                });;
                
            }
            
        });        

        
    }










    $scope.note_fetcher = function(status) {

        $scope.fetch_notes(status);
        $scope.default_note_stat = status;

    }




    $scope.fetch_all_starred = function() {


        $scope.default_note_stat = 'active';
        $scope.fetch_notes($scope.default_note_stat, "true");

    }





    

    // MOBILE FUNCTIONS
    $scope.update_note_mobile = function() {

        // updated quill content
        $scope.quill_update_mobile = quill_update_mobile.root.innerHTML;
        
        
        $http({
            method: 'POST',
            url: "api/update_note.php",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            data: $.param({ 
                note_id : $scope.current_note_id,
                note_title : $scope.update_title,
                note_content : $scope.quill_update_mobile, 
                note_starred : $scope.update_starred,
                note_subject : $scope.update_subject
                
            })
            
        }).then(function (response) {
            
            
            
            
            // re-fetch notes list
            $scope.fetch_notes($scope.default_note_stat);
            
            Swal.fire({
                title: 'Success!',
                html: "Changes saved!",
                icon: 'success',
                confirmButtonText: 'OK'
            });
            
            
            $scope.view_note($scope.current_note_id);
            
            
            
            
        }, function (error) {
            
            $scope.error_messages.push('An error occured while updating this note.');
            
        });

        
    }

    
    
});