<div id="chats-lsv-admin"></div>
<script>
    var deleteItem = function(itemid, type) {
        if (type === 'room') {
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'deleteroom',
                        'agentId': agentId,
                        'roomId': itemid
                    }
                })
                .done(function(data) {
                    location.reload();
                })
                .fail(function() {
                    console.log(false);
                });
        } else if (type === 'agent') {
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'deleteagent',
                        'agentId': itemid
                    }
                })
                .done(function(data) {
                    location.reload();
                })
                .fail(function() {
                    console.log(false);
                });
        } else if (type === 'user') {
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'deleteuser',
                        'userId': itemid
                    }
                })
                .done(function(data) {
                    location.reload();
                })
                .fail(function() {
                    console.log(false);
                });
        } else if (type === 'recording') {
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'deleterecording',
                        'recordingId': itemid
                    }
                })
                .done(function(data) {
                    location.reload();
                })
                .fail(function() {
                    console.log(false);
                });
        } else if (type === 'uploads') {
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'deleteuploads',
                        'uploadsId': itemid
                    }
                })
                .done(function(data) {
                    location.reload();
                })
                .fail(function() {
                    console.log(false);
                });
        } else if (type === 'category') {
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'deleteCategory',
                        'categoryId': itemid
                    }
                })
                .done(function(data) {
                    location.reload();
                })
                .fail(function() {
                    console.log(false);
                });
        } else if (type === 'quiz') {
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'deleteQuiz',
                        'quizId': itemid
                    }
                })
                .done(function(data) {
                    location.reload();
                })
                .fail(function() {
                    console.log(false);
                });
        } else if (type === 'deleteEssayTypeQuiz') {
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'deleteEssayTypeQuiz',
                        'quiz_essay_id': itemid
                    }
                })
                .done(function(data) {
                    location.reload();
                })
                .fail(function() {
                    console.log(false);
                });
        }

    };
    var isAdmin = true;
    var roomId = false;
    <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
        var agentId = false;
    <?php } else { ?>
        var agentId = "<?php echo $_SESSION["tenant"]; ?>";
    <?php } ?>
</script>



</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; CLE Video Chat 2019-<?php echo date('Y'); ?></span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" data-localize="ready_leave"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" data-localize="select_logout"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal" data-localize="cancel"></button>
                <a class="btn btn-primary" href="logout.php" data-localize="logout"></a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="generateBroadcastLinkModal" tabindex="-1" role="dialog" aria-labelledby="generateBroadcastLinkModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" data-localize="broadcasting_attendee_url"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" data-localize="broadcasting_attendee_info"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal" data-localize="close"></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="generateLinkModal" tabindex="-1" role="dialog" aria-labelledby="generateLinkModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" data-localize="video_attendee_url"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" data-localize="video_attendee_info"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal" data-localize="close"></button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>
<script src="js/detect.js"></script>

<?php if ($basename == 'agent.php') { ?>
    <script>
        <?php
        if (isset($_GET['id'])) {
        ?>
            $('#usernameDiv').hide();
        <?php
        } else {
        ?>
            $('#usernameDiv').show();
        <?php
        }
        ?>
        jQuery(document).ready(function($) {
            $('#error').hide();
            $('#saveAgent').click(function(event) {
            //validation for blanks
                
                for (i=0; i < 6; i++){
                    document.forms["AgentForm"]["first_name"].value
                }

                var a = document.forms["AgentForm"]["first_name"].value;
                var b = document.forms["AgentForm"]["last_name"].value;
                <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
                    var c = document.forms["AgentForm"]["email"].value;
                    var d = document.forms["AgentForm"]["tenant"].value;
                    var e = document.forms["AgentForm"]["username"].value;
                    // var f = document.forms["AgentForm"]["password"].value;
                    
                    if (
                        a == null || a == "" || 
                        b == null || b == "" ||  
                        c == null || c == "" || 
                        d == null || d == "" || 
                        e == null || e == ""  
                        // f == null || f == ""
                        ) {
                            alert("Please Fill All Required Field with red * marks");
                            return false;
                    }
                <?php }else{ ?>
                    if (
                        a == null || a == "" || 
                        b == null || b == ""   
                        ) {
                            alert("Please Fill All Required Field with red * marks");
                            return false;
                    }
                <?php }?>
                <?php
                if (isset($_GET['id'])) {
                ?>
                    var dataObj = {
                        'type': 'editagent',
                        'agentId': <?php echo $_GET['id']; ?>,
                        'firstName': $('#first_name').val(),
                        'lastName': $('#last_name').val(),
                        'tenant': $('#tenant').val(),
                        'email': $('#email').val(),
                        'password': $('#password').val(),
                        'usernamehidden': $('#usernamehidden').val()
                    };
                <?php
                } else {
                ?>
                    var dataObj = {
                        'type': 'addagent',
                        'username': $('#username').val(),
                        'firstName': $('#first_name').val(),
                        'lastName': $('#last_name').val(),
                        'tenant': $('#tenant').val(),
                        'email': $('#email').val(),
                        'password': $('#password').val()
                    };
                <?php
                }
                ?>
                $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: dataObj
                    })
                    .done(function(data) {
                        if (data) {
                            <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
                                location.href = 'agents.php';
                            <?php }else{ ?>
                                location.href = 'agent.php?id=<?php echo $_GET['id'];?>';
                            <?php } ?>    

                        } else {
                            $('#error').show();
                            $('#error').html('<span data-localize="error_agent_save"></span>');
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function() {});
            });
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getadmin',
                        'id': <?php echo (int) @$_GET['id'] ?>
                    }
                })
                .done(function(data) {
                    if (data) {
                        data = JSON.parse(data);
                        $('#agentTitle').html(data.first_name + ' ' + data.last_name);
                        $('#usernamehidden').val(data.username);
                        $('#username').val(data.username);
                        if (data.password) {
                            $('#leftblank').html(' <span data-localize="left_blank_changed"></span>');
                        }
                        //$('#password').val(data.password);
                        $('#first_name').val(data.first_name);
                        $('#last_name').val(data.last_name);
                        $('#tenant').val(data.tenant);
                        $('#email').val(data.email);
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}
if ($basename == 'config.php') {
?>


    <script>
        jQuery(document).ready(function($) {
            $('#error').hide();
            $('#videoScreen_exitMeetingDrop').on('change', function() {
                if (this.value == 3) {
                    $('#videoScreen_exitMeeting').show();
                } else {
                    $('#videoScreen_exitMeeting').hide();
                }
            });
            $('#saveConfig').click(function(event) {
                if ($('#videoScreen_exitMeetingDrop').val() == 1) {
                    var exitMeeting = false;
                } else if ($('#videoScreen_exitMeetingDrop').val() == 2) {
                    exitMeeting = '/';
                } else if ($('#videoScreen_exitMeetingDrop').val() == 3) {
                    exitMeeting = $('#videoScreen_exitMeeting').val()
                }
                var dataObj = {
                    'type': 'updateconfig',
                    'fileName': '<?php echo $fileConfig; ?>',
                    'data': {
                        'appWss': $('#appWss').val(),
                        'agentName': $('#agentName').val(),
                        'agentAvatar': $('#agentAvatar').val(),
                        'smartVideoLanguage': $('#smartVideoLanguage').val(),
                        'anonVisitor': $('#anonVisitor').val(),
                        'entryForm.enabled': $('#entryForm_enabled').prop('checked'),
                        'entryForm.required': $('#entryForm_required').prop('checked'),
                        'entryForm.private': $('#entryForm_private').prop('checked'),
                        'entryForm.showEmail': $('#entryForm_showEmail').prop('checked'),
                        'entryForm.showAvatar': $('#entryForm_showAvatar').prop('checked'),
                        'recording.enabled': $('#recording_enabled').prop('checked'),
                        'recording.download': $('#recording_download').prop('checked'),
                        'recording.saveServer': $('#recording_saveServer').prop('checked'),
                        'recording.autoStart': $('#recording_autoStart').prop('checked'),
                        'recording.screen': $('#recording_screen').prop('checked'),
                        'recording.oneWay': $('#recording_oneWay').prop('checked'),
                        'recording.transcode': $('#recording_transcode').prop('checked'),
                        'whiteboard.enabled': $('#whiteboard_enabled').prop('checked'),
                        'whiteboard.allowAnonymous': $('#whiteboard_allowAnonymous').prop('checked'),
                        'videoScreen.greenRoom': $('#videoScreen_greenRoom').prop('checked'),
                        'videoScreen.waitingRoom': $('#videoScreen_waitingRoom').prop('checked'),
                        'videoScreen.videoConference': $('#videoScreen_videoConference').prop('checked'),
                        'videoScreen.onlyAgentButtons': $('#videoScreen_onlyAgentButtons').prop('checked'),
                        'videoScreen.getSnapshot': $('#videoScreen_getSnapshot').prop('checked'),
                        'videoScreen.separateScreenShare': $('#videoScreen_separateScreenShare').prop('checked'),
                        'videoScreen.enableLogs': $('#videoScreen_enableLogs').prop('checked'),
                        'videoScreen.broadcastAttendeeVideo': $('#videoScreen_broadcastAttendeeVideo').prop('checked'),
                        'videoScreen.allowOtherSee': $('#videoScreen_allowOtherSee').prop('checked'),
                        'videoScreen.localFeedMirrored': $('#videoScreen_localFeedMirrored').prop('checked'),
                        'videoScreen.exitMeetingOnTime': $('#videoScreen_exitMeetingOnTime').prop('checked'),
                        'videoScreen.primaryCamera': $('#videoScreen_primaryCamera').val(),
                        'videoScreen.videoFileStream': $('#videoScreen_videoFileStream').val(),
                        'videoScreen.videoConstraint': ($('#videoScreen_videoConstraint').val()) ? JSON.parse($('#videoScreen_videoConstraint').val()) : '',
                        'videoScreen.audioConstraint': ($('#videoScreen_audioConstraint').val()) ? JSON.parse($('#videoScreen_audioConstraint').val()) : '',
                        'videoScreen.screenConstraint': ($('#videoScreen_screenConstraint').val()) ? JSON.parse($('#videoScreen_screenConstraint').val()) : '',
                        'videoScreen.exitMeeting': exitMeeting,
                        'serverSide.loginForm': $('#serverSide_loginForm').prop('checked'),
                        'serverSide.chatHistory': $('#serverSide_chatHistory').prop('checked'),
                        'serverSide.feedback': $('#serverSide_feedback').prop('checked'),
                        'serverSide.checkRoom': $('#serverSide_checkRoom').prop('checked'),
                        'iceServers.iceServers': ($('#iceServers').val()) ? JSON.parse($('#iceServers').val()) : '',
                        'iceServers.requirePass': $('#iceServers_requirePass').prop('checked'),
                        'transcribe.languageTo': $('#transcribe_languageTo').val(),
                        'transcribe.language': $('#transcribe_language').val(),
                        'transcribe.direction': $('#transcribe_direction').val(),
                        'transcribe.apiKey': $('#transcribe_apiKey').val(),
                        'transcribe.enabled': $('#transcribe_enabled').prop('checked')
                    }
                };
                $.ajax({
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        url: '../server/script.php',
                        data: dataObj
                    })
                    .done(function(data) {
                        if (data) {
                            location.href = 'config.php?file=<?php echo $fileConfig; ?>';
                        } else {
                            $('#error').show();
                            $('#error').html('<span data-localize="error_config_save"></span>');
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function(e) {
                        console.log(e);
                    });
            });
            $('#addConfig').click(function(event) {
                var dataObj = {
                    'type': 'addconfig',
                    'fileName': $('#fileName').val()
                };
                $.ajax({
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        url: '../server/script.php',
                        data: dataObj
                    })
                    .done(function(data) {
                        if (data) {
                            location.href = 'config.php?file=' + $('#fileName').val();
                        } else {
                            $('#error').show();
                            $('#error').html('<span data-localize="error_config_add"></span>');
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function(e) {
                        console.log(e);
                    });
            });
            <?php
            $jsonString = file_get_contents('../config/' . $fileConfig . '.json');
            $data = json_decode($jsonString);
            ?>

            $('#appWss').val('<?php echo @$data->appWss; ?>');
            $('#agentName').val('<?php echo @$data->agentName; ?>');
            $('#agentAvatar').val('<?php echo @$data->agentAvatar; ?>');
            $('#smartVideoLanguage').val('<?php echo @$data->smartVideoLanguage; ?>');
            $('#anonVisitor').val('<?php echo @$data->anonVisitor; ?>');
            $('#entryForm_enabled').prop('checked', <?php echo @$data->entryForm->enabled; ?>);
            $('#entryForm_required').prop('checked', <?php echo @$data->entryForm->required; ?>);
            $('#entryForm_private').prop('checked', <?php echo @$data->entryForm->private; ?>);
            $('#entryForm_showEmail').prop('checked', <?php echo @$data->entryForm->showEmail; ?>);
            $('#entryForm_showAvatar').prop('checked', <?php echo @$data->entryForm->showAvatar; ?>);
            $('#recording_enabled').prop('checked', <?php echo @$data->recording->enabled; ?>);
            $('#recording_download').prop('checked', <?php echo @$data->recording->download; ?>);
            $('#recording_saveServer').prop('checked', <?php echo @$data->recording->saveServer; ?>);
            $('#recording_autoStart').prop('checked', <?php echo @$data->recording->autoStart; ?>);
            $('#recording_screen').prop('checked', <?php echo @$data->recording->screen; ?>);
            $('#recording_oneWay').prop('checked', <?php echo @$data->recording->oneWay; ?>);
            $('#recording_transcode').prop('checked', <?php echo @$data->recording->transcode; ?>);
            $('#whiteboard_enabled').prop('checked', <?php echo @$data->whiteboard->enabled; ?>);
            $('#whiteboard_allowAnonymous').prop('checked', <?php echo @$data->whiteboard->allowAnonymous; ?>);
            $('#videoScreen_greenRoom').prop('checked', <?php echo @$data->videoScreen->greenRoom; ?>);
            $('#videoScreen_waitingRoom').prop('checked', <?php echo @$data->videoScreen->waitingRoom; ?>);
            $('#videoScreen_videoConference').prop('checked', <?php echo @$data->videoScreen->videoConference; ?>);
            $('#videoScreen_onlyAgentButtons').prop('checked', <?php echo @$data->videoScreen->onlyAgentButtons; ?>);
            $('#videoScreen_getSnapshot').prop('checked', <?php echo @$data->videoScreen->getSnapshot; ?>);
            $('#videoScreen_separateScreenShare').prop('checked', <?php echo @$data->videoScreen->separateScreenShare; ?>);
            $('#videoScreen_broadcastAttendeeVideo').prop('checked', <?php echo @$data->videoScreen->broadcastAttendeeVideo; ?>);
            $('#videoScreen_allowOtherSee').prop('checked', <?php echo @$data->videoScreen->allowOtherSee; ?>);
            $('#videoScreen_localFeedMirrored').prop('checked', <?php echo @$data->videoScreen->localFeedMirrored; ?>);
            $('#videoScreen_exitMeetingOnTime').prop('checked', <?php echo @$data->videoScreen->exitMeetingOnTime; ?>);
            $('#videoScreen_primaryCamera').val('<?php echo @$data->videoScreen->primaryCamera; ?>');
            $('#videoScreen_videoFileStream').val('<?php echo @$data->videoScreen->videoFileStream; ?>');
            $('#videoScreen_videoConstraint').val('<?php echo (isset($data->videoScreen->videoConstraint)) ? json_encode($data->videoScreen->videoConstraint, JSON_FORCE_OBJECT) : ''; ?>');
            $('#videoScreen_audioConstraint').val('<?php echo (isset($data->videoScreen->audioConstraint)) ? json_encode($data->videoScreen->audioConstraint, JSON_FORCE_OBJECT) : ''; ?>');
            $('#videoScreen_screenConstraint').val('<?php echo (isset($data->videoScreen->screenConstraint)) ? json_encode($data->videoScreen->screenConstraint, JSON_FORCE_OBJECT) : ''; ?>');
            var exitMeeting = '<?php echo addslashes($data->videoScreen->exitMeeting); ?>';
            if (exitMeeting == false) {
                $('#videoScreen_exitMeetingDrop').val(1);
                $('#videoScreen_exitMeeting').hide();
            } else if (exitMeeting == '/') {
                $('#videoScreen_exitMeetingDrop').val(2);
                $('#videoScreen_exitMeeting').hide();
            } else {
                $('#videoScreen_exitMeetingDrop').val(3);
                $('#videoScreen_exitMeeting').show();
                $('#videoScreen_exitMeeting').val(exitMeeting);
            }

            $('#serverSide_loginForm').prop('checked', <?php echo @$data->serverSide->loginForm; ?>);
            $('#serverSide_chatHistory').prop('checked', <?php echo @$data->serverSide->chatHistory; ?>);
            $('#serverSide_feedback').prop('checked', <?php echo @$data->serverSide->feedback; ?>);
            $('#serverSide_checkRoom').prop('checked', <?php echo @$data->serverSide->checkRoom; ?>);
            $('#iceServers').val('<?php echo (isset($data->iceServers->iceServers)) ? json_encode($data->iceServers->iceServers) : ''; ?>')
            $('#iceServers_requirePass').prop('checked', <?php echo @$data->iceServers->requirePass; ?>);
            $('#videoScreen_enableLogs').prop('checked', <?php echo @$data->videoScreen->enableLogs; ?>);
            $('#transcribe_enabled').prop('checked', <?php echo @$data->transcribe->enabled; ?>);
            $('#transcribe_language').val('<?php echo @$data->transcribe->language; ?>');
            $('#transcribe_languageTo').val('<?php echo @$data->transcribe->languageTo; ?>');
            $('#transcribe_direction').val('<?php echo @$data->transcribe->direction; ?>');
            $('#transcribe_apiKey').val('<?php echo @$data->transcribe->apiKey; ?>');
        });
    </script>

<?php
}
if ($basename == 'locale.php') {
?>


    <script>
        <?php
        $jsonString = file_get_contents('../locales/' . $fileLocale . '.json');

        $data = json_decode($jsonString, true);
        $fileContent = '';
        $fileData = '';
        foreach ($data as $key => $value) {
            $fileContent .= '<div class="form-group"><label for="roomName"><h6>' . $key . ':</h6></label><input type="text" class="form-control" id="' . $key . '" aria-describedby="' . $key . '" value="' . htmlentities(addslashes($value)) . '"></div>';
            $fileData .= "'" . $key . "': $('#" . $key . "').val(),";
        };
        $fileData = substr($fileData, 0, -1);
        ?>
        jQuery(document).ready(function($) {
            $('#error').hide();
            $('#saveLocale').click(function(event) {
                var dataObj = {
                    'type': 'updatelocale',
                    'fileName': '<?php echo $fileLocale; ?>',
                    'data': {
                        '<?php echo $fileData; ?>',
                    }
                };
                $.ajax({
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        url: '../server/script.php',
                        data: dataObj
                    })
                    .done(function(data) {
                        if (data) {
                            location.href = 'locale.php?file=<?php echo $fileLocale; ?>';
                        } else {
                            $('#error').show();
                            $('#error').html('<span data-localize="error_locale_save"></span>');
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function(e) {
                        console.log(e);
                    });
            });
            $('#addLocale').click(function(event) {
                var dataObj = {
                    'type': 'addlocale',
                    'fileName': $('#fileName').val()
                };
                $.ajax({
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        url: '../server/script.php',
                        data: dataObj
                    })
                    .done(function(data) {
                        if (data) {
                            location.href = 'locale.php?file=' + $('#fileName').val();
                        } else {
                            $('#error').show();
                            $('#error').html('<span data-localize="error_locale_add"></span>');
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function(e) {
                        console.log(e);
                    });
            });

            $('#localeStrings').html('<?php echo $fileContent; ?>');
        });
    </script>

<?php
}
if ($basename == 'user.php') {
?>


    <script>
        jQuery(document).ready(function($) {
            $('#error').hide();
            $('#saveUser').click(function(event) {
                var isBlocked = ($('#is_blocked').prop('checked')) ? 1 : 0;
                <?php
                if (isset($_GET['id'])) {
                ?>
                    var name = $('#first_name').val() + ' ' + $('#last_name').val();
                    var dataObj = {
                        'type': 'edituser',
                        'userId': <?php echo $_GET['id']; ?>,
                        'name': name,
                        'firstName': $('#first_name').val(),
                        'lastName': $('#last_name').val(),
                        'username': $('#email').val(),
                        'password': $('#password').val(),
                        'credits': $('#credits').val(),
                        'minimum_credit_score': $('#minimum_credit_score').val(),
                        'isBlocked': isBlocked
                        
                    };
                <?php
                } else {
                ?>
                    var dataObj = {
                        'type': 'adduser',
                        'username': $('#email').val(),
                        'firstName': $('#first_name').val(),
                        'lastName': $('#last_name').val(),
                        'name': $('#first_name').val() + ' ' + $('#last_name').val(),
                        'password': $('#password').val(),
                        'credits': $('#credits').val(),
                        'minimum_credit_score': $('#minimum_credit_score').val(),
                        'isBlocked': isBlocked
                    };
                <?php
                }
                ?>
                $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: dataObj
                    })
                    .done(function(data) {
                        if (data) {
                            console.log(data);
                            location.href = 'users.php';
                        } else {
                            $('#error').show();
                            $('#error').html('<span data-localize="error_user_save"></span>');
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function() {});
            });
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getuser',
                        'id': <?php echo (int) @$_GET['id'] ?>
                    }
                })
                .done(function(data) {
                    if (data) {
                        data = JSON.parse(data);
                        $('#userTitle').html(data.name);
                        $('#username').val(data.username);
                        if (data.password) {
                            $('#leftblank').html('<span data-localize="left_blank_changed"></span>');
                        }
                        //$('#password').val(data.password);
                        if (!data.first_name && !data.last_name) {
                            var name = data.name.split(' ');
                            data.first_name = name[0];
                            data.last_name = name[1];
                        }
                        $('#first_name').val(data.first_name);
                        $('#last_name').val(data.last_name);
                        $('#email').val(data.username);
                        $('#credits').val(data.credits);
                        $('#minimum_credit_score').val(data.minimum_credit_score);
                        $('#is_blocked').prop('checked', (data.is_blocked == "1"));
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}
if ($basename == 'agents.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getagents'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $.each(result, function(i, item) {

                            if (item.is_master == 1) {
                                var deleteEditLink = '<a href="agent.php?id=' + item.agent_id + '" data-localize="edit"></a>';
                            } else {
                                deleteEditLink = '<a href="agent.php?id=' + item.agent_id + '" data-localize="edit"></a> | <a href="javascript:void(0);" id="deleteagent' + item.agent_id + '" data-localize="delete"></a>';
                            }
                            $('<tr>').append(
                                $('<td>').text(item.username),
                                $('<td>').text(item.first_name + ' ' + item.last_name),
                                $('<td>').text(item.tenant),
                                $('<td>').text(item.email),
                                $('<td>').html(deleteEditLink)
                            ).appendTo('#agents_table');
                            $('#deleteagent' + item.agent_id).on('click', function() {
                                deleteItem(item.agent_id, 'agent');
                            });
                        });

                        $('#agents_table').DataTable({
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);



                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}
if ($basename == 'users.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getusers'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $.each(result, function(i, item) {
                            var yesNo = (item.is_blocked == "1") ? '<span data-localize="yes"></span>' : '<span data-localize="no"></span>';
                            var loginstatus = (item.status == "1") ? '<span style="color:green;" data-localize="online">Online</span>' : '<span style="color:red;" data-localize="ofline">Ofline</span>';
                            $('<tr>').append(
                                $('<td>').text(item.name),
                                $('<td>').text(item.username),
                                $('<td>').html(loginstatus),
                                // $('<td>').text(item.status),
                                $('<td>').html(yesNo),
                                $('<td>').text(item.credits),
                                $('<td>').text(item.minimum_credit_score),
                                // $('<td>').html('<a href="user.php?id=' + item.user_id + '" data-localize="edit"></a> | <a href="javascript:void(0);" id="deleteuser' + item.user_id + '" data-localize="delete"></a>')
                                $('<td>').html('<a href="user.php?id=' + item.user_id + '" data-localize="edit"></a>')
                            ).appendTo('#users_table');
                            $('#deleteuser' + item.user_id).on('click', function() {
                                deleteItem(item.user_id, 'user');
                            });
                        });
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#users_table').DataTable({
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}

if ($basename == 'userDetailedReport.php') {
    ?>
        <script>
            jQuery(document).ready(function($) {
                $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: {
                            'type': 'getUsersDetailedReport'
                        }
                    })
                    .done(function(data) {
                        if (data) {
                            var result = JSON.parse(data);
                   
                            $.each(result, function(i, item) {
                                var yesNo = (item.is_blocked == "1") ? '<span data-localize="yes"></span>' : '<span data-localize="no"></span>';
                                var loginstatus = (item.status == "1") ? '<span style="color:green;" data-localize="online">Online</span>' : '<span style="color:red;" data-localize="ofline">Ofline</span>';
                                
                                $('<tr>').append(
                                    $('<td>').text(item.name),
                                    $('<td>').text(item.username),
                                    $('<td>').html(loginstatus),
                                    // $('<td>').text(item.status),
                                    $('<td>').html(yesNo),
                                    $('<td>').text(item.credits),
                                    $('<td>').text(item.minimum_credit_score),
                                    $('<td>').html('<a href="usershoursByusersDetailsReport.php?username=' + item.username +'">'+item.username+'</a>'),
                                    
                                ).appendTo('#users_table');
                                
                                $('#deleteuser' + item.user_id).on('click', function() {
                                    deleteItem(item.user_id, 'user');
                                });
                            });
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'copy', 'csv', 'excel', 'pdf', 'print'
                                    'copy','csv', 'excel'
                                ]
                            });
                        }
                    })
                    .fail(function(e) {
                        console.log(e);
                    });
            });
            
               
            
        </script>
    
    <?php
}
if ($basename == 'usershours.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'usershours'
                }
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);
                            var join = 0;
                            var left = 0;
                            var joined = 0;
                            var oldStartTime = '';
                            var startTime = '';
                            var endTime = '';
                            var add = 0;
                            var add_array = [];
                            sum = 0;
                            $.each(result, function (i, item) {
                                console.log('i = '+i);
                                var yesNo = (item.is_blocked == "1") ? '<span data-localize="yes"></span>' : '<span data-localize="no"></span>';
                                if(join == 0 && item.system == "visitor joined"){
                                    join=1;
                                    oldStartTime = new Date(item.date_created);
                                }else if(item.system == "visitor left"){
                                    left=1;
                                }
                                if(join == 1 && left == 1 ){
                                    console.log('pair complete');
                                    endTime = new Date(item.date_created);
                                    var difference = endTime.getTime() - oldStartTime.getTime(); // This will 
                                    var resultInMinutes = Math.round(difference / 60000); 
                                    console.log(endTime+'<br>'+oldStartTime);
                                    console.log(resultInMinutes); 
                                    var total =resultInMinutes;
                                    join=0; left=0;
                                    joined=0;
                                }else{
                                    joined++
                                    console.log('joined='+joined);
                                    console.log('join='+join);
                                    if(joined == 2){
                                        newStartTime = new Date(item.date_created);
                                        // endTime = newStartTime;
                                        var difference = newStartTime.getTime() - oldStartTime.getTime(); // This will 
                                        
                                        var resultInMinutes = Math.round(difference / 60000);
                                        console.log('----------------pair complete with continue visitor joined');
                                       
                                        console.log(newStartTime+'<br>'+oldStartTime);
                                        console.log(resultInMinutes); 
                                        var total = resultInMinutes; 
                                        //when visitor joined & visitor joined come at very next
                                        oldStartTime = newStartTime
                                        joined=0;
                                    }
                                    console.log('pair not complete');
                                }
                                
                                if(item.system == 'visitor left' && total >= 0){
                                    add_array.push(total);
                                    sum += (total);
                                }
                               
                                $('<tr>').append(
                                        $('<td>').text(item.logged_id),
                                        $('<td>').text(item.message),
                                        $('<td>').text(item.system),
                                        $('<td>').text(item.room_id),
                                        $('<td>').html(item.date_created),
                                        $('<td>').html(total),
                                        ).appendTo('#users_table');
                            });
                            console.log(add_array);
                            console.log(sum);
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                }
                            });
                        }
                        
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
    <?php
}
if ($basename == 'agentshoursByrooms.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'agentshoursByrooms'
                }
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);
                            
                            $.each(result, function (i, item) {
                               
                                $('<tr>').append(
                                        $('<td>').text(item.logged_id),
                                        $('<td>').html('<a href="agentshoursByagents.php?roomId=' + item.room_id + '">'+item.room_id+'</a>'),
                                        // $('<td>').html(item.date_created),
                                        // $('<td>').html(total),
                                        ).appendTo('#users_table');
                            });
                            
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                },
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'copy', 'csv', 'excel', 'pdf', 'print'
                                    'copy','csv', 'excel'
                                ]
                            });
                        }
                        
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
    <?php
}
if ($basename == 'agentshoursByagents.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'agentshoursByagents',
                        'roomId': '<?php echo $_GET['roomId']; ?>'
                }
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);
                           
                            $.each(result, function (i, item) {
                               
                                $('<tr>').append(
                                        $('<td>').text(item.logged_id),
                                        // $('<td>').text(item.message),
                                        // $('<td>').text(item.system),
                                        $('<td>').html(item.room_id),
                                        $('<td>').html('<a href="agentshoursByagentsDetails.php?email=' + item.email + '&roomId=' + item.room_id + '">'+item.email+'</a>'),
                                        // $('<td>').html(item.date_created),
                                        // $('<td>').html(total),
                                        ).appendTo('#users_table');
                            });
                            
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                },
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'copy', 'csv', 'excel', 'pdf', 'print'
                                    'copy','csv', 'excel'
                                ]
                            });
                        }
                        
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
    <?php
}
if ($basename == 'agentshoursByagentsDetails.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'agentshoursByagentsDetails',
                        'email': '<?php echo $_GET['email']; ?>',
                        'roomId': '<?php echo $_GET['roomId']; ?>',
                }
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);
                            var join = 0;
                            var left = 0;
                            var joined = 0;
                            var oldStartTime = '';
                            var startTime = '';
                            var endTime = '';
                            var add = 0;
                            var add_array = [];
                            sum = 0;
                            $.each(result, function (i, item) {
                                // console.log('i = '+i);
                                
                                // if(join == 0 && item.system == "agent joined"){
                                //     join=1;
                                //     oldStartTime = new Date(item.date_created);
                                // }else if(item.system == "agent left"){
                                //     left=1;
                                // }
                                // if(join == 1 && left == 1 ){
                                //     console.log('pair complete');
                                //     endTime = new Date(item.date_created);
                                //     var difference = endTime.getTime() - oldStartTime.getTime(); // This will 
                                //     var resultInMinutes = Math.round(difference / 60000); 
                                //     console.log(endTime+'<br>'+oldStartTime);
                                //     console.log(resultInMinutes); 
                                //     var total =resultInMinutes;
                                //     join=0; left=0;
                                //     joined=0;
                                // }else{
                                //     joined++
                                //     console.log('joined='+joined);
                                //     console.log('join='+join);
                                //     if(joined == 2){
                                //         newStartTime = new Date(item.date_created);
                                //         // endTime = newStartTime;
                                //         var difference = newStartTime.getTime() - oldStartTime.getTime(); // This will 
                                        
                                //         var resultInMinutes = Math.round(difference / 60000);
                                //         console.log('----------------pair complete with continue visitor joined');
                                       
                                //         console.log(newStartTime+'<br>'+oldStartTime);
                                //         console.log(resultInMinutes); 
                                //         var total = resultInMinutes; 
                                //         //when visitor joined & visitor joined come at very next
                                //         oldStartTime = newStartTime
                                //         joined=0;
                                //     }
                                //     console.log('pair not complete');
                                // }
                                
                                // if(item.system == 'agent left' && total >= 0){
                                //     add_array.push(total);
                                //     sum += (total);
                                // }
                               
                                $('<tr>').append(
                                        $('<td>').text(item.logged_id),
                                        $('<td>').text(item.message),
                                        $('<td>').text(item.system),
                                        $('<td>').html(item.room_id),
                                        $('<td>').html(item.username),
                                        $('<td>').html(item.date_created),
                                        // $('<td>').html(total),
                                        ).appendTo('#users_table');
                            });
                            console.log(add_array);
                            console.log(sum);
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                },
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'copy', 'csv', 'excel', 'pdf', 'print'
                                    'copy','csv', 'excel'
                                ]
                            });
                        }
                        
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
    <?php
}
if ($basename == 'usershoursByrooms.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'usershoursByrooms'
                }
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);
                            
                            $.each(result, function (i, item) {
                                
                                $('<tr>').append(
                                        $('<td>').text(item.logged_id),
                                        $('<td>').html('<a href="usershoursByusers.php?roomId=' + item.room_id + '">'+item.room_id+'</a>'),
                                        // $('<td>').html(item.date_created),
                                        // $('<td>').html(total),
                                        ).appendTo('#users_table');
                            });
                            
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                },
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'copy', 'csv', 'excel', 'pdf', 'print'
                                    'copy','csv', 'excel'
                                ]
                            });
                        }
                        
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
    <?php
}

if ($basename == 'usershoursByusers.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'usershoursByusers',
                        'roomId': '<?php echo $_GET['roomId']; ?>'
                }
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);
                            
                            $.each(result, function (i, item) {
                                
                                $('<tr>').append(
                                        $('<td>').text(item.logged_id),
                                        // $('<td>').text(item.message),
                                        // $('<td>').text(item.system),
                                        $('<td>').html(item.room_id),
                                        $('<td>').html('<a href="usershoursByusersDetails.php?username=' + item.username + '&roomId=' + item.room_id + '">'+item.username+'</a>'),
                                        // $('<td>').html(item.date_created),
                                        // $('<td>').html(total),
                                        ).appendTo('#users_table');
                            });
                            
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                },
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'copy', 'csv', 'excel', 'pdf', 'print'
                                    'copy','csv', 'excel'
                                ]
                            });
                        }
                        
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
    <?php
}

if ($basename == 'usershoursByusersDetails.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'usershoursByusersDetails',
                        'username': '<?php echo $_GET['username']; ?>',
                        'roomId': '<?php echo $_GET['roomId']; ?>',
                }
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);
                            var join = 0;
                            var left = 0;
                            var joined = 0;
                            var oldStartTime = '';
                            var startTime = '';
                            var endTime = '';
                            var add = 0;
                            var add_array = [];
                            sum = 0;
                            $.each(result, function (i, item) {
                                // console.log('i = '+i);
                                
                                // if(join == 0 && item.system == "visitor joined"){
                                //     join=1;
                                //     oldStartTime = new Date(item.date_created);
                                // }else if(item.system == "visitor left"){
                                //     left=1;
                                // }
                                // if(join == 1 && left == 1 ){
                                //     console.log('pair complete');
                                //     endTime = new Date(item.date_created);
                                //     var difference = endTime.getTime() - oldStartTime.getTime(); // This will 
                                //     var resultInMinutes = Math.round(difference / 60000); 
                                //     console.log(endTime+'<br>'+oldStartTime);
                                //     console.log(resultInMinutes); 
                                //     var total =resultInMinutes;
                                //     join=0; left=0;
                                //     joined=0;
                                // }else{
                                //     joined++
                                //     console.log('joined='+joined);
                                //     console.log('join='+join);
                                //     if(joined == 2){
                                //         newStartTime = new Date(item.date_created);
                                //         // endTime = newStartTime;
                                //         var difference = newStartTime.getTime() - oldStartTime.getTime(); // This will 
                                        
                                //         var resultInMinutes = Math.round(difference / 60000);
                                //         console.log('----------------pair complete with continue visitor joined');
                                       
                                //         console.log(newStartTime+'<br>'+oldStartTime);
                                //         console.log(resultInMinutes); 
                                //         var total = resultInMinutes; 
                                //         //when visitor joined & visitor joined come at very next
                                //         oldStartTime = newStartTime
                                //         joined=0;
                                //     }
                                //     console.log('pair not complete');
                                // }
                                
                                // if(item.system == 'visitor left' && total >= 0){
                                //     add_array.push(total);
                                //     sum += (total);
                                // }
                               
                                $('<tr>').append(
                                        $('<td>').text(item.logged_id),
                                        $('<td>').text(item.message),
                                        $('<td>').text(item.system),
                                        $('<td>').html(item.room_id),
                                        $('<td>').html(item.username),
                                        $('<td>').html(item.date_created),
                                        // $('<td>').html(total),
                                        ).appendTo('#users_table');
                            });
                            
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                },
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'copy', 'csv', 'excel', 'pdf', 'print'
                                    'copy','csv', 'excel'
                                ]
                            });
                        }
                        
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
    <?php
}

if ($basename == 'usershoursByusersDetailsReport.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getUsersHoursByusersDetailsReport',
                        'username': '<?php echo $_GET['username']; ?>',
                }
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);
                            var join = 0;
                            var left = 0;
                            var joined = 0;
                            var oldStartTime = '';
                            var startTime = '';
                            var endTime = '';
                            var add = 0;
                            var add_array = [];
                            sum = 0;
                            $.each(result, function (i, item) {
                                console.log('i = '+i);
                                var yesNo = (item.is_blocked == "1") ? '<span data-localize="yes"></span>' : '<span data-localize="no"></span>';
                               
                               
                                $('<tr>').append(
                                        $('<td>').text(item.logged_id),
                                        $('<td>').html(item.room_id),
                                        $('<td>').html(item.username),
                                        $('<td>').html(item.room_count),
                                        ).appendTo('#users_table');
                            });
                            console.log(add_array);
                            console.log(sum);
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                },
                                dom: 'Bfrtip',
                                buttons: [
                                    'copy', 'csv', 'excel'
                                ]
                            });
                        }
                        
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
    <?php
}
if ($basename == 'recordings.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getrecordings',
                        'category': '<?php echo $_GET['category']; ?>'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        
                        $.each(result, function(i, item) {
                            // var deleteEditLink = '<a href="../server/recordings/' + item.filename + '" target="_blank" data-localize="view"></a> | <a href="javascript:void(0);" id="deleterecording' + item.recording_id + '" data-localize="delete"></a>';
                            var deleteEditLink = '<a href="videotracking.php?video='+item.filename+'" target="_blank" data-localize="view"></a>';
                            var count = i== 0 ? 1: i+1 ;
                            $('<tr>').append(
                                // $('<td>').html('<a href="../server/recordings/' + item.filename + '" target="_blank">' + item.filename + '</a>'),
                                $('<td>').text(count),
                                $('<td>').html('<a href="videotracking.php?video='+item.filename+'&video_id='+item.recording_id+'&username=<?php echo $_SESSION["username"]; ?>" target="_blank">' + item.filename + '</a>'),
                                $('<td>').text(item.room_id),
                                $('<td>').text(item.quiz_category),
                                $('<td>').text(item.date_created),
                                $('<td>').html(deleteEditLink)
                            ).appendTo('#recordings_table');
                            $('#deleterecording' + item.recording_id).on('click', function() {
                                deleteItem(item.recording_id, 'recording');
                            });
                        });
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#recordings_table').DataTable({
                            "order": [
                                [3, 'desc']
                            ],
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}
if ($basename == 'recordingsByCategory.php') {
    ?>
        <script>
            jQuery(document).ready(function($) {
    
                $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: {
                            'type': 'getRecordingsByCategory'
                        }
                    })
                    .done(function(data) {
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function(i, item) {
                                var deleteEditLink = '<a href="videotracking.php?video='+item.filename+'" target="_blank" data-localize="view"></a>';
                                var count = i== 0 ? 1: i+1 ;
                                $('<tr>').append(
                                    $('<td>').text(count),
                                    $('<td>').text(item.video_count),
                                    // $('<td>').text(item.agent_id),
                                    $('<td>').html('<a href="recordings.php?category='+item.quiz_category+'">' + item.quiz_category + '</a>'),
                                    
                                    
                                ).appendTo('#recordings_table');
                                $('#deleterecording' + item.recording_id).on('click', function() {
                                    deleteItem(item.recording_id, 'recording');
                                });
                            });
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                            $('#recordings_table').DataTable({
                                "order": [
                                    [3, 'desc']
                                ],
                                "language": {
                                    "url": "locales/table.json"
                                }
                            });
                        }
                    })
                    .fail(function(e) {
                        console.log(e);
                    });
            });
        </script>
    
    <?php
    }
if ($basename == 'feedbacks.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getFeedbacks'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        
                        $.each(result, function(i, item) {
                            $('<tr>').append(
                                $('<td>').text(item.feedback_id),
                                $('<td>').text(item.user_id),
                                $('<td>').text(item.room_id),
                                $('<td>').text(item.username),
                                $('<td>').text(item.agent_email),
                                $('<td>').text(item.text),
                                $('<td>').text(item.rate),
                                $('<td>').text(item.date_added),
                            ).appendTo('#feedback_table');
                            $('#deletefeedbacks' + item.feedback_id).on('click', function() {
                                deleteItem(item.recording_id, 'feedbacks');
                            });
                        });
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#feedback_table').DataTable({
                            "order": [
                                [0, 'desc']
                            ],
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}

if ($basename == 'videosTrackingReport.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getVideosTracking',
                        'username' : '<?php echo $_GET['username']; ?>'
                }
            })
                    .done(function (data) {
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {
                                var deleteEditLink = '<a href="../server/recordings/' + item.filename + '" target="_blank" data-localize="view"></a> | <a href="javascript:void(0);" id="deleterecording' + item.recording_id + '" data-localize="delete"></a>';
                                $('<tr>').append(
                                        $('<td>').html('<a href="videotracking.php?video='+item.filename+'&video_id='+item.recording_id+'&username=<?php echo $_SESSION["username"]; ?>" target="_blank">' + item.filename + '</a>'),
                                        $('<td>').text(item.video_duration),
                                        $('<td>').text((item.total_watched_time).replace('.000000', ' ')),
                                        $('<td>').text(item.username),
                                        $('<td>').html(item.timestamp)
                                        ).appendTo('#recordings_table');
                                $('#deleterecording' + item.recording_id).on('click', function () {
                                    deleteItem(item.recording_id, 'recording');
                                });
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#recordings_table').DataTable({
                                "order": [[4, 'desc']],
                                "language": {
                                    "url": "locales/table.json"
                                },
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'copy', 'csv', 'excel', 'pdf', 'print'
                                    'copy','csv', 'excel'
                                ]
                            });
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>

    <?php
}
if ($basename == 'videosTrackingReportByroom.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getVideosTrackingByroom'}
            })
                    .done(function (data) {
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {
                                var deleteEditLink = '<a href="../server/recordings/' + item.filename + '" target="_blank" data-localize="view"></a> | <a href="javascript:void(0);" id="deleterecording' + item.recording_id + '" data-localize="delete"></a>';
                                $('<tr>').append(
                                        $('<td>').html('<a href="videosTrackingReportByusers.php?room='+item.room_id+'">' + item.room_id + '</a>'),
                                        $('<td>').text(item.username),
                                        // $('<td>').html(item.timestamp)
                                        ).appendTo('#recordings_table');
                                $('#deleterecording' + item.recording_id).on('click', function () {
                                    deleteItem(item.recording_id, 'recording');
                                });
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#recordings_table').DataTable({
                                "order": [[0, 'desc']],
                                "language": {
                                    "url": "locales/table.json"
                                },
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'copy', 'csv', 'excel', 'pdf', 'print'
                                    'copy','csv', 'excel'
                                ]
                            });
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>

    <?php
}
if ($basename == 'videosTrackingReportByusers.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getVideosTrackingByusers',
                        'room_id': '<?php echo $_GET['room']?>'
                }
            })
                    .done(function (data) {
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {
                                
                                $('<tr>').append(
                                    $('<td>').html('<a href="videosTrackingReport.php?username='+item.username+'">' + item.username + '</a>'),
                                    $('<td>').text(item.room_id),
                                    // $('<td>').html(item.timestamp)
                                    ).appendTo('#recordings_table');
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#recordings_table').DataTable({
                                "order": [[0, 'desc']],
                                "language": {
                                    "url": "locales/table.json"
                                },
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'copy', 'csv', 'excel', 'pdf', 'print'
                                    'copy','csv', 'excel'
                                ]
                            });
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>

    <?php
}
if ($basename == 'upload.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getUpload','username': "<?php echo $_SESSION["username"]; ?>"}
            })
                    .done(function (data) {
                        // console.log(data)
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {
                                var deleteEditLink = '<a href="../server/uploads/' + item.filename + '" download data-localize="download">Download</a> | <a href="javascript:void(0);" id="deleteuploads' + item.id + '" data-localize="delete"></a>';
                                $('<tr>').append(
                                        $('<td>').html('<a href="../server/uploads/' + item.filename + '" target="_blank">' + item.filename + '</a>'),
                                        $('<td>').text(item.room_id),
                                        // $('<td>').text((item.agent_id == null)?'N/A':item.agent_id),
                                        // $('<td>').text((item.user_id == null)?'N/A':item.user_id),
                                        $('<td>').text(item.upload_type),
                                        $('<td>').text(item.email),
                                        $('<td>').text(item.date_created),
                                        $('<td>').html(deleteEditLink)
                                        ).appendTo('#uploads_table');
                                $('#deleteuploads' + item.id).on('click', function () {
                                    deleteItem(item.id, 'uploads');
                                });
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#uploads_table').DataTable({
                                "order": [[4, 'desc']],
                                "language": {
                                    "url": "locales/table.json"
                                }
                            });
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>

    <?php
}
if ($basename == 'uploads.php') {
?>
    <script>
        jQuery(document).ready(function($) {
        <?php if ($_SESSION["tenant"] == 'lsv_mastertenant')
            {
        ?>    
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getUploads'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $.each(result, function(i, item) {
                            var deleteEditLink = '<a href="../server/uploads/' + item.filename + '" target="_blank">Download</a> | <a href="javascript:void(0);" id="deleteuploads' + item.id + '" data-localize="delete"></a>';
                            $('<tr>').append(
                                $('<td>').html('<a href="../server/uploads/' + item.filename + '" target="_blank">' + item.filename + '</a>'),
                                $('<td>').text(item.room_id),
                                // $('<td>').text((item.agent_id == null) ? 'N/A' : item.agent_id),
                                // $('<td>').text((item.user_id == null) ? 'N/A' : item.user_id),
                                $('<td>').text((item.agent_id == null)?'N/A':item.first_name),
                                $('<td>').text((item.user_id == null)?'N/A':item.name),
                                $('<td>').text(item.upload_type),
                                $('<td>').text(item.email),
                                $('<td>').text(item.date_created),
                                $('<td>').html(deleteEditLink)
                            ).appendTo('#uploads_table');
                            $('#deleteuploads' + item.id).on('click', function() {
                                deleteItem(item.id, 'uploads');
                            });
                        });
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#uploads_table').DataTable({
                            "order": [
                                [7, 'desc']
                            ],
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        <?php 
            }
        ?>    
        });
    </script>

<?php
}

if ($basename == 'rooms.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getrooms',
                        'agentId': agentId
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        var getCurrentDateFormatted = function(date) {
                            var currentdate = new Date(date);
                            if (currentdate.getDate()) {
                                return ('0' + currentdate.getDate()).slice(-2) + "/" +
                                    ('0' + (currentdate.getMonth() + 1)).slice(-2) + "/" +
                                    currentdate.getFullYear() + " " +
                                    ('0' + currentdate.getHours()).slice(-2) + '.' + ('0' + currentdate.getMinutes()).slice(-2);
                            } else {
                                return '';
                            }
                        };
                        $.each(result, function(i, item) {
                            var datetimest = '';
                            
                            if (item.datetime) {
                                datetimest = getCurrentDateFormatted(item.datetime) + ' / ' + item.duration;
                            }
                            var quiz_categories = (item.quiz_categories != "None" && item.quiz_categories != null ) ? (item.quiz_categories)  : 'N/A';
                            var onlinevisitors = (item.parent_id != "0") ? (item.numberof_visitors == "0") ? '<span style="color:red;" data-localize="Parent">'+item.numberof_visitors+'</span>' : '<span style="color:green;" data-localize="Child">'+item.numberof_visitors+'</span>' : "N/A";
                            var isActive = (item.is_active == "1") ? '<span data-localize="yes">Yes</span>' : '<span data-localize="no">No</span>';
                            var role = (item.parent_id == "0") ? '<span data-localize="Parent">Parent</span>' : '<span data-localize="Child">Child</span>';
                            $('<tr>').append(
                                $('<td id="roomid_' + item.roomId + '">').text(item.roomId),
                                $('<td>').text(item.agent),
                                $('<td>').text(item.visitor),
                                $('<td>').text(item.visitors_limit),
                                $('<td>').html(onlinevisitors),
                                $('<td>').html(quiz_categories),
                                $('<td>').html(item.minimum_time_of_quiz),
                                $('<td>').html(item.minimum_time_for_video),
                                $('<td>').html(item.credits_for_room),
                                $('<td>').html('<a target="_blank" title="Conference agent URL" href="' + item.agenturl + '"><?php echo $actual_link; ?>' + item.shortagenturl + '</a><br/><a title="Broadcast agent URL" target="_blank" href="' + item.agenturl_broadcast + '"><?php echo $actual_link; ?>' + item.shortagenturl_broadcast + '</a>'),
                                $('<td>').html('<a target="_blank" title="Conference visitor URL" href="' + item.visitorurl + '"><?php echo $actual_link; ?>' + item.shortvisitorurl + '</a><br/><a title="Broadcast visitor URL" target="_blank" href="' + item.visitorurl_broadcast + '"><?php echo $actual_link; ?>' + item.shortvisitorurl_broadcast + '</a>'),
                                $('<td>').text(datetimest),
                                $('<td>').html(isActive),
                                $('<td>').html(role),
                                $('<td>').html('<a href="room.php?id=' + item.room_id + '" data-localize="edit"></a> | <a href="javacript:void(0);" id="deleteroom' + item.room_id + '" data-localize="delete"></a>')
                            ).appendTo('#rooms_table');
                            $('#deleteroom' + item.room_id).on('click', function() {
                                deleteItem(item.room_id, 'room');
                            });
                        });
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#rooms_table').DataTable({
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function() {
                    console.log(false);
                });
        });
    </script>

<?php
}
if ($basename == 'roomsforquiz.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getrooms',
                        'agentId': agentId
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        var getCurrentDateFormatted = function(date) {
                            var currentdate = new Date(date);
                            if (currentdate.getDate()) {
                                return ('0' + currentdate.getDate()).slice(-2) + "/" +
                                    ('0' + (currentdate.getMonth() + 1)).slice(-2) + "/" +
                                    currentdate.getFullYear() + " " +
                                    ('0' + currentdate.getHours()).slice(-2) + '.' + ('0' + currentdate.getMinutes()).slice(-2);
                            } else {
                                return '';
                            }
                        };
                        $.each(result, function(i, item) {
                            var datetimest = '';
                            
                            if (item.datetime) {
                                datetimest = getCurrentDateFormatted(item.datetime) + ' / ' + item.duration;
                            }
                            var quiz_categories = (item.quiz_categories != "None" && item.quiz_categories != null ) ? (item.quiz_categories)  : 'N/A';
                            
                            var isActive = (item.is_active == "1") ? '<span data-localize="yes">Yes</span>' : '<span data-localize="no">No</span>';
                            var role = (item.parent_id == "0") ? '<span data-localize="Parent">Parent</span>' : '<span data-localize="Child">Child</span>';
                            $('<tr>').append(
                                $('<td id="roomid_' + item.roomId + '">').text(item.roomId),
                                $('<td>').html(isActive),
                                $('<td>').html(role),
                                $('<td>').html(quiz_categories),
                                $('<td>').html('<a  target="blank" href="assignedquiz.php?room='+item.roomId+'">View</a>'),
                                // $('<td>').html('<a href="room.php?id=' + item.room_id + '" data-localize="edit"></a> | <a href="javacript:void(0);" id="deleteroom' + item.room_id + '" data-localize="delete"></a>')
                            ).appendTo('#rooms_table');
                            $('#deleteroom' + item.room_id).on('click', function() {
                                deleteItem(item.room_id, 'room');
                            });
                        });
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#rooms_table').DataTable({
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function() {
                    console.log(false);
                });
        });
    </script>

<?php
}
if ($basename == 'subrooms.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            var currdatetime =  new Date().toISOString() 
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getSubRooms', 'agentId': agentId, 'currentdatetime':currdatetime}
            })
                    .done(function (data) {
                        if (data) {
                            var result = JSON.parse(data);
                            var getCurrentDateFormatted = function (date) {
                                var currentdate = new Date(date);
                                if (currentdate.getDate()) {
                                    return ('0' + currentdate.getDate()).slice(-2) + "/"
                                            + ('0' + (currentdate.getMonth() + 1)).slice(-2) + "/"
                                            + currentdate.getFullYear() + " "
                                            + ('0' + currentdate.getHours()).slice(-2) + '.' + ('0' + currentdate.getMinutes()).slice(-2);
                                } else {
                                    return '';
                                }
                            };
                            $.each(result, function (i, item) {
                                var datetimest = '';
                                if (item.datetime) {
                                    datetimest = getCurrentDateFormatted(item.datetime) + ' / ' + item.duration;
                                }
                                var isActive = (item.is_active == "1") ? '<span data-localize="yes">Yes</span>' : '<span data-localize="no">No</span>';
                                $('<tr>').append(
                                        $('<td id="roomid_' + item.roomId + '">').text(item.roomId),
                                        $('<td>').text(item.agent),
                                        // $('<td>').text(item.visitor),
                                        // $('<td>').html('<a target="_blank" title="Conference agent URL" href="' + item.agenturl + '"><?php echo $actual_link; ?>' + item.shortagenturl + '</a><br/><a title="Broadcast agent URL" target="_blank" href="' + item.agenturl_broadcast + '"><?php //echo $actual_link; ?>' + item.shortagenturl_broadcast + '</a>'),
                                        // $('<td>').html('<a target="_blank" title="Conference visitor URL" href="' + item.visitorurl + '"><?php echo $actual_link; ?>' + item.shortvisitorurl + '</a><br/><a title="Broadcast visitor URL" target="_blank" href="' + item.visitorurl_broadcast + '"><?php //echo $actual_link; ?>' + item.shortvisitorurl_broadcast + '</a>'),
                                        $('<td>').html('<a title="Broadcast visitor URL" target="_blank" href="' + item.visitorurl_broadcast + '"><?php echo $actual_link; ?>' + item.shortvisitorurl_broadcast + '</a>'),
                                        $('<td>').text(datetimest),
                                        $('<td>').html(isActive),
                                        // $('<td>').html('<a href="room.php?id=' + item.room_id + '" data-localize="edit"></a> | <a href="javacript:void(0);" id="deleteroom' + item.room_id + '" data-localize="delete"></a>')
                                        ).appendTo('#rooms_table');
                                $('#deleteroom' + item.room_id).on('click', function () {
                                    deleteItem(item.room_id, 'room');
                                });
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#rooms_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                }
                            });
                        }
                    })
                    .fail(function () {
                        console.log(false);
                    });
        });</script>

    <?php
}
if ($basename == 'visitors.php') {
?>
    <script>
        jQuery(document).ready(function($) {
            setTimeout(function() {
                svConfigs.agentName = '<?php echo @$_SESSION["agent"]["first_name"] . ' ' . @$_SESSION["agent"]["last_name"]; ?>';
            }, 3000);
        });
    </script>

<?php
}
if ($basename == 'chats.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getchats',
                        'agentId': agentId
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $.each(result, function(i, item) {
                            var openModal = '<div class="modal fade" id="ex' + item.room_id + '" tabindex="-1" role="dialog" aria-labelledby="ex' + item.room_id + '" aria-hidden="true"><div class="modal-dialog modal-lgr" role="document"><button type="button" data-toggle="modal" class="closeDocumentModal" data-target="#ex' + item.room_id + '" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="fa fa-times"></span></button><div class="modal-content">' + item.messages + '</div>     </div> </div><a href="" class="btn fa fa-comments" data-toggle="modal" data-target="#ex' + item.room_id + '"></a>';
                            $('<tr>').append(
                                $('<td>').text(item.date_created),
                                $('<td>').text(item.room_id),
                                $('<td>').html(openModal),
                                $('<td>').text(item.agent),
                            ).appendTo('#chats_table');
                        });
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#chats_table').DataTable({
                            "pagingType": "full_numbers",
                            "order": [
                                [0, 'desc']
                            ],
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}
if ($basename == 'quiz.php') {
?>
    <script>
      jQuery(document).ready(function($) {
        $('#submitbutton').click(function(event) {
            
                 //validation for blanks
                var a = document.forms["QuizForm"]["question"].value;
                var b = document.forms["QuizForm"]["correct_answer"].value;
                var c = document.forms["QuizForm"]["wrong_answer1"].value;
                var d = document.forms["QuizForm"]["wrong_answer2"].value;
                var e = document.forms["QuizForm"]["wrong_answer3"].value;
                
                if (
                    a == null || a == "" || 
                    b == null || b == "" || 
                    c == null || c == "" ||
                    d == null || d == "" ||
                    e == null || e == "" 
                
                    ) {
                        alert("Please Fill All Required Field with red * marks");
                        return false;
                }

            event.preventDefault();
            <?php
                if (isset($_GET['id'])) {
            ?>
            $("#error").hide();
            //  alert('update');
             var dataObj = {
                        'type': 'editQuiz',
                        'id': '<?php echo $_GET['id']; ?>',
                        'category': $('#category').val(),
                        'status': $('#status').val(),
                        'question': $('#question').val(),
                        'correct_answer': $('#correct_answer').val(),
                        'wrong_answer1': $('#wrong_answer1').val(),
                        'wrong_answer2': $('#wrong_answer2').val(),
                        'wrong_answer3': $('#wrong_answer3').val()
                    };      
            <?php
                } else {
            ?>
            var dataObj = {
                        'type': 'addQuiz',
                        'category': $('#category').val(),
                        'status': $('#status').val(),
                        'question': $('#question').val(),
                        'correct_answer': $('#correct_answer').val(),
                        'wrong_answer1': $('#wrong_answer1').val(),
                        'wrong_answer2': $('#wrong_answer2').val(),
                        'wrong_answer3': $('#wrong_answer3').val()
                    };  
            <?php
                } 
            ?>        
            $.ajax({
                url: "../server/script.php",
                type: "POST",
                data: dataObj,
                success: function(data) {
                    if (data) {
                        console.log(data);
                        window.location.href = "quizindex.php?category="+$('#category').val()
                    } else {
                        $("#error").show();
                        $("#error").html("Invalid Data");
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        });
        //get update section values
        <?php
        if (isset($_GET['id'])) {
        ?>
        
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getQuizById',
                        'id': <?php echo (int) @$_GET['id'] ?>
                    }
                })
                .done(function(data) {
                    if (data) {
                        
                        data = JSON.parse(data);
                        console.log(data);
                        $('#category').val(data.category);
                        $('#status').val(data.status);

                        // $('#status').html(data.status == 0 ? '<option value="0" selected>Inactive</option><option value="1">Active</option>' : '<option value="1" selected>Active</option><option value="0">Inactive</option>')
                        $('#question').val(data.que);
                        $('#correct_answer').val(data.ans);
                        $('#wrong_answer1').val(data.option1);
                        $('#wrong_answer2').val(data.option2);
                        $('#wrong_answer3').val(data.option3);
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        // $('[data-localize]').localize('dashboard', opts);
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        <?php
        }
        ?>
    });
    </script>
<?php    
}
if ($basename == 'quizindexcategwise.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getQuizCategoryWise'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $.each(result, function(i, item) {
                            var deleteEditLink = '<a href="javascript:void(0);" id="deleteQuiz' + item.id + '"data-localize="delete">Delete</a>';
                            $('<tr>').append(
                                $('<td>').text(i + 1),
                                $('<td>').text(item.category),
                                $('<td>').text(item.question_count),
                                // $('<td>').text(item.que),
                                // $('<td>').text(item.option1),
                                // $('<td>').text(item.option2),
                                // $('<td>').text(item.option3),
                                // $('<td>').text(item.option4),
                                // $('<td>').text(item.ans),
                                // $('<td>').html(deleteEditLink),
                                $('<td>').html('<a  target="blank" href="quizindex.php?category='+item.category+'">View</a>'),
                            ).appendTo('#chats_table');
                            $('#deleteQuiz' + item.id).on('click', function() {
                                deleteItem(item.id, 'quiz');
                            });
                        });

                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#chats_table').DataTable({
                            "pagingType": "full_numbers",
                            // "order": [[0, 'desc']],
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>
<?php
}
if ($basename == 'quizindex.php') {
?>
    <script>
    <?php
        if (isset($_GET['category'])) {
    ?>
        jQuery(document).ready(function($) {
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getQuiz', 'category':'<?php echo $_GET['category'];?>'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $.each(result, function(i, item) {
                            var deleteEditLink = '<a href="quiz.php?id=' + item.id + '" data-localize="edit"></a> | <a href="javascript:void(0);" id="deleteQuiz' + item.id + '"data-localize="delete">Delete</a>';
                            (item.status == 1) ? item.status = $('<td style="background-color: #baf1ba;">').text('Active'): item.status = $('<td style="background-color: #fde7e5;">').text('In-active');
                            $('<tr>').append(
                                $('<td>').text(i + 1),
                                $('<td>').text(item.category),
                                $('<td>').text(item.que),
                                $('<td>').text(item.option1),
                                $('<td>').text(item.option2),
                                $('<td>').text(item.option3),
                                $('<td>').text(item.option4),
                                $('<td>').text(item.ans),
                                // $('<td>').text(item.userans),
                                item.status,
                                $('<td>').html(deleteEditLink),
                            ).appendTo('#chats_table');
                            $('#deleteQuiz' + item.id).on('click', function() {
                                deleteItem(item.id, 'quiz');
                            });
                        });

                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#chats_table').DataTable({
                            "pagingType": "full_numbers",
                            // "order": [[0, 'desc']],
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    <?php
        }   
    ?>    
    </script>

<?php
}
if ($basename == 'essaytypequizindex.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getEssayTypeQuiz'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $.each(result, function(i, item) {
                            var deleteEditLink = '<a href="javascript:void(0);" id="deleteEssayTypeQuiz' + item.quiz_essay_id + '"data-localize="delete">Delete</a>';
                            var status = (item.status == 1) ? 'Active': 'Inactive';
                            $('<tr>').append(
                                $('<td>').text(i + 1),
                                $('<td>').text(item.category),
                                $('<td>').text(item.question_name),
                                $('<td>').text(status),
                                // $('<td>').text(item.status),
                                $('<td>').text(item.default_marks),
                                $('<td>').text(item.gernal_feedback),
                                $('<td>').html('<a href="essaytypequiz.php?quiz_essay_id=' + item.quiz_essay_id + '" data-localize="edit"></a> | <a href="javascript:void(0);" id="deleteEssayTypeQuiz' + item.quiz_essay_id + '" data-localize="delete"></a>'),
                                // $('<td>').html(deleteEditLink),
                            ).appendTo('#chats_table');
                            $('#deleteEssayTypeQuiz' + item.quiz_essay_id).on('click', function() {
                                deleteItem(item.quiz_essay_id, 'deleteEssayTypeQuiz');
                            });
                        });

                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#chats_table').DataTable({
                            "pagingType": "full_numbers",
                            // "order": [[0, 'ASC']],
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}
if ($basename == 'essaytypequizsusers.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getEssayTypeQuizUsers'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        console.log(result);
                        $.each(result, function(i, item) {
                            // if agent id is blank means only users allowed here -----
                            if(item.agent_id == ''){
                                var deleteEditLink = '<a href="javascript:void(0);" id="deleteEssayTypeQuiz' + item.quiz_essay_id + '"data-localize="delete">Delete</a>';
                                (item.status == 1) ? item.status = 'Active': item.status = 'Inactive';
                                $('<tr>').append(
                                    $('<td>').text(i + 1),
                                    $('<td>').text(item.username),
                                    $('<td>').text(item.ques_count),
                                    $('<td>').text(item.pending_marks),
                                    $('<td>').text(item.timestamp),
                                    // $('<td>').html('<a href="essaytypequiz.php?quiz_essay_id=' + item.quiz_essay_id + '" data-localize="edit"></a> | <a href="javascript:void(0);" id="deleteEssayTypeQuiz' + item.quiz_essay_id + '" data-localize="delete"></a>'),
                                    $('<td>').html('<a href="essaytypequizsuser-answer.php?user_id=' + item.user_id + '" data-localize="view"></a>'),
                                    // $('<td>').html(deleteEditLink),
                                ).appendTo('#chats_table');
                                $('#deleteEssayTypeQuiz' + item.quiz_essay_id).on('click', function() {
                                    deleteItem(item.quiz_essay_id, 'deleteEssayTypeQuiz');
                                });
                            }    
                        });

                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#chats_table').DataTable({
                            "pagingType": "full_numbers",
                            // "order": [[0, 'ASC']],
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}
if ($basename == 'essaytypequizsuser-answer.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getEssayTypeQuizUsersAnswer',
                        'user_id': '<?php echo $_GET['user_id'] ?>'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        console.log(result);
                        $.each(result, function(i, item) {
                            // if agent id is blank means only users allowed here -----
                            if(item.agent_id == ''){
                                var deleteEditLink = '<a href="javascript:void(0);" id="deleteEssayTypeQuiz' + item.quiz_essay_id + '"data-localize="delete">Delete</a>';
                                (item.status == 1) ? item.status = 'Active': item.status = 'Inactive';
                                $('<tr>').append(
                                    $('<td>').text(i + 1),
                                    $('<td>').text(item.question_name),
                                    $('<td>').text(item.default_marks),
                                    $('<td>').text(item.manual_marks),
                                    $('<td>').text(item.timestamp),
                                    $('<td>').html('<a href="essaytypequizanswer.php?quiz_essay_id=' + item.quiz_essay_id + '&user_id='+ item.user_id +'" data-localize="edit"></a>'),
                                ).appendTo('#chats_table');
                                $('#deleteEssayTypeQuiz' + item.quiz_essay_id).on('click', function() {
                                    deleteItem(item.quiz_essay_id, 'deleteEssayTypeQuiz');
                                });
                            }    
                        });

                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#chats_table').DataTable({
                            "pagingType": "full_numbers",
                            // "order": [[0, 'ASC']],
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}
if ($basename == 'essaytypequiz.php') {
?>
    <script>
        jQuery(document).ready(function($) {
            // $('#error').hide();
            $('#submitbutton').click(function(event) {
                var a = document.forms["Form"]["question_name"].value;
                var b = document.forms["Form"]["question_text"].value;
                var c = document.forms["Form"]["default_marks"].value;
                if (a == null || a == "", b == null || b == "", c == null || c == "") {
                    alert("Please Fill All Required Field");
                    return false;
                }
               
                $('.required_error').on("input",function (){
                if ($('.required_error').val() == " ")   
                        alert('asd');
                });
                <?php
                    if (isset($_GET['quiz_essay_id'])) {
                ?>
                    // alert('update');        
                    var dataObj = {
                        'type': 'editEssayTypeQuiz',
                        'quiz_essay_id': <?php echo $_GET['quiz_essay_id']; ?>,
                        'category': $('#category').val(),
                        'question_name': $('#question_name').val(),
                        'question_text': $('#question_text').val(),
                        'default_marks': $('#default_marks').val(),
                        'gernal_feedback': $('#gernal_feedback').val(),
                        'status': $('#status').val()
                    };
                <?php
                } else {
                ?>
                    // alert('add');
                    var dataObj = {
                        'type': 'addEssayTypeQuiz',
                        'category': $('#category').val(),
                        'question_name': $('#question_name').val(),
                        'question_text': $('#question_text').val(),
                        'default_marks': $('#default_marks').val(),
                        'gernal_feedback': $('#gernal_feedback').val(),
                        'status': $('#status').val()
                    };
                <?php
                }
                ?>
                $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: dataObj
                    })
                    .done(function(data) {
                        if (data) {
                            // console.log(data);
                            location.href = 'essaytypequizindex.php';
                        } else {
                            alert("error");
                            $('#error').show();
                            $('#error').html('<span data-localize="error_user_save"></span>');
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function() {});
            });
            <?php
            if (isset($_GET['quiz_essay_id'])) {
            ?>
                $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: {
                            'type': 'getEssayTypeQuizById',
                            'quiz_essay_id': <?php echo (int) @$_GET['quiz_essay_id'] ?>
                        }
                    })
                    .done(function(data) {
                        if (data) {
                            data = JSON.parse(data);
                            $('#categoryValue').val(data.category);
                            // $('#category').html('<option value="'+data.category+'"selected>'+data.category+'</option>');
                            $('#question_name').val(data.question_name);
                            $('#question_text').val(data.question_text);
                            $('#default_marks').val(data.default_marks);
                            $('#gernal_feedback').val(data.gernal_feedback);
                            $('#status').html(data.status == 0 ? '<option value="0" selected>Inactive</option><option value="1">Active</option>' : '<option value="1" selected>Active</option><option value="0">Inactive</option>')
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function(e) {
                        console.log(e);
                    });
                //update section category ajax--------          
                var dataObj = {
                    'type': 'getCategorydropdown'
                };
                $.ajax({
                    url: "../server/script.php",
                    type: "POST",
                    data: dataObj,
                    success: function(data) {
                        var result = JSON.parse(data);
                        if (result) {
                            $.each(result, function(i, item) {
                                if (item.name == $('#categoryValue').val()) {
                                    $('#category').append(
                                        '<option value="' + item.name + '" selected>' + item.name + '</option>',
                                    );
                                } else {
                                    $('#category').append(
                                        '<option value="' + item.name + '">' + item.name + '</option>',
                                    );
                                }
                            });
                        } else {
                            $('#select_category').append(
                                $('#category').html('<option value=""></option>'),
                            );
                        }
                    }
                })
            <?php
            }
            ?>
        });
    </script>

<?php
}
if ($basename == 'essaytypequizanswer.php') {
    ?>
    <script>
        jQuery(document).ready(function($) {
            $('#error').hide();
            $('#submitbutton').click(function(event) {
                <?php
                    if (isset($_GET['quiz_essay_id'])) {
                ?>
                    // alert('update');        
                    var dataObj = {
                        'type': 'editEssayTypeQuizAnswer',
                        'quiz_essay_id': '<?php echo $_GET['quiz_essay_id']; ?>',
                        'agent_id': '<?php echo $_SESSION['agent']['agent_id']; ?>',
                        'manual_marks': $('#manual_marks').val(),
                        'user_id': '<?php echo $_GET['user_id']; ?>'
                    };
                <?php
                } else {
                ?>
                    
                <?php
                }
                ?>
                $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: dataObj
                    })
                    .done(function(data) {
                        if (data) {
                            location.href = 'essaytypequizsuser-answer.php?user_id=<?php echo $_GET['user_id']; ?>';
                        } else {
                            alert("error");
                            $('#error').show();
                            $('#error').html('<span data-localize="error_user_save"></span>');
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function() {});
            });

            <?php
            if (isset($_GET['quiz_essay_id'])) {
            ?>
                $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: {
                            'type': 'getEssayTypeQuizAnswerById',
                            'quiz_essay_id': '<?php echo (int) @$_GET['quiz_essay_id'] ?>',
                            'agent_id':'<?php echo $_SESSION['agent']['agent_id']; ?>', 
                            'user_id': '<?php echo $_GET['user_id']; ?>'
                            
                        }
                    })
                    .done(function(data) {
                        if (data) {
                            data = JSON.parse(data);
                            $('#categoryValue').val(data.category);
                            // $('#category').html('<option value="'+data.category+'"selected>'+data.category+'</option>');
                            $('#question_name').val(data.question_name);
                            $('#question_text').val(data.question_text);
                            $('#quiz_answer').val(data.quiz_answer);
                            $('#default_marks').val(data.default_marks);
                            $('#manual_marks').val(data.manual_marks);
                            $('#gernal_feedback').val(data.gernal_feedback);
                            $('#status').html(data.status == 0 ? '<option value="0" selected>Inactive</option><option value="1">Active</option>' : '<option value="1" selected>Active</option><option value="0">Inactive</option>')
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function(e) {
                        console.log(e);
                    });
                //update section category ajax--------          
                var dataObj = {
                    'type': 'getCategorydropdown'
                };
                $.ajax({
                    url: "../server/script.php",
                    type: "POST",
                    data: dataObj,
                    success: function(data) {
                        var result = JSON.parse(data);
                        if (result) {
                            $.each(result, function(i, item) {
                                if (item.name == $('#categoryValue').val()) {
                                    $('#category').append(
                                        '<option value="' + item.name + '" selected>' + item.name + '</option>',
                                    );
                                } else {
                                    // $('#category').append(
                                    //     '<option value="' + item.name + '">' + item.name + '</option>',
                                    // );
                                }
                            });
                        } else {
                            $('#select_category').append(
                                $('#category').html('<option value=""></option>'),
                            );
                        }
                    }
                })
            <?php
            }
            ?>
        });
    </script>

<?php
}
if ($basename == 'categoryindex.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getCategory'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $.each(result, function(i, item) {
                            var deleteEditLink = '<a href="category.php?id=' + item.id + '" data-localize="edit"></a> | <a href="javascript:void(0);" id="deleteCategory' + item.id + '"data-localize="delete"></a>';
                            $('<tr>').append(
                                $('<td>').text(i + 1),
                                $('<td>').text(item.name),
                                $('<td>').text(item.minimum_score),
                                $('<td>').text(item.timestamp),
                                $('<td>').html(deleteEditLink),
                            ).appendTo('#chats_table');
                            $('#deleteCategory' + item.id).on('click', function() {
                                deleteItem(item.id, 'category');
                            });
                        });
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#chats_table').DataTable({
                            "pagingType": "full_numbers",
                            "order": [
                                [0, 'desc']
                            ],
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}
if ($basename == 'settingindex.php') {
    ?>
        <script>
            jQuery(document).ready(function($) {
    
                $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: {
                            'type': 'getSetting'
                        }
                    })
                    .done(function(data) {
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function(i, item) {
                                var deleteEditLink = '<a href="setting.php?id=' + item.id + '" data-localize="edit"></a>';  
                                // <a href="javascript:void(0);" id="deleteSetting' + item.id + '"data-localize="delete"></a>';
                                $('<tr>').append(
                                    $('<td>').text(i + 1),
                                    $('<td>').text(item.subject),
                                    $('<td>').text(item.description),
                                    $('<td>').text(item.key_name),
                                    $('<td>').text(item.key_value),
                                    $('<td>').text(item.timestamp),
                                    $('<td>').html(deleteEditLink),
                                ).appendTo('#chats_table');
                                // $('#deleteSetting' + item.id).on('click', function() {deleteItem(item.id, 'setting'); });
                            });
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                            $('#chats_table').DataTable({
                                "pagingType": "full_numbers",
                                "order": [
                                    [0, 'desc']
                                ],
                                "language": {
                                    "url": "locales/table.json"
                                }
                            });
                        }
                    })
                    .fail(function(e) {
                        console.log(e);
                    });
            });
        </script>
    
<?php
}
if ($basename == 'quizstart.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getQuiz',
                        'category': '<?php echo $_GET['category'];?>'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $.each(result, function(i, item) {
                            i++;
                            $('<tr>').append(

                                $('<td>').html('<h3><br>' + i + ' ' + item.que + '</h3>' +
                                    '&nbsp;&nbsp;&nbsp;&nbsp;a )&nbsp;&nbsp;&nbsp;<input required type="radio" name ="' + item.id + '" value="' + item.option1 + '">&nbsp;' + item.option1 + '<br>' +
                                    '&nbsp;&nbsp;&nbsp;&nbsp;b )&nbsp;&nbsp;&nbsp;<input required type="radio" name ="' + item.id + '" value="' + item.option2 + '">&nbsp;' + item.option2 + '<br>' +
                                    '&nbsp;&nbsp;&nbsp;&nbsp;c )&nbsp;&nbsp;&nbsp;<input required type="radio" name ="' + item.id + '" value="' + item.option3 + '">&nbsp;' + item.option3 + '<br>' +
                                    '&nbsp;&nbsp;&nbsp;&nbsp;d )&nbsp;&nbsp;&nbsp;<input required type="radio" name ="' + item.id + '" value="' + item.option4 + '">&nbsp;' + item.option4 + '<br>'
                                ),
                                // $('<tr>').html('<input type="radio" value="'+item.option1+'">'+item.option1),

                            ).appendTo('#chats_table');
                        });
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#chats_table').DataTable({
                            "pagingType": "full_numbers",
                            "order": [
                                [0, 'desc']
                            ],
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}
if ($basename == 'essayquizstart.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getEssayQuiz',
                        'category': '<?php echo $_GET['category'];?>'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $.each(result, function(i, item) {
                            i++;
                            $('<tr>').append(
                                $('<td>').html('<h3>' + i + ' ' + item.question_name + '</h3>' +
                                    '<div class="form-group"><label for="Feedback or Hint">Gernal Feedback or Hint</label><p style="background-color: lightblue;" class="form-control col-sm-8">'+item.gernal_feedback+'</p></div>'+
                                    '<div class="form-group"><label for="question_text">Writte Answer Here</label><textarea class="form-control" id="question_texts'+item.quiz_essay_id+'" name="'+item.quiz_essay_id+'" placeholder="Enter Answer here" Required></textarea></div>' 
                                ),
                                
                            ).appendTo('#chats_table');
                           
                        });
                        console.log(form);
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                       
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}
if ($basename == 'essayquizresult.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'addEssayQuizAnswer',
                        'category': '<?php echo $_POST['category'];?>',
                        'data': '<?php echo json_encode($_POST['answer_data']); ?>',
                        'agent_id':'<?php $agent=isset($_SESSION['agent']['agent_id'])?$_SESSION['agent']['agent_id'] : null ;  echo $agent;?>',
                        'user_id':'<?php $user=isset($_SESSION['agent']['user_id'])?$_SESSION['agent']['user_id'] : null ; echo $user;?>',
                    }
                })
                .done(function(data) {
                    if (data) {
                        console.log(data);
                        // var result = JSON.parse(data);
                       
                        // $.each(result, function(i, item) {
                            
                        //     i++;
                        //     $('<tr>').append(
                        //         $('<td>').html('<h3>' + i + ' ' + item.question_name + '</h3>' +
                        //             '<div class="form-group"><label for="Feedback or Hint">Gernal Feedback or Hint</label><p class="form-control col-sm-8"></p></div>'+
                        //             '<div class="form-group"><label for="question_text">Writte Answer Here</label><textarea class="form-control" id="question_texts'+item.quiz_essay_id+'" name="'+item.quiz_essay_id+'" placeholder="Enter Answer here" Required></textarea></div>' 
                        //         ),
                               
                            // ).appendTo('#chats_table');
                            
                        // });
                        
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                       
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>

<?php
}
if ($basename == 'assignedquiz.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/userdash_script.php',
                    data: {
                        'type': 'getQuizCategoryWise',
                        'room': '<?php echo $_GET['room']; ?>'
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $.each(result, function(i, item) {
                            var deleteEditLink = '<a href="javascript:void(0);" id="deleteQuiz' + item.id + '"data-localize="delete">Delete</a>';
                            $('<tr>').append(
                                $('<td>').text(i + 1),
                                $('<td>').text(item.category),
                                // $('<td>').text(item.question_count),
                                $('<td>').html('<a  target="blank" href="quizstart.php?category='+item.category+'">Attempt</a>'),
                                $('<td>').html('<a  target="blank" href="essayquizstart.php?category='+item.category+'">Attempt</a>'),
                            ).appendTo('#chats_table');
                            $('#deleteQuiz' + item.id).on('click', function() {
                                deleteItem(item.id, 'quiz');
                            });
                        });

                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                        $('#chats_table').DataTable({
                            "pagingType": "full_numbers",
                            // "order": [[0, 'desc']],
                            "language": {
                                "url": "locales/table.json"
                            }
                        });
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        });
    </script>
<?php
}
if ($basename == 'visitorQuizindexUserwise.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getvisitorQuizindexUsewise'}
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);
                            var join = 0;
                            var left = 0;
                            var joined = 0;
                            var oldStartTime = '';
                            var startTime = '';
                            var endTime = ''
                            $.each(result, function (i, item) {
                                
                                (item.is_eligible ==1) ? item.is_eligible = 'Yes' : item.is_eligible = 'No';
                                $('<tr>').append(
                                        $('<td>').text(i+1),
                                        $('<td>').html('<a  target="blank" href="visitorQuizindex.php?user_id='+item.user_id+'">'+item.username+'</a>'),
                                        // <a  target="blank" href="assignedquiz.php?room='+item.roomId+'">View</a>
                                        $('<td>').text(item.timestamp),
                                        ).appendTo('#users_table');
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                }
                            });
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
<?php
}
if ($basename == 'visitorCreditIndexUserwise.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getvisitorCreditIndexUserwise'}
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);

                            $.each(result, function (i, item) {
                                (item.is_eligible ==1) ? item.is_eligible = 'Yes' : item.is_eligible = 'No';
                                $('<tr>').append(
                                        $('<td>').text(i+1),
                                        $('<td>').html('<a  target="blank" href="visitorCreditIndex.php?user_id='+item.user_id+'">'+item.username+'</a>'),
                                        // <a  target="blank" href="assignedquiz.php?room='+item.roomId+'">View</a>
                                        $('<td>').text(item.timestamp),
                                        ).appendTo('#users_table');
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                }
                            });
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
<?php
}
if ($basename == 'userwiseCertificationAllow.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getuserwiseCertificationAllow'}
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);

                            $.each(result, function (i, item) {
                                (item.is_eligible ==1) ? item.is_eligible = 'Yes' : item.is_eligible = 'No';
                                // var cf_allow = item.credits >= item.minimum_credit_score ? 1 : 0 ;
                                var cf_allow = (item.minimum_credit_score == 0 && item.credits == 0) ? 'N/A': item.credits >= item.minimum_credit_score ? 1 : 0 ;
                                $('<tr>').append(
                                        $('<td>').text(i+1),
                                        $('<td>').text(item.name),
                                        $('<td>').text(item.minimum_credit_score),
                                        $('<td>').text(item.credits),
                                        $('<td>').text(cf_allow),
                                        $('<td>').html(cf_allow == 1 ? '<center><a  target="blank" href="userCertification.php?user_id='+item.user_id+'">Generate Certificate <i class="fas fa-fw fa-file-pdf"></i></a></center>':'N/A'),
                                        ).appendTo('#users_table');
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                }
                            });
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
<?php
}
if ($basename == 'visitorQuizindex.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getvisitorquizindex',
                        'user_id': '<?php echo $_GET['user_id']; ?>'
                }
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);
                            var join = 0;
                            var left = 0;
                            var joined = 0;
                            var oldStartTime = '';
                            var startTime = '';
                            var endTime = ''
                            $.each(result, function (i, item) {
                                
                                (item.is_eligible ==1) ? item.is_eligible = 'Yes' : item.is_eligible = 'No';
                                $('<tr>').append(
                                        $('<td>').text(i+1),
                                        $('<td>').text(item.username),
                                        $('<td>').text(item.roomId),
                                        $('<td>').text(item.quiz_category),
                                        $('<td>').text(item.visitor_joining_time),
                                        $('<td>').text(item.visitor_left_time),
                                        $('<td>').text(item.spending_time),
                                        $('<td>').html(item.minimum_time),
                                        $('<td>').html(item.is_eligible),
                                        $('<td>').html(item.score),
                                        $('<td>').html(item.total_score),
                                        ).appendTo('#users_table');
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                },
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'copy', 'csv', 'excel', 'pdf', 'print'
                                    'copy','csv', 'excel'
                                ]
                            });
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
<?php
}
if ($basename == 'visitorCreditIndex.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getvisitorCreditIndex',
                        'user_id': '<?php echo $_GET['user_id']; ?>'
                }
            })
                    .done(function (data) {
                        if (data) {
                            // console.log(data);
                            var result = JSON.parse(data);
                            var join = 0;
                            var left = 0;
                            var joined = 0;
                            var oldStartTime = '';
                            var startTime = '';
                            var endTime = ''
                            $.each(result, function (i, item) {
                                var credit_status = item.score >= item.minimum_score ? "Yes": "NO";
                                (item.is_eligible ==1) ? item.is_eligible = 'Yes' : item.is_eligible = 'No';
                                $('<tr>').append(
                                        $('<td>').text(i+1),
                                        $('<td>').text(item.username),
                                        $('<td>').text(item.roomId),
                                        $('<td>').text(item.quiz_category),
                                        $('<td>').text(item.minimum_score),
                                        $('<td>').html(item.score),
                                        $('<td>').html(item.total_score),
                                        $('<td>').html(credit_status),
                                        $('<td>').html(item.credits_for_room),
                                        ).appendTo('#users_table');
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#users_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                }
                            });
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>
<?php
}
if ($basename == 'dash.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getrooms',
                        'agentId': agentId
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $('#roomsCount').html(result.length);
                    }
                })
                .fail(function() {
                    console.log(false);
                });
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getagents',
                        'agentId': agentId
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $('#agentsCount').html(result.length);
                    }
                })
                .fail(function() {
                    console.log(false);
                });
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getusers',
                        'agentId': agentId
                    }
                })
                .done(function(data) {
                    if (data) {
                        var result = JSON.parse(data);
                        $('#usersCount').html(result.length);
                    }
                })
                .fail(function() {
                    console.log(false);
                });
            $.ajaxSetup({
                cache: false
            });
            $.getJSON('https://www.new-dev.com/version/version.json', function(data) {
                if (data) {
                    var currentVersion = '<?php echo $currentVersion; ?>';
                    var newNumber = data.version.split('.');
                    var curNumber = currentVersion.split('.');
                    var isNew = false;
                    if (parseInt(curNumber[0]) < parseInt(newNumber[0])) {
                        isNew = true;
                    }
                    if (parseInt(curNumber[0]) == parseInt(newNumber[0]) && parseInt(curNumber[1]) < parseInt(newNumber[1])) {
                        isNew = true;
                    }
                    if (parseInt(curNumber[0]) == parseInt(newNumber[0]) && parseInt(curNumber[1]) == parseInt(newNumber[1]) && parseInt(curNumber[2]) < parseInt(newNumber[2])) {
                        isNew = true;
                    }

                    if (isNew) {

                        $('#remoteVersion').html('<span data-localize="new_lsv_version"></span>' + data.version + '<br/><br/><span data-localize="new_lsv_features"></span><br/>' + data.text);
                    } else {
                        $('#remoteVersion').html('<span data-localize="version_uptodate"></span>');
                    }

                } else {
                    $('#remoteVersion').html('<span data-localize="cannot_connect"></span>');
                }
                var opts = {
                    language: 'en',
                    pathPrefix: 'locales',
                    loadBase: true,
                    callback: function(data, defaultCallback) {
                        document.title = data.title;
                        defaultCallback(data);
                    }
                };
                $('[data-localize]').localize('dashboard', opts);
            });
        });
    </script>

<?php
}
if ($basename == 'room.php') {
?><script>
        <?php
        if (isset($_GET['id'])) {
        ?>

            var queryStr = function(url) {
                // This function is anonymous, is executed immediately and
                // the return value is assigned to QueryString!
                var query_string = {};
                var query = url.substring(1);
                var vars = query.split("&");
                for (var i = 0; i < vars.length; i++) {
                    var pair = vars[i].split("=");
                    if (typeof query_string[pair[0]] === "undefined") {
                        query_string[pair[0]] = pair[1];
                    } else if (typeof query_string[pair[0]] === "string") {
                        var arr = [query_string[pair[0]], pair[1]];
                        query_string[pair[0]] = arr;
                    } else {
                        query_string[pair[0]].push(pair[1]);
                    }
                }
                return query_string;
            };
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getroombyid',
                        'room_id': <?php echo (int) @$_GET['id'] ?>
                    }
                })
                .done(function(data) {
                    // console.log(data);
                    if (data) {
                        data = JSON.parse(data);
                        
                        var parsed = {};
                        if (data.visitorurl) {
                            var visitorUrl = data.visitorurl;
                            var parser = document.createElement('a');
                            parser.href = visitorUrl;
                            parsed = JSON.parse(decodeURIComponent(escape(window.atob(queryStr(parser.search).p))));
                        }
                        $('#credits_for_room').val(data.credits_for_room);
                        $('#minimum_time_for_video').val(data.minimum_time_for_video);
                        $('#minimum_time_of_quiz').val(data.minimum_time_of_quiz);
                        $('#selected_categories').val(data.quiz_categories);
                        $('#parent_idValue').val(data.parent_id);
                        $('#roomName').val(data.roomId);
                        $('#names').val(data.agent);
                        $('#visitorName').val(parsed.visitorName);
                        $('#shortagent').val(data.shortagenturl);
                        $('#shortvisitor').val(data.shortvisitorurl);
                        $('#config').val(parsed.config);
                        if (data.datetime) {
                            let current_datetime = new Date(data.datetime);
                            var formatted_date = (current_datetime.getMonth() + 1) + '/' + current_datetime.getDate() + '/' + current_datetime.getFullYear() + ' ' + current_datetime.getHours() + ':' + current_datetime.getMinutes();
                            $('#datetime').val(formatted_date);
                        }

                        $('#duration').val(data.duration);
                        if (data.duration != 15 || data.duration != 30 || data.duration != 45) {
                            $('#durationtext').val(data.duration);
                        }
                        if (parsed.disableVideo) {
                            $('#disableVideo').prop('checked', true);
                        }
                        if (parsed.disableAudio) {
                            $('#disableAudio').prop('checked', true);
                        }
                        if (parsed.disableScreenShare) {
                            $('#disableScreenShare').prop('checked', true);
                        }
                        if (parsed.disableWhiteboard) {
                            $('#disableWhiteboard').prop('checked', true);
                        }
                        if (parsed.disableTransfer) {
                            $('#disableTransfer').prop('checked', true);
                        }
                        if (parsed.autoAcceptVideo) {
                            $('#autoAcceptVideo').prop('checked', true);
                        }
                        if (parsed.autoAcceptAudio) {
                            $('#autoAcceptAudio').prop('checked', true);
                        }
                        $('#active').prop('checked', (data.is_active == "1"));
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        <?php
        }
        ?>



        var agentUrl, visitorUrl, sessionId, shortAgentUrl, shortVisitorUrl, agentBroadcastUrl, viewerBroadcastLink, shortAgentUrl_broadcast, shortVisitorUrl_broadcast;
        jQuery(document).ready(function($) {
            $('#error').hide();

            

            $('#saveRoom').on('click', function() {
            
                 //validation for blanks
                var a = document.forms["RoomForm"]["roomName"].value;
                var b = document.forms["RoomForm"]["names"].value;
                var c = document.forms["RoomForm"]["visitorName"].value;
                var d = document.forms["RoomForm"]["datetime"].value;
                var e = document.forms["RoomForm"]["active"].value;
                var f = document.forms["RoomForm"]["minimum_time_of_quiz"].value;
                var g = document.forms["RoomForm"]["minimum_time_for_video"].value;
                var h = document.forms["RoomForm"]["credits_for_room"].value;
                // var i = document.forms["RoomForm"]["categories"].value;
                if (
                    a == null || a == "" || 
                    b == null || b == "" || 
                    c == null || c == "" ||
                    d == null || d == "" ||
                    e == null || e == "" ||
                    f == null || f == "" ||
                    g == null || g == "" ||
                    h == null || h == ""
                    // i == null || i == ""
                    ) {
                        alert("Please Fill All Required Field with red * marks");
                        return false;
                }
                
                generateLink();
                var datetime = ($('#datetime').val()) ? new Date($('#datetime').val()).toISOString() : '';
                var duration = ($('#durationtext').val()) ? $('#durationtext').val() : $('#duration').val();
                <?php
                if (isset($_GET['id'])) {
                ?>

                //    values of checkbox who are checked in update
                    // var val = [];
                    // $("input[name='category']:checked").each(function(i){
                    // val.push($(this).val());
                    // });
                    // if((val.length) != 0) {
                    //     val.join(", ");
                    // }else{
                    //     alert('Please choose at least one category otherwise quiz not showing.');
                    //     // return false;
                    //     // val = "None";
                    // };
                    // var categories = val;
                    
                    var dataObj = {
                        'room_id': '<?php echo $_GET['id']; ?>',
                        'type': 'editroom',
                        'agentId': agentId,
                        'agent': $('#names').val(),
                        'agenturl': agentUrl,
                        'visitor': $('#visitorName').val(),
                        'visitorurl': visitorUrl,
                        'password': $('#roomPass').val(),
                        'session': sessionId,
                        'datetime': datetime,
                        'duration': duration,
                        'shortVisitorUrl': shortVisitorUrl,
                        'shortAgentUrl': shortAgentUrl,
                        'agenturl_broadcast': agentBroadcastUrl,
                        'visitorurl_broadcast': viewerBroadcastLink,
                        'shortVisitorUrl_broadcast': shortVisitorUrl_broadcast,
                        'shortAgentUrl_broadcast': shortAgentUrl_broadcast,
                        'is_active': $('#active').prop('checked'),
                        'parent_id': $('#parent_id').val(),
                        'categories': $('#category_dropdown').val(),
                        'minimum_time_of_quiz': $('#minimum_time_of_quiz').val(),
                        'minimum_time_for_video': $('#minimum_time_for_video').val(),
                        'credits_for_room': $('#credits_for_room').val()
                    };
                <?php
                } else {
                ?>
                    
                // values of checkbox who are checked
                    // var val = [];
                    // $("input[name='category']:checked").each(function(i){
                    // val.push($(this).val());
                    // });
                    // if((val.length) != 0) {
                    //     val.join(", ");
                    // }else{
                    //     alert('Please choose at least one category otherwise quiz not showing');
                    //     // return false;
                    //     // val = "None";
                    // };
                    // var categories = val;

                    var dataObj = {
                        'type': 'scheduling',
                        'agentId': agentId,
                        'agent': $('#names').val(),
                        'agenturl': agentUrl,
                        'visitor': $('#visitorName').val(),
                        'visitorurl': visitorUrl,
                        'password': $('#roomPass').val(),
                        'session': sessionId,
                        'datetime': datetime,
                        'duration': $('#duration').val(),
                        'shortVisitorUrl': shortVisitorUrl,
                        'shortAgentUrl': shortAgentUrl,
                        'agenturl_broadcast': agentBroadcastUrl,
                        'visitorurl_broadcast': viewerBroadcastLink,
                        'shortVisitorUrl_broadcast': shortVisitorUrl_broadcast,
                        'shortAgentUrl_broadcast': shortAgentUrl_broadcast,
                        'is_active': $('#active').prop('checked'),
                        'parent_id': $('#parent_id').val(),
                        'categories': $('#category_dropdown').val(),
                        'minimum_time_of_quiz': $('#minimum_time_of_quiz').val(),
                        'minimum_time_for_video': $('#minimum_time_for_video').val(),
                        'credits_for_room': $('#credits_for_room').val()
                    };
                   
                    //                        var dataObj = {'type': 'addroom', 'lsRepUrl': '<?php echo $actual_link; ?>', 'roomId': $('#roomName').val(), 'agentName': $('#names').val(), 'visitorName': $('#visitorName').val(), 'agentShortUrl': $('#shortagent').val(), 'visitorShortUrl': $('#shortvisitor').val(), 'password': $('#roomPass').val(),
                    //                            'config': $('#config').val(), 'dateTime': datetime, 'duration': $('#duration').val(), 'disableVideo': $('#disableVideo').prop('checked'), 'disableAudio': $('#disableAudio').prop('checked'), 'disableScreenShare': $('#disableScreenShare').prop('checked'), 'disableWhiteboard': $('#disableWhiteboard').prop('checked'), 'disableTransfer': $('#disableTransfer').prop('checked'), 'is_active': $('#active').prop('checked')};
                <?php
                }
                ?>
                $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: dataObj
                    })
                    .done(function(data) {
                        if (data == 200) {
                            location.href = 'rooms.php';
                        } else {
                            $('#error').show();
                            $('#error').html('<span data-localize="error_room_save"></span>');
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function() {
                        $('#error').show();
                        $('#error').html('<span data-localize="error_room_save"></span>');
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                    });
            });
            $('#generateLink').on('click', function() {
                generateLink(false);
                window.open(agentUrl);
                var text = $('#generateLinkModal').html();
                $('#generateLinkModal').html(text.replace('[generateLink]', visitorUrl));
                $('#generateLinkModal').modal('toggle');
            });
            $('#generateBroadcastLink').on('click', function() {
                generateLink(true);
                window.open(agentUrl);
                var text = $('#generateBroadcastLinkModal').html();
                $('#generateBroadcastLinkModal').html(text.replace('[generateBroadcastLink]', viewerBroadcastLink));
                $('#generateBroadcastLinkModal').modal('toggle');
            });
            var d = new Date();
            //            $('#datetime').datetimepicker({
            //                timeFormat: 'h:mm TT',
            //                stepHour: 1,
            //    //                        stepMinute: 15,
            //                controlType: 'select',
            //                hourMin: 8,
            //                hourMax: 21,
            //                minDate: new Date(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), 0),
            //                oneLine: true
            //            });
            $('#datetime').datetimepicker({

                format: 'MM/DD/YYYY HH:mm',
                minDate: new Date(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), 0),
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-check',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });
        });
    </script>

<?php } ?>
<script>
    jQuery(document).ready(function($) {
        var opts = {
            language: 'en',
            pathPrefix: 'locales',
            loadBase: true,
            callback: function(data, defaultCallback) {
                document.title = data.title;
                defaultCallback(data);
            }
        };
        $('[data-localize]').localize('dashboard', opts);
    });
</script>
<!-- clash script -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
<!-- <script src="vendor/datatables/jquery.dataTables.min.js"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->

<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>


<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-datetimepicker.js"></script>
<script src="js/jquery.localize.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $actual_link; ?>js/loader.v2.js" data-source_path="<?php echo $actual_link; ?>"></script>
</body>

</html>