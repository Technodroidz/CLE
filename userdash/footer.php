<div id="chats-lsv-admin"></div>
<script>

    var deleteItem = function (itemid, type) {
        if (type === 'room') {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'deleteroom', 'agentId': agentId, 'roomId': itemid}
            })
                    .done(function (data) {
                        location.reload();
                    })
                    .fail(function () {
                        console.log(false);
                    });
        } else if (type === 'agent') {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'deleteagent', 'agentId': itemid}
            })
                    .done(function (data) {
                        location.reload();
                    })
                    .fail(function () {
                        console.log(false);
                    });
        } else if (type === 'user') {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'deleteuser', 'userId': itemid}
            })
                    .done(function (data) {
                        location.reload();
                    })
                    .fail(function () {
                        console.log(false);
                    });
        } else if (type === 'recording') {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'deleterecording', 'recordingId': itemid}
            })
                    .done(function (data) {
                        location.reload();
                    })
                    .fail(function () {
                        console.log(false);
                    });
        } else if (type === 'uploads') {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'deleteuploads', 'uploadsId': itemid}
            })
                    .done(function (data) {
                        location.reload();
                    })
                    .fail(function () {
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
        jQuery(document).ready(function ($) {
            $('#error').hide();
            $('#saveAgent').click(function (event) {
    <?php
    if (isset($_GET['id'])) {
        ?>
                    var dataObj = {'type': 'editagent', 'agentId': <?php echo $_GET['id']; ?>, 'firstName': $('#first_name').val(), 'lastName': $('#last_name').val(), 'tenant': $('#tenant').val(), 'email': $('#email').val(), 'password': $('#password').val(), 'usernamehidden': $('#usernamehidden').val()};
        <?php
    } else {
        ?>
                    var dataObj = {'type': 'addagent', 'username': $('#username').val(), 'firstName': $('#first_name').val(), 'lastName': $('#last_name').val(), 'tenant': $('#tenant').val(), 'email': $('#email').val(), 'password': $('#password').val()};
        <?php
    }
    ?>
                $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: dataObj
                })
                        .done(function (data) {
                            if (data) {
                                location.href = 'agents.php';
                            } else {
                                $('#error').show();
                                $('#error').html('<span data-localize="error_agent_save"></span>');
                                var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                $('[data-localize]').localize('dashboard', opts);
                            }
                        })
                        .fail(function () {
                        });
            });
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getadmin', 'id': <?php echo (int) @$_GET['id'] ?>}
            })
                    .done(function (data) {
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
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>

    <?php
}
if ($basename == 'config.php') {
    ?>


    <script>


        jQuery(document).ready(function ($) {
            $('#error').hide();
            $('#videoScreen_exitMeetingDrop').on('change', function() {
                if (this.value == 3) {
                    $('#videoScreen_exitMeeting').show();
                } else {
                    $('#videoScreen_exitMeeting').hide();
                }
            });
            $('#saveConfig').click(function (event) {
                if ($('#videoScreen_exitMeetingDrop').val() == 1) {
                    var exitMeeting = false;
                } else if ($('#videoScreen_exitMeetingDrop').val() == 2) {
                    exitMeeting = '/';
                } else if ($('#videoScreen_exitMeetingDrop').val() == 3) {
                    exitMeeting = $('#videoScreen_exitMeeting').val()
                }
                var dataObj = {'type': 'updateconfig', 'fileName': '<?php echo $fileConfig; ?>', 'data': {
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
                    }};
                $.ajax({
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    url: '../server/script.php',
                    data: dataObj
                })
                        .done(function (data) {
                            if (data) {
                                location.href = 'config.php?file=<?php echo $fileConfig; ?>';
                            } else {
                                $('#error').show();
                                $('#error').html('<span data-localize="error_config_save"></span>');
                                var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                $('[data-localize]').localize('dashboard', opts);
                            }
                        })
                        .fail(function (e) {
                            console.log(e);
                        });
            });
            $('#addConfig').click(function (event) {
                var dataObj = {'type': 'addconfig', 'fileName': $('#fileName').val()};
                $.ajax({
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    url: '../server/script.php',
                    data: dataObj
                })
                        .done(function (data) {
                            if (data) {
                                location.href = 'config.php?file=' + $('#fileName').val();
                            } else {
                                $('#error').show();
                                $('#error').html('<span data-localize="error_config_add"></span>');
                                var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                $('[data-localize]').localize('dashboard', opts);
                            }
                        })
                        .fail(function (e) {
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
            var exitMeeting = '<?php echo addslashes($data->videoScreen->exitMeeting);?>';
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
        });</script>

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
        jQuery(document).ready(function ($) {
            $('#error').hide();
            $('#saveLocale').click(function (event) {
                var dataObj = {'type': 'updatelocale', 'fileName': '<?php echo $fileLocale; ?>', 'data': {<?php echo $fileData; ?>}};
                $.ajax({
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    url: '../server/script.php',
                    data: dataObj
                })
                        .done(function (data) {
                            if (data) {
                                location.href = 'locale.php?file=<?php echo $fileLocale; ?>';
                            } else {
                                $('#error').show();
                                $('#error').html('<span data-localize="error_locale_save"></span>');
                                var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                $('[data-localize]').localize('dashboard', opts);
                            }
                        })
                        .fail(function (e) {
                            console.log(e);
                        });
            });
            $('#addLocale').click(function (event) {
                var dataObj = {'type': 'addlocale', 'fileName': $('#fileName').val()};
                $.ajax({
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    url: '../server/script.php',
                    data: dataObj
                })
                        .done(function (data) {
                            if (data) {
                                location.href = 'locale.php?file=' + $('#fileName').val();
                            } else {
                                $('#error').show();
                                $('#error').html('<span data-localize="error_locale_add"></span>');
                                var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                $('[data-localize]').localize('dashboard', opts);
                            }
                        })
                        .fail(function (e) {
                            console.log(e);
                        });
            });

            $('#localeStrings').html('<?php echo $fileContent; ?>');
        });</script>

    <?php
}
if ($basename == 'user.php') {
    ?>


    <script>


        jQuery(document).ready(function ($) {
            $('#error').hide();
            $('#saveUser').click(function (event) {
                var isBlocked = ($('#is_blocked').prop('checked')) ? 1 : 0;
    <?php
    if (isset($_GET['id'])) {
        ?>
                    var name = $('#first_name').val() + ' ' + $('#last_name').val();
                    var dataObj = {'type': 'edituser', 'userId': <?php echo $_GET['id']; ?>, 'name': name, 'firstName': $('#first_name').val(), 'lastName': $('#last_name').val(), 'username': $('#email').val(), 'password': $('#password').val(), 'isBlocked': isBlocked};
        <?php
    } else {
        ?>
                    var dataObj = {'type': 'adduser', 'username': $('#email').val(), 'firstName': $('#first_name').val(), 'lastName': $('#last_name').val(), 'name': $('#first_name').val() + ' ' + $('#last_name').val(), 'password': $('#password').val(), 'isBlocked': isBlocked};
        <?php
    }
    ?>
                $.ajax({
                    type: 'POST',
                    url: '../server/userdash_script.php',
                    data: dataObj
                })
                        .done(function (data) {
                            if (data) {
                                location.href = 'users.php';
                            } else {
                                $('#error').show();
                                $('#error').html('<span data-localize="error_user_save"></span>');
                                var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                $('[data-localize]').localize('dashboard', opts);
                            }
                        })
                        .fail(function () {
                        });
            });
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getuser', 'id': <?php echo (int) @$_GET['id'] ?>}
            })
                    .done(function (data) {
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
                            $('#is_blocked').prop('checked', (data.is_blocked == "1"));
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>

    <?php
}
if ($basename == 'agents.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getagents'}
            })
                    .done(function (data) {
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {

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
                                $('#deleteagent' + item.agent_id).on('click', function () {
                                    deleteItem(item.agent_id, 'agent');
                                });
                            });

                            $('#agents_table').DataTable({
                                "language": {
                                    "url": "locales/table.json"
                                }
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            
                            
                            
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });</script>

    <?php
}
if ($basename == 'users.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/userdash_script.php',
                data: {'type': 'getusers'}
            })
                    .done(function (data) {
                        if (data) {
                            console.log(data);
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {
                                var yesNo = (item.is_blocked == "1") ? '<span data-localize="yes"></span>' : '<span data-localize="no"></span>';
                                $('<tr>').append(
                                        $('<td>').text(item.name),
                                        $('<td>').text(item.username),
                                        $('<td>').html(yesNo),
                                        $('<td>').html('<a href="user.php?id=' + item.user_id + '" data-localize="edit"></a>') //| <a href="javascript:void(0);" id="deleteuser' + item.user_id + '" data-localize="delete"></a>')
                                        ).appendTo('#users_table');
                                $('#deleteuser' + item.user_id).on('click', function () {
                                    deleteItem(item.user_id, 'user');
                                });
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
if ($basename == 'usershours.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/userdash_script.php',
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
                                $('<td>').html('<a  target="blank" href="quizstart.php?category='+item.category+'&room='+'<?php echo "$_GET[room]"; ?>'+'">Attempt</a>'),
                                $('<td>').html('<a  target="blank" href="essayquizstart.php?category='+item.category+'&room='+'<?php echo "$_GET[room]"; ?>'+'">Attempt</a>'),
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
if ($basename == 'visitorquizindex.php') {
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $.ajax({
                type: 'POST',
                url: '../server/userdash_script.php',
                data: {'type': 'getvisitorquizindex'}
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
                                        $('<td>').text(item.quiz_access_type),
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
if ($basename == 'essaytypequizsuser-answer.php') {
    ?>
        <script>
            jQuery(document).ready(function($) {
    
                $.ajax({
                        type: 'POST',
                        url: '../server/userdash_script.php',
                        data: {
                            'type': 'getEssayTypeQuizUsersAnswer',
                            'user_id': '<?php echo $_SESSION['agent']['user_id'] ?>'
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
                            console.log(data);
                            $.each(result, function(i, item) {
                                var deleteEditLink = '<a href="videotracking.php?video='+item.filename+'" target="_blank" data-localize="view"></a>';
                                var count = i== 0 ? 1: i+1 ;
                                $('<tr>').append(
                                    $('<td>').text(count),
                                    $('<td>').text(item.video_count),
                                    // $('<td>').text(item.agent_id),
                                    $('<td>').html('<a href="recordings.php?category='+item.quiz_category+'">' + item.quiz_category + '</a>')
                                ).appendTo('#recordings_table');
                                // $('#deleterecording' + item.recording_id).on('click', function() {
                                //     deleteItem(item.recording_id, 'recording');
                                // });
                            });
                            var opts = {
                                language: 'en',
                                pathPrefix: 'locales',
                                loadBase: true
                            };
                            $('[data-localize]').localize('dashboard', opts);
                            $('#recordings_table').DataTable({
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
if ($basename == 'recordings.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getrecordings',
                        'category': '<?php echo $_GET['category']; ?>'}
            })
                    .done(function (data) {
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {
                                // var deleteEditLink = '<a href="../server/recordings/' + item.filename + '" target="_blank" data-localize="view"></a> | <a href="javascript:void(0);" id="deleterecording' + item.recording_id + '" data-localize="delete"></a>';
                                var deleteEditLink = '<a href="../server/recordings/' + item.filename + '" target="_blank" data-localize="view"></a>';
                                $('<tr>').append(
                                        // $('<td>').html('<a href="../server/recordings/' + item.filename + '" target="_blank">' + item.filename + '</a>'),
                                        $('<td>').text(item.recording_id),
                                        $('<td>').html('<a href="videotracking.php?video='+item.filename+'&video_id='+item.recording_id+'&username=<?php echo $_SESSION["username"]; ?>" target="_blank">' + item.filename + '</a>'),
                                        $('<td>').text(item.room_id),
                                        $('<td>').text(item.agent_id),
                                        $('<td>').text(item.date_created),
                                        $('<td>').html(deleteEditLink)
                                        ).appendTo('#recordings_table');
                                $('#deleterecording' + item.recording_id).on('click', function () {
                                    deleteItem(item.recording_id, 'recording');
                                });
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#recordings_table').DataTable({
                                "order": [[3, 'desc']],
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
if ($basename == 'videosTrackingReportByroom.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/userdash_script.php',
                data: {'type': 'getVideosTrackingByroom'}
        })
                    .done(function (data) {
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {
                                var deleteEditLink = '<a href="../server/recordings/' + item.filename + '" target="_blank" data-localize="view"></a> | <a href="javascript:void(0);" id="deleterecording' + item.recording_id + '" data-localize="delete"></a>';
                                var mystr = item.total_watched_time;
                                
                                var t_w_t_split = mystr.split(".");
                                var t_w_t = t_w_t_split[0];
                                
                                var myarr = mystr.split(":");
                                var minutes =  myarr[1]
                                $('<tr>').append(
                                        // $('<td>').html('<a href="../server/recordings/' + item.filename + '" target="_blank">' + item.filename + '</a>'),
                                        $('<td>').html('<a href="videotracking.php?video='+item.filename+'&video_id='+item.recording_id+'&username=<?php echo $_SESSION["username"]; ?>" target="_blank">' + item.filename + '</a>'),
                                        $('<td>').html('<a href="videosTrackingReport.php?room='+item.roomId+'&video_id='+item.recording_id+'&username=<?php echo $_SESSION["username"]; ?>" target="_blank">' + item.roomId + '</a>'),
                                        $('<td>').text(item.quiz_categories),
                                        $('<td>').text(item.video_duration),
                                        $('<td>').text(t_w_t),
                                        $('<td>').text(item.minimum_time_for_video),
                                        $('<td>').text(item.username),
                                       
                                        $('<td>').html((item.minimum_time_for_video <= minutes)? 'Allowed': 'not allowed'),
                                        $('<td>').html(item.timestamp),
                                        ).appendTo('#recordings_table');
                                $('#deleterecording' + item.recording_id).on('click', function () {
                                    deleteItem(item.recording_id, 'recording');
                                });
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#recordings_table').DataTable({
                                "order": [[3, 'desc']],
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
if ($basename == 'videosTrackingReportByvideo.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/userdash_script.php',
                data: {'type': 'getVideosTrackingByvideo',
                        'video_id': '<?php echo $_GET['video_id']; ?>'}
            })
                    .done(function (data) {
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {
                                var deleteEditLink = '<a href="../server/recordings/' + item.filename + '" target="_blank" data-localize="view"></a> | <a href="javascript:void(0);" id="deleterecording' + item.recording_id + '" data-localize="delete"></a>';
                                $('<tr>').append(
                                        // $('<td>').html('<a href="../server/recordings/' + item.filename + '" target="_blank">' + item.filename + '</a>'),
                                        $('<td>').html('<a href="videotracking.php?video='+item.filename+'&video_id='+item.recording_id+'&username=<?php echo $_SESSION["username"]; ?>" target="_blank">' + item.filename + '</a>'),
                                        $('<td>').text(item.room_id),
                                        $('<td>').text(item.video_id),
                                        $('<td>').text(item.video_duration),
                                        $('<td>').text(item.video_watched_time),
                                        $('<td>').html(item.timestamp),
                                        ).appendTo('#recordings_table');
                                $('#deleterecording' + item.recording_id).on('click', function () {
                                    deleteItem(item.recording_id, 'recording');
                                });
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#recordings_table').DataTable({
                                "order": [[3, 'desc']],
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
if ($basename == 'videosTrackingReport.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/userdash_script.php',
                data: {'type': 'getVideosTracking',
                        'video_id': '<?php echo $_GET['video_id']?>',
                        'username': '<?php echo $_GET['username']?>',
                        'room': '<?php echo $_GET['room']?>',
                }
            })
                    .done(function (data) {
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {
                                var deleteEditLink = '<a href="../server/recordings/' + item.filename + '" target="_blank" data-localize="view"></a> | <a href="javascript:void(0);" id="deleterecording' + item.recording_id + '" data-localize="delete"></a>';
                                var quiz_status = (String(item.video_duration) <= String(item.video_watched_time) ? 'Allowed':'Not-Allowed');
                                var mystr = item.total_watched_time;

                                var t_w_t_split = mystr.split(".");
                                var t_w_t = t_w_t_split[0];

                                var myarr = mystr.split(":");
                                var minutes =  myarr[1];
                                var quiz_status = ((item.minimum_time_for_video <=  myarr[1]) ? 'Allowed':'Not-Allowed');
                                
                                $('<tr>').append(
                                        // $('<td>').html('<a href="../server/recordings/' + item.filename + '" target="_blank">' + item.filename + '</a>'),
                                        $('<td>').html('<a href="videotracking.php?video='+item.filename+'&video_id='+item.recording_id+'&username=<?php echo $_SESSION["username"]; ?>">' + item.filename + '</a>'),
                                        $('<td>').text(item.room_id),
                                        $('<td>').html('<a href="videosTrackingReportByvideo.php?video='+item.filename+'&video_id='+item.recording_id+'&username=<?php echo $_SESSION["username"]; ?>">' + item.video_id + '</a>'),
                                        $('<td>').text(item.video_duration),
                                        // $('<td>').text(item.video_watched_time),
                                        $('<td>').text(t_w_t),
                                        $('<td>').text(item.minimum_time_for_video),
                                        $('<td>').text(item.username),
                                        $('<td>').html(quiz_status=='Allowed'?'<a href="assignedquiz.php?room='+item.roomId+'" onclick="addQuiz()">'+item.roomId+'</a>':quiz_status),
                                        $('<td>').html(item.timestamp)
                                        ).appendTo('#recordings_table');
                                $('#deleterecording' + item.recording_id).on('click', function () {
                                    deleteItem(item.recording_id, 'recording');
                                });
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#recordings_table').DataTable({
                                "order": [[3, 'desc']],
                                "language": {
                                    "url": "locales/table.json"
                                }
                            });
                        }
                    })
                    .fail(function (e) {
                        console.log(e);
                    });
        });
        function addQuiz(){
            alert('You can access into quiz succcessfully');
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {
                    type: "addquizreport",
                    email: '<?php echo $_GET['username'];?>',
                    room_id: '<?php echo $_GET['room'];?>',
                    quiz_access_type: 'video_tracking',
                    
                }
            })
            .done(function (data) {
                alert('successfully added');
            })
            .fail(function (data) {
                alert('failed');
            })
        }
    </script>

    <?php
}
// user rooms it is modified by agent room-------
if ($basename == 'rooms.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {
            var currdatetime =  new Date().toISOString() 
            $.ajax({
                type: 'POST',
                url: '../server/userdash_script.php',
                data: {'type': 'getrooms', 'agentId': agentId, 'currentdatetime':currdatetime}
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
if ($basename == 'roomsforquiz.php') {
?>
    <script>
        jQuery(document).ready(function($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/userdash_script.php',
                    data: {
                        'type': 'getRoomsForQuiz',
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
                url: '../server/userdash_script.php',
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

        jQuery(document).ready(function ($) {
            setTimeout(function () {
                svConfigs.agentName = '<?php echo @$_SESSION["agent"]["first_name"] . ' ' . @$_SESSION["agent"]["last_name"]; ?>';
            }, 3000);
        });</script>

    <?php
}
if ($basename == 'chats.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getchats', 'agentId': agentId}
            })
                    .done(function (data) {
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {
                                var openModal = '<div class="modal fade" id="ex' + item.room_id + '" tabindex="-1" role="dialog" aria-labelledby="ex' + item.room_id + '" aria-hidden="true"><div class="modal-dialog modal-lgr" role="document"><button type="button" data-toggle="modal" class="closeDocumentModal" data-target="#ex' + item.room_id + '" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="fa fa-times"></span></button><div class="modal-content">' + item.messages + '</div>     </div> </div><a href="" class="btn fa fa-comments" data-toggle="modal" data-target="#ex' + item.room_id + '"></a>';
                                $('<tr>').append(
                                        $('<td>').text(item.date_created),
                                        $('<td>').text(item.room_id),
                                        $('<td>').html(openModal),
                                        $('<td>').text(item.agent),
                                        ).appendTo('#chats_table');
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#chats_table').DataTable({
                                "pagingType": "full_numbers",
                                "order": [[0, 'desc']],
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
if ($basename == 'quizstart.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'getQuiz','category': '<?php echo $_GET['category']; ?>' }
            })
                    .done(function (data) {
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {
                                i++;
                                $('<tr>').append(

                                        $('<td>').html('<h3><br>'+i+' '+item.que+'</h3>'+
                                        '&nbsp;&nbsp;&nbsp;&nbsp;a )&nbsp;&nbsp;&nbsp;<input required type="radio" name ="'+item.id+'" value="'+item.option1+'">&nbsp;'+item.option1+'<br>'+
                                        '&nbsp;&nbsp;&nbsp;&nbsp;b )&nbsp;&nbsp;&nbsp;<input required type="radio" name ="'+item.id+'" value="'+item.option2+'">&nbsp;'+item.option2+'<br>'+
                                        '&nbsp;&nbsp;&nbsp;&nbsp;c )&nbsp;&nbsp;&nbsp;<input required type="radio" name ="'+item.id+'" value="'+item.option3+'">&nbsp;'+item.option3+'<br>'+
                                        '&nbsp;&nbsp;&nbsp;&nbsp;d )&nbsp;&nbsp;&nbsp;<input required type="radio" name ="'+item.id+'" value="'+item.option4+'">&nbsp;'+item.option4+'<br>'
                                        ),
                                        // $('<tr>').html('<input type="radio" value="'+item.option1+'">'+item.option1),
                                        
                                        ).appendTo('#chats_table');
                            });
                            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                            $('[data-localize]').localize('dashboard', opts);
                            $('#chats_table').DataTable({
                                "pagingType": "full_numbers",
                                "order": [[0, 'desc']],
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
if ($basename == 'quizresult.php') {
    ?>
    <script>

        jQuery(document).ready(function ($) {

            $.ajax({
                type: 'POST',
                url: '../server/userdash_script.php',
                data: {'type': 'quizaccessupdate',
                        'username': "<?php echo $_SESSION["username"]; ?>",
                        'quiz_total_score': $("#quiz_total_score").val(),
                        'quiz_your_score': $("#quiz_your_score").val(),
                        'category': '<?php echo $_POST["category"]; ?>',
                        'roomId': '<?php echo $_POST["roomId"]; ?>'
                    }
            })
                    .done(function (data) {
                        if (data) {
                            var result = JSON.parse(data);
                            console.log(result);
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

        jQuery(document).ready(function ($) {

            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {'type': 'getcredits', 'agentId': agentId}
                })
                        .done(function (data) {
                            if (data) {
                                var result = JSON.parse(data);
                                $('#roomsCount').html(result.length);
                            }
                        })
                        .fail(function () {
                            console.log(false);
                        });
                // $.ajax({
                //     type: 'POST',
                //     url: '../server/script.php',
                //     data: {'type': 'getrooms', 'agentId': agentId}
                // })
                //         .done(function (data) {
                //             if (data) {
                //                 var result = JSON.parse(data);
                //                 $('#roomsCount').html(result.length);
                //             }
                //         })
                //         .fail(function () {
                //             console.log(false);
                //         });
                // $.ajax({
                //     type: 'POST',
                //     url: '../server/script.php',
                //     data: {'type': 'getagents', 'agentId': agentId}
                // })
                //         .done(function (data) {
                //             if (data) {
                //                 var result = JSON.parse(data);
                //                 $('#agentsCount').html(result.length);
                //             }
                //         })
                //         .fail(function () {
                //             console.log(false);
                //         });
                $.ajax({
                    type: 'POST',
                    url: '../server/userdash_script.php',
                    data: {'type': 'getusers', 'agentId': agentId}
                })
                        .done(function (data) {
                            
                            if (data) {
                                var result = JSON.parse(data);
                                $('#usersCount').html(result.length);
                                $('#usersCount').html(result.length)
                                $('#creditsCount').html(result[0].credits)
                               
                            }
                        })
                        .fail(function () {
                            console.log(false);
                        });
            $.ajaxSetup({cache: false});
            $.getJSON('https://www.new-dev.com/version/version.json', function (data) {
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
                var opts = {language: 'en', pathPrefix: 'locales', loadBase: true, callback: function (data, defaultCallback) {
                        document.title = data.title;
                        defaultCallback(data);
                    }};
                $('[data-localize]').localize('dashboard', opts);
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
                url: '../server/userdash_script.php',
                data: {'type': 'getUpload','username': "<?php echo $_SESSION["username"]; ?>"}
            })
                    .done(function (data) {
                        // console.log(data)
                        if (data) {
                            var result = JSON.parse(data);
                            $.each(result, function (i, item) {
                                var deleteEditLink = '<a href="../server/uploads/' + item.filename + '" target="_blank">Download</a> | <a href="javascript:void(0);" id="deleteuploads' + item.id + '" data-localize="delete"></a>';
                                $('<tr>').append(
                                        $('<td>').html('<a href="../server/uploads/' + item.filename + '" target="_blank">' + item.filename + '</a>'),
                                        // $('<td>').text(item.room_id),
                                        $('<td>').text((item.room_id == 'null')?'N/A':item.room_id),
                                        $('<td>').text((item.agent_id == null)?'N/A':item.first_name),
                                        $('<td>').text((item.user_id == null)?'N/A':item.name),
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
                                "order": [[7, 'desc']],
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
if ($basename == 'room.php') {
    ?><script>
    <?php
    if (isset($_GET['id'])) {
        ?>

                var queryStr = function (url) {
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
                    data: {'type': 'getroombyid', 'room_id': <?php echo (int) @$_GET['id'] ?>}
                })
                        .done(function (data) {
                            if (data) {
                                data = JSON.parse(data);
                                var parsed = {};
                                if (data.visitorurl) {
                                    var visitorUrl = data.visitorurl;
                                    var parser = document.createElement('a');
                                    parser.href = visitorUrl;
                                    parsed = JSON.parse(decodeURIComponent(escape(window.atob(queryStr(parser.search).p))));
                                }
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
                        .fail(function (e) {
                            console.log(e);
                        });
        <?php
    }
    ?>



            var agentUrl, visitorUrl, sessionId, shortAgentUrl, shortVisitorUrl, agentBroadcastUrl, viewerBroadcastLink, shortAgentUrl_broadcast, shortVisitorUrl_broadcast;
            jQuery(document).ready(function ($) {
                $('#error').hide();
                $('#saveRoom').on('click', function () {
                    generateLink();
                    var datetime = ($('#datetime').val()) ? new Date($('#datetime').val()).toISOString() : '';
                    var duration = ($('#durationtext').val()) ? $('#durationtext').val() : $('#duration').val();
    <?php
    if (isset($_GET['id'])) {
        ?>
                        var dataObj = {'room_id': '<?php echo $_GET['id']; ?>', 'type': 'editroom', 'agentId': agentId, 'agent': $('#names').val(), 'agenturl': agentUrl, 'visitor': $('#visitorName').val(), 'visitorurl': visitorUrl,
                            'password': $('#roomPass').val(), 'session': sessionId, 'datetime': datetime, 'duration': duration, 'shortVisitorUrl': shortVisitorUrl, 'shortAgentUrl': shortAgentUrl,
                            'agenturl_broadcast': agentBroadcastUrl, 'visitorurl_broadcast': viewerBroadcastLink, 'shortVisitorUrl_broadcast': shortVisitorUrl_broadcast, 'shortAgentUrl_broadcast': shortAgentUrl_broadcast, 'is_active': $('#active').prop('checked')};
        <?php
    } else {
        ?>
                        var dataObj = {'type': 'scheduling', 'agentId': agentId, 'agent': $('#names').val(), 'agenturl': agentUrl, 'visitor': $('#visitorName').val(), 'visitorurl': visitorUrl,
                            'password': $('#roomPass').val(), 'session': sessionId, 'datetime': datetime, 'duration': $('#duration').val(), 'shortVisitorUrl': shortVisitorUrl, 'shortAgentUrl': shortAgentUrl,
                            'agenturl_broadcast': agentBroadcastUrl, 'visitorurl_broadcast': viewerBroadcastLink, 'shortVisitorUrl_broadcast': shortVisitorUrl_broadcast, 'shortAgentUrl_broadcast': shortAgentUrl_broadcast, 'is_active': $('#active').prop('checked')};
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
                            .done(function (data) {
                                if (data == 200) {
                                    location.href = 'rooms.php';
                                } else {
                                    $('#error').show();
                                    $('#error').html('<span data-localize="error_room_save"></span>');
                                    var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                    $('[data-localize]').localize('dashboard', opts);
                                }
                            })
                            .fail(function () {
                                $('#error').show();
                                $('#error').html('<span data-localize="error_room_save"></span>');
                                var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                $('[data-localize]').localize('dashboard', opts);
                            });
                });
                $('#generateLink').on('click', function () {
                    generateLink(false);
                    window.open(agentUrl);
                    var text = $('#generateLinkModal').html();
                    $('#generateLinkModal').html(text.replace('[generateLink]', visitorUrl));
                    $('#generateLinkModal').modal('toggle');
                });
                $('#generateBroadcastLink').on('click', function () {
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
            });</script>

<?php } ?>
<script>
    jQuery(document).ready(function ($) {
        var opts = {language: 'en', pathPrefix: 'locales', loadBase: true, callback: function (data, defaultCallback) {
                document.title = data.title;
                defaultCallback(data);
            }};
        $('[data-localize]').localize('dashboard', opts);
    });

</script>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-datetimepicker.js"></script>
<script src="js/jquery.localize.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $actual_link; ?>js/loader.v2.js" data-source_path="<?php echo $actual_link; ?>" ></script>
</body>

</html>
