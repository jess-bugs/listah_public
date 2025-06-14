var app = angular.module('angularApp', []);

app.controller('angular_controller', function($scope, $http, $timeout, $filter) {
    
    
    
    
    /******* used for background setting for selected card */
    $scope.selectedNoteId = null;
    
    $scope.selectNote = function(noteId) {
        $scope.selectedNoteId = noteId;
        
        // $scope.show_edit_note_mobile();
        
        $scope.note_headers = false;
        $scope.edit_note_mobile = false;
        $scope.loading_anim = true;
        
    };
    /*******************************************************/
    
    
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
    
    
    
    $scope.show_profile_block = false;
    $scope.show_changepass_block = false;
    
    
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
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });
    
    
    
    
    
    
    
    const quill_create_mobile = new Quill('#editor-create-note-mobile', {
        
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });
    
    







    // function to discard and clear create note block
    $scope.discard_create_note = function() {

        $scope.createnote_quill_content = quill.root.innerHTML;
        let quill_content =  $scope.createnote_quill_content.replace(/<\/?[^>]+(>|$)/g, "");

        
        if (($scope.createnote_title && $scope.createnote_title.trim() !== "") || 
            (quill_content && quill_content.trim() !== "") || 
            ($scope.createnote_subject && $scope.createnote_subject.trim() !== "")) {

            Swal.fire({
                title: 'Discard Note',
                text: "Are you sure you want to discard this note? All unsaved changes will be lost.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#36bcba',
                cancelButtonColor: 'red',
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    // clear quill JS
                    quill.clipboard.dangerouslyPasteHTML("");
    
                    $scope.$apply(function() {
    
                        // clear the title
                        $scope.createnote_title = "";
    
                        // remove star
                        $scope.createnote_starred = false;
    
                        // clear error message
                        $scope.createnote_err_message = "";
    
                        // clear subject field
                        $scope.createnote_subject = "";
    
                    });
                }
            });

        }
        
    };





    
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
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Note created!',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000,
                        timerProgressBar: false
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
        $scope.show_profile_block =  false;
        $scope.show_changepass_block = false;
        
        
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
            
            
            
            
            // mobile - show animation for 1 second before displaying note content
            $timeout(function(){
                
                $scope.note_headers = false;
                $scope.edit_note_mobile = true;
                $scope.loading_anim = false;
                
            }, 500)
            
            
            
            
            
            
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
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Changes Saved!',
                showConfirmButton: false,
                showCancelButton: false,
                timer: 2000,
                timerProgressBar: false
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
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Note Archived!',
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 2000,
                            timerProgressBar: false
                        });
                        
                        $scope.fetch_notes($scope.default_note_stat);
                        $scope.view_note($scope.current_note_id);
                        
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
            title: 'Remove from Archives',
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
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Note removed from the archive!',
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 2000,
                            timerProgressBar: false
                        });
                        
                        $scope.fetch_notes($scope.default_note_stat);
                        $scope.view_note($scope.current_note_id);
                        
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
            
            
            if(response.data > 0) {
                
                
                // re-fetch notes list
                $scope.fetch_notes($scope.default_note_stat);
                
                $scope.view_note($scope.current_note_id);
                
                
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Changes saved!',
                    showConfirmButton: false,
                    showCancelButton: false,
                    timer: 2000,
                    timerProgressBar: false
                });
                
                
            } else {
                
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Failed!',
                    html: "Error: " + response.data,
                    showConfirmButton: false,
                    showCancelButton: false,
                    timer: 2000,
                    timerProgressBar: false
                });
                
            }
            
            
            
            
        }, function (error) {
            
            
            $scope.error_messages.push('An error occured while updating this note.');
            
        });
        
        
    }
    
    
    
    









    $scope.discard_create_note_mobile = function() {

        $scope.createnote_quill_content_mobile = quill_create_mobile.root.innerHTML;
        let quill_content =  $scope.createnote_quill_content_mobile.replace(/<\/?[^>]+(>|$)/g, "");

        
        if (($scope.createnote_title && $scope.createnote_title.trim() !== "") || 
            (quill_content && quill_content.trim() !== "") || 
            ($scope.createnote_subject && $scope.createnote_subject.trim() !== "")) {

            Swal.fire({
                title: 'Discard Note',
                text: "Are you sure you want to discard this note? All unsaved changes will be lost.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#36bcba',
                cancelButtonColor: 'red',
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    // clear quill JS
                    quill_create_mobile.clipboard.dangerouslyPasteHTML("");
    
                    $scope.$apply(function() {
    
                        // clear the title
                        $scope.createnote_title = "";
    
                        // remove star
                        $scope.createnote_starred = false;
    
                        // clear error message
                        $scope.createnote_err_message = "";
    
                        // clear subject field
                        $scope.createnote_subject = "";
    
                    });
                }
            });

        }
        
    };




    
    
    
    $scope.create_new_note_mobile = function() {
        
        $scope.createnote_quill_content_mobile = quill_create_mobile.root.innerHTML;
        
        let quill_content =  $scope.createnote_quill_content_mobile.replace(/<\/?[^>]+(>|$)/g, "")
        
        let note_title = $scope.createnote_title;
        
        if(quill_content == "" || note_title == "" || note_title == undefined) {
            
            
            $scope.createnote_err_message = "Fields cannot be empty.";
            
        } else {
            
            
            
            
            $http({
                method: 'POST',
                url: "api/create_note.php",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                data: $.param({ 
                    note_title : $scope.createnote_title,
                    note_content : $scope.createnote_quill_content_mobile,
                    note_starred : $scope.createnote_starred,
                    note_subject : $scope.createnote_subject
                })
                
            }).then(function(response) {
                
                
                
                if(response.data > 0) {
                    
                    // clear quill JS
                    quill_create_mobile.clipboard.dangerouslyPasteHTML("");
                    
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
                    
                    
                    // close mobile modal
                    $(".btn-close").click();
                    
                    
                    
                    
                    // show headers / hide create note for mobile
                    $scope.note_headers = true;
                    $scope.create_note_mobile = false;
                    
                    
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Note created!',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000,
                        timerProgressBar: false
                    });
                    
                    
                } else {
                    
                    
                    // $scope.createnote_err_message = "Failed to save note: " + response.data;
                    $scope.error_messages.push('Failed to create this note.');
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Failed!',
                        html: "Error: " + response.data,
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000,
                        timerProgressBar: false
                    });
                    
                    
                }
                
                
            }, function(error) {
                
                // $scope.createnote_err_message = "Backend Error: " + (error.data ? error.data : JSON.stringify(error));
                $scope.error_messages.push('Failed to create this note: ' + (error.data ? error.data : JSON.stringify(error)));
            })
        }
        
    }
    
    
    
    
    
    
    $scope.logout = function() {
        
        Swal.fire({
            title: 'Logout',
            html: "Do you really want to logout?",
            icon: 'warning',
            
            confirmButtonColor: "#36bcba",
            cancelButtonColor: "red",
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel'
        }).then(result => {
            
            if(result.isConfirmed) {
                
                
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Logging out...',
                    showConfirmButton: false,
                    showCancelButton: false,
                    timer: 500,
                    timerProgressBar: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false 
                });
                
                $timeout(function() {                                        
                    window.location.href = "notes.php?logout=y";
                }, 500);
                
            }
            
        });
        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    // for mobile viewing, updating, and creating
    $scope.note_headers = true;
    $scope.edit_note_mobile = false;
    $scope.loading_anim = false;
    
    $scope.show_edit_note_mobile = function() {
        
        $scope.note_headers = false;
        
        $scope.edit_note_mobile = true;
        
    }
    
    
    
    $scope.create_note_mobile = false;
    
    $scope.show_create_note_mobile = function() {
        
        $scope.note_headers = false;
        
        $scope.create_note_mobile = true;
        
    }
    
    // ***************************************
    
    
    
    
    
    $scope.delete_note = function() {
        
        
        
        Swal.fire({
            title: $scope.update_title,
            html: "Do you want to <span class='fw-bold'>permanently</span> delete this note?",
            icon: 'danger',
            confirmButtonColor: "#36bcba",
            cancelButtonColor: "red",
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            
            
            
            if(result.isConfirmed) {
                $http({
                    method: 'POST',
                    url: "api/delete_note.php",
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    data: $.param({ 
                        note_id : $scope.current_note_id,                        
                        
                    })
                    
                }).then(function (response) {
                    
                    if(response.data.status > 0) {
                        
                        $scope.fetch_notes($scope.default_note_stat);
                        
                        
                        // for desktop
                        $scope.show_create_note_block = true;
                        $scope.show_view_note_block = false;
                        
                        
                        // for mobile
                        $scope.note_headers = true;
                        $scope.edit_note_mobile = false;
                        
                        
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Note Deleted!',
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 2000,
                            timerProgressBar: false
                        });
                        
                    } else {
                        
                        Swal.fire({
                            title: 'Error',
                            html:response.data.message ,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        
                    }                            
                    
                } , function (error) {
                    
                    // handle error
                    
                });;
                
                
            }
            
            
        });
        
        
    }
    
    
    
    
    
    
    // show profile block
    $scope.see_profile = function() {
        
        
        $scope.show_changepass_block = false;
        $scope.show_create_note_block = false;
        $scope.show_view_note_block = false;
        
        $scope.show_profile_block = true;
        
        
    }
    
    
    
    
    // change password block
    $scope.changepass_block = function() {
        
        $scope.show_profile_block = false;
        $scope.show_create_note_block = false;
        $scope.show_view_note_block = false;
        
        $scope.show_changepass_block = true;
        
    }
    
    
    
    
    
    // switching active tab
    // geninfo
    $scope.current_tab = "geninfo";
    
    $scope.change_current_tab = function(tab) {
        
        $scope.current_tab = tab;
    }
    
    
    
    
    
    
    
    
    
    /****************************** USERMETA ******************************/
    
    
    
    $scope.usermeta_error = "";
    $scope.update_info_error = "";
    $scope.enable_edit_prof = false;
    
    // fetch user meta
    $scope.get_user_meta = function() {
        
        $http({
            method: 'POST',
            url: "api/get_usermeta.php",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            
        }).then(function (response) {
            
            if(response.data.status) {
                
                angular.forEach(response.data.rows, function(row) {
                    
                    $scope.view_um_userid = row.user_id;
                    $scope.view_um_username = row.user;
                    $scope.view_um_fname = row.first_name;
                    $scope.view_um_lname = row.last_name;
                    $scope.view_um_gender = row.gender;
                    $scope.view_um_profile_pic = row.image_path;
                    $scope.view_um_account_status = row.account_status;
                    $scope.view_um_role = row.role;
                    $scope.view_um_account_created = row.account_created;
                    
                    
                    
                    
                    $scope.update_fname = $scope.view_um_fname;
                    $scope.update_lname = $scope.view_um_lname;
                    $scope.update_gender = $scope.view_um_gender; 
                    
                })
            }
            
            
        }, function (error) {
            
            $scope.usermeta_error = "Failed to reach backend.";
            // handle error
            
        });
        
        
    }
    
    
    // initial fetch
    $scope.get_user_meta();
    
    
    
    
    
    
    
    // function to update profile
    $scope.update_profile = function() {
        
        let genders = ['Male', 'Female'];
        
        if(!validate_input($scope.update_lname) || !validate_input($scope.update_fname) || !validate_input($scope.update_gender)) {
            
            $scope.update_info_error = "Please complete all required fields";
            
        } else if($scope.update_lname.length <= 1 || $scope.update_fname.length <= 1) {
            
            $scope.update_info_error = "First and last name should be more than 2 characters in length";
            
            
        } else if(!genders.includes($scope.update_gender)) {
            
            $scope.update_info_error = "Not a valid gender";
            
        } else {
            
            
            $scope.update_info_error = "";
            
            $http({
                method: 'POST',
                url: "api/update_profile.php",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                data: $.param({ 
                    
                    first_name : $scope.update_fname,
                    last_name : $scope.update_lname,
                    gender : $scope.update_gender
                    
                })
                
            }).then(function (response) {
                
                if(response.data.status) {
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Changes Saved!',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 1000,
                        timerProgressBar: false
                    });
                    
                    
                    // refresh viewing data
                    $scope.get_user_meta();
                    
                    
                    // disable editing
                    $scope.enable_edit_prof = false;
                    
                } else {
                    
                    $scope.update_info_error = "Backend error: " + response.data.message;
                    
                }
                
            }, function (error) {
                
                // handle error
                $scope.update_info_error = "Didn't reach the backend."
                
            });
            
            
            
        }
        
    }
    
    
    
    
    // cancel updating profile
    $scope.cancel_update_profile = function() {
        
        $scope.enable_edit_prof = false;
        
        // set original values
        $scope.update_fname = $scope.view_um_fname;
        $scope.update_lname = $scope.view_um_lname;
        $scope.update_gender = $scope.view_um_gender; 
        
        
    }
    
    
    
    
    
    
    
    $scope.update_profile_pic = function() {
        
        $("#profile-file-input").click();
    }
    
    
    
    // file input trigger
    $("#profile-file-input").on('change', function() {
        
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                
                let src = e.target.result;
                
                
                Swal.fire({
                    title: "Update Avatar",
                    text: "This image source is dynamic.",
                    html: `
                    <img src="${src}" style="max-height: 250px;">
                    <p>Would you like to proceed to change your avatar?</p>
                    `,
                    imageAlt: "New Profile Pic",
                    showCancelButton: true,
                    confirmButtonText: "OK",
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        let profile_pic_input = $("#profile-file-input")[0];
                        
                        let formData = new FormData();                        
                        
                        if (profile_pic_input.files.length > 0) {
                            
                            formData.append("profile_image", profile_pic_input.files[0]);
                        } else {
                            
                            $scope.usermeta_error = "Failed to get image.";
                        }
                        
                        
                        
                        $http({
                            method: 'POST',
                            url: "api/update_profile_pic.php",
                            headers: { 'Content-Type': undefined },
                            data: formData,
                            transformRequest: angular.identity
                            
                        }).then(function (response) {
                            
                            $scope.usermeta_error = "";
                            
                            if(response.data.status) {
                                
                                // console.log(response.data.message);
                                $scope.get_user_meta();
                                
                            } else {
                                
                                
                                $scope.usermeta_error = "Server Response: " + response.data.message;
                            }
                            
                        }, function (error) {
                            
                            
                            // handle error
                            $scope.usermeta_error = "Failed to reach backend.";
                        });
                        
                    }
                });
                
                
            };
            reader.readAsDataURL(file);
        }
        
    })
    
    
    
    
    /****************************** End of USERMETA ******************************/
    
    
    
    
    
    
    
    
    
    
    
    
    
    /****************************** Change Password ******************************/
    
    
    $scope.changepass_error = "";
    $scope.show_new_password_block = false;
    
    
    // check current password if correct
    $scope.check_current_password = function() {
        
        if(validate_input($scope.current_password) && $scope.current_password.length >= 7) {
            
            
            $http({
                method: 'POST',
                url: "api/change_pass.php",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                data: $.param({ 
                    current_pass : $scope.current_password
                })
                
            }).then(function (response) {
                
                
                $scope.changepass_error = "";
                
                // password correct
                if(response.data.status) {
                    
                    $scope.show_new_password_block = true;
                    
                } else {
                    
                    $scope.changepass_error = response.data.message;
                    
                }
                
                
            }, function (error) {
                
                
                // handle error
                $scope.changepass_error = "Didn't reach backend.";
            });
            
            
            
        } else {
            
            $scope.changepass_error = "Password must be at least 7 characters in length.";
        }
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    // password is correct, change the current password
    $scope.change_password = function() {
        
        if(!validate_input($scope.new_pass) || !validate_input($scope.confirm_pass)) {
            
            $scope.changepass_error = "Please input a valid password.";
            
        } else if($scope.new_pass.length <=5) {
            
            $scope.changepass_error = "Password must be at least 6 characters.";
            
        } else if($scope.new_pass !== $scope.confirm_pass) {
            
            $scope.changepass_error = "Passwords did not match.";        
            
        } else if($scope.current_password == $scope.new_pass) {
            
            $scope.changepass_error = "You cannot use the old password.";        
            
        } else {
            
            
            $scope.changepass_error = "";
            
            $http({
                method: 'POST',
                url: "api/change_pass.php",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                data: $.param({ 
                    current_pass : $scope.current_password,
                    new_pass : $scope.new_pass,
                    confirm_pass : $scope.confirm_pass
                })
                
            }).then(function (response) {
                
                if(response.data.status) {
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Password changed!',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 3000,
                        timerProgressBar: false
                    });
                    
                    
                    $scope.current_password = "";
                    
                    // hide new password block
                    $scope.show_new_password_block = false;
                    
                    
                } else {
                    
                    $scope.changepass_error = "Backend Error: " + response.data.message;
                }
                
            }, function (error) {
                
                
                // handle error
                $scope.changepass_error = "Didn't reach the backend."
                
            });
            
            
        }
        
    }
    
    
    
    /************************* End of Change Password ***************************/
    
    
    
    
    
    
    
    
    
    
    
    
    
    /************************* Delete Account ***************************/
    
    
    $scope.show_delete_acct_block = false;
    $scope.delete_pass_error = "";
    $scope.delete_account = function() {
        
        
        
        if(validate_input($scope.delete_account_passsword)) {
            
            
            $http({
                method: 'POST',
                url: "api/change_pass.php",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                data: $.param({                     
                    current_pass : $scope.delete_account_passsword
                })
                
            }).then(function (response) {
                
                if(response.data.status) {
                    
                    $scope.delete_pass_error = "";
                    
                    
                    Swal.fire({
                        title: 'Account Deletion',
                        html: "You are about to permanently delete your account and its note contents. <span class='text-danger fw-bold'>This cannot be undone.</span> Would you like to proceed?",
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
                                url: "api/delete_account.php",
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                data: $.param({ 
                                    
                                })
                                
                            }).then(function (response) {
                                
                                
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Redirecting...',
                                    showConfirmButton: false,
                                    showCancelButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    allowEnterKey: false 
                                });
                                
                                
                                $timeout(function() {                                        
                                    window.location.href = "notes.php?logout=y";
                                }, 2000);
                                
                                
                                
                            }, function (error) {
                                
                                
                                // handle error
                                $scope.delete_pass_error = "Didn't reach the backend";
                                
                            });
                            
                        } else {
                            
                            $scope.$apply(function() {
                                
                                $scope.delete_account_passsword = "";
                            })
                        }
                    })
                    
                    
                } else {
                    
                    $scope.delete_pass_error  = "Password Incorrect";
                    
                }
                
            }, function (error) {
                
                
                // handle error
                $scope.delete_pass_error  = "Didn't reach the backend";
                
            });
            
            
            
            
        } else {
            
            $scope.delete_pass_error = "Password invalid.";
        }
        
        
        
    }
    
    /************************* End of Delete Account ***************************/
    
    
    
    
    
    
    
    
    
    
    
    /************************* Edit Profile Block ***************************/
    
    
    $scope.edit_profile_block = false;
    
    $scope.edit_profile_mobile = function(editing) {
        
        if(editing) {
        
            $scope.note_headers = false;
            $scope.edit_note_mobile = false;
            $scope.create_note_mobile = false;
            $scope.edit_profile_block = true;
            
        } else {

            $scope.note_headers = true;
            $scope.edit_profile_block = false;

            $scope.current_tab = "geninfo";
        }

        
        
        
    };
    
    
    
    /************************* End of Edit Profile Block ***************************/
    
    
    
    
    // time formatter
    $scope.formatDate = function(datetime) {
        if (!datetime) return '';
        return $filter('date')(new Date(datetime), 'MMM d, yyyy');
    };
    
    
    
    // validator
    function validate_input(data) {
        
        if(data !== "" && data !== undefined) {
            
            return true;
        }
    }
});