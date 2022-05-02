<?php
include_once 'header.php';
?>

<h1 class="h3 mb-2 text-gray-800" id="roomTitle" data-localize="room"></h1>
<div id="error" style="display:none;" class="alert alert-danger"></div>

<div class="row">
    <div class="col-sm-6">
        <div class="p-1">

            <form class="user" name="RoomForm">

                <div class="form-group">
                    <label class="requiredred" for="roomName"><h6 data-localize="room_id"></h6></label>
                    <input type="text" class="form-control"  id="roomName" name="roomName" aria-describedby="roomName" placeholder="please enter room name like this : room_xyz_123" onkeyup="nospaces(this)">
                </div>
                <div class="form-group">
                    <label class="requiredred" for="names"><h6 data-localize="agent_name"></h6></label>
                    <input type="text" class="form-control" id="names" name="names" aria-describedby="names">
                </div>
                <div class="form-group">
                    <label class="requiredred" for="visitorName"><h6 data-localize="visitor_name"></h6></label>
                    <input type="text" class="form-control" id="visitorName" name="visitorName" aria-describedby="visitorName">
                </div>
                <div class="form-group">
                    <label for="shortagent" data-localize="agent_shorturl"><h6></h6></label>
                    <input type="text" class="form-control" id="shortagent"  aria-describedby="shortagent">
                </div>
                <div class="form-group">
                    <label for="shortvisitor"><h6 data-localize="visitor_shorturl"></h6></label>
                    <input type="text" class="form-control" id="shortvisitor" aria-describedby="shortvisitor" >
                </div>
                <div class="form-group">
                    <label for="roomPass"><h6 data-localize="password"></h6></label>
                    <input type="password" class="form-control" id="roomPass" aria-describedby="roomPass" autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label for="config"><h6 data-localize="room_config"></h6></label>
                    <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
                        <select class="form-control" name="config" id="config"><option value="">-</option>
                            <?php
                            if ($handle = opendir('../config')) {

                                while (false !== ($entry = readdir($handle))) {

                                    if ($entry != "." && $entry != "..") {
                                        $entryValue = substr($entry, 0, -5);
                                        echo '<option value="' . $entry . '">' . $entryValue . '</option>';
                                    }
                                }

                                closedir($handle);
                            }
                            ?>
                        </select>

                    <?php } else { ?>
                        <input type="text" class="form-control" id="config" aria-describedby="config" >
                    <?php } ?>

                </div>
                <div class="form-group">
                    <label class="requiredred" for="datetime"><h6 data-localize="date_time"></h6></label>
                    <input type="text" class="form-control" id="datetime"   name="datetime" aria-describedby="datetime">
                </div>


                <div class="form-group">
                    <label for="duration"><h6 data-localize="duration"></h6></label>
                    <select class="form-control" name="duration" id="duration"><option value="">-</option><option value="15">15</option><option value="30">30</option><option value="45">45</option></select>
                    <span data-localize="or"></span>
                    <br/>
                    <input type="text" class="form-control w-25" id="durationtext"   aria-describedby="shortagent" oninput="orDuration(this)">
                </div>

                <div class="form-group">
                    <h6 data-localize="disable"></h6>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="disableVideo">
                        <label class="custom-control-label" for="disableVideo" data-localize="disable_video"></label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="disableAudio">
                        <label class="custom-control-label" for="disableAudio" data-localize="disable_audio"></label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="disableScreenShare">
                        <label class="custom-control-label" for="disableScreenShare" data-localize="disable_screen_share"></label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="disableWhiteboard">
                        <label class="custom-control-label" for="disableWhiteboard" data-localize="disable_whiteboard"></label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="disableTransfer">
                        <label class="custom-control-label" for="disableTransfer" data-localize="disable_file_transfer"></label>
                    </div>
                </div>
                <div class="form-group">
                    <h6 data-localize="auto_accept"></h6>

                    <div class="custom-control custom-checkbox">

                        <input type="checkbox" class="custom-control-input" id="autoAcceptVideo">
                        <label class="custom-control-label" for="autoAcceptVideo" data-localize="auto_accept_video"></label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="autoAcceptAudio">
                        <label class="custom-control-label" for="autoAcceptAudio"data-localize="auto_accept_audio"></label>
                    </div>
                </div>
                <div class="form-group">
                    <h6 data-localize="room_active"></h6>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" checked="checked">
                        <label class="custom-control-label" for="active" data-localize="active"></label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="requiredred" for="minimum_time_of_quiz"><h6 data-localize="minimum_time_of_quiz">Minimum Time For Quiz Access On Event </h6></label>
                    <select class="form-control" name="minimum_time_of_quiz" id="minimum_time_of_quiz" name="minimum_time_of_quiz">
                        <option value="">-</option>
                        <option value="01">01</option>
                        <option value="05">05</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="30">30</option>
                        <option value="45">45</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="requiredred" for="minimum_time_for_video"><h6 data-localize="minimum_time_for_video">Minimum Time For Video</h6></label>
                    <select class="form-control" name="minimum_time_for_video" id="minimum_time_for_video" name="minimum_time_for_video">
                        <option value="">-</option>
                        <option value="01">01</option>
                        <option value="05">05</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="30">30</option>
                        <option value="45">45</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="requiredred" for="credits_for_room"><h6 data-localize="credits_for_room">Credits For Room</h6></label>
                    <input type="number"  min="1"  max ="100" class="form-control" id="credits_for_room" name="credits_for_room" aria-describedby="names"  oninput="manualCredits(this)" required>
                </div>
                <!-- <div class="form-group" id="category">
                    <label class="requiredred" for="categories"><h6 data-localize="role">Assign Quiz category</h6></label><br>
                    <input type="hidden" id="selected_categories" name="selected_categories">
                </div> -->

                <div class="form-group">
                    <label for="categories_dropdown"><h6 data-localize="role">Assign Quiz category</h6></label>
                    <input type="hidden" id="selected_categories" name="selected_categories">
                        <select class="form-control" name="category_dropdown" id="category_dropdown">
                        <option value="0">None</option>
                        </select>
                </div> 
                <div class="form-group">
                    <label for="parent_id"><h6 data-localize="role">Parent Id</h6></label>
                    <input type="hidden" id="parent_idValue">
                        <select class="form-control" name="parent_id" id="parent_id">
                        <option value="0">No Parent</option>
                        </select>
                </div> 
                       

                <a href="javascript:void(0);" id="saveRoom" class="btn btn-primary btn-user btn-block" data-localize="save">
                    Save
                </a>
                <hr>

            </form>

        </div>

    </div>
    <div class="col-sm-6">
        <div class="p-1">
            <h6 data-localize="room_info"></h6>
            <a href="javascript:void(0);" id="generateLink" class="btn btn-primary btn-user btn-block" data-localize="start_video">
                
            </a>
            <hr>
            <a href="javascript:void(0);" id="generateBroadcastLink" class="btn btn-primary btn-user btn-block" data-localize="start_broadcast">
                
            </a>
            <hr>

        </div>
    </div>

</div>
<style>
  .requiredred:before {
    content:" *";
    color: red;
  }
</style>
<script src="vendor/jquery/jquery.min.js"></script>
<script>
    
    // credits_for_room has to be not less than 0 
     function orDuration(t){
        var max_marks = Number($('#durationtext').val());
        var duration_value = Number(t.value);
            if(duration_value <= -1){
                $("#durationtext").val(1);
            }
    }

    // credits_for_room has to be not less than 0 
    function manualCredits(t){
        var max_marks = Number($('#credits_for_room').val());
        var manual_value = Number(t.value);
            if(manual_value <= -1){
                $("#credits_for_room").val(0);
            }
    }

    // replace the space with undersocre in room id name field 
    function nospaces(t){
        if(t.value.match(/\s/g)){
            t.value=t.value.replace(/\s/g,'_');
        }
    }

    // for category dropdown in add quiz------
    jQuery(document).ready(function($) {
        <?php
            if (!isset($_GET['id'])) {
        ?>
                var dataObj = {
                    'type': 'getrooms'
                };
                $.ajax({
                    url: "../server/script.php",
                    type: "POST",
                    data: dataObj,
                    success: function(data) {
                        var result = JSON.parse(data);
                        if (result) {
                            $.each(result, function(i, item) {
                                $('#parent_id').append(
                                    '<option value="' + item.room_id + '">' + item.roomId + '</option>',
                                );
                            });
                        } else {
                            $('#parent_id').append(
                                $('#parent_id').html('<option value=""></option>'),
                            );
                        }
                    }
                })
                // for quiz_categories
                $.ajax({
                    url: "../server/script.php",
                    type: "POST",
                    data: {'type': 'getCategorydropdown'},
                    success: function(data) {
                        var result = JSON.parse(data);
                        if (result) {
                            $.each(result, function(i, item) {
                                // $('#category').append(
                                    
                                //     ' <input type="checkbox"  name="category" value="'+item.name+'"> <label for="'+item.name+'">'+item.name+'</label><br>',
                                // );
                                $('#category_dropdown').append(
                                    '<option value="' + item.name + '">' + item.name + '</option>',
                                );
                            });
                        } else {
                            $('#select_category').append(
                                $('#category').html('<option value=""></option>'),
                                '<input type="checkbox" id="none" name="categories[]" value="none"> <label for="none">none</label>'
                            );
                        }
                    }
                })
            
        <?php
            } else {
        ?>
         $.ajax({
                    url: "../server/script.php",
                    type: "POST",
                    data: {'type': 'getCategorydropdown'},
                    success: function(data) {
                        var result = JSON.parse(data);
                        if (result) {
                            var categ_array = $('#selected_categories').val();
                            $.each(result, function(i, item) {
                                if(item.name ==  $('#selected_categories').val()){
                                   
                                    // $('#category').append(
                                    //     ' <input type="checkbox"  name="category" value="'+item.name+'" checked> <label for="'+item.name+'">'+item.name+'</label><br>',
                                    // );

                                    $('#category_dropdown').append(
                                        '<option value="' + item.name + '" selected>' + item.name + '</option>',
                                    );
                                }else{
                                    $('#category_dropdown').append(
                                        '<option value="' + item.name + '">' + item.name + '</option>',
                                    );
                                }
                                
                            });
                        } else {
                            // $('#select_category').append(
                            //     $('#category').html('<option value=""></option>'),
                            //     '<input type="checkbox" id="none" name="categories[]" value="none"> <label for="none">none</label>'
                            // );
                            $('#category_dropdown').append(
                                        '<option value="' + item.name + '">' + item.name + '</option>',
                                    );
                        }
                    }
                })
            //update quiz category ajax--------   
            var dataObj = {
                'type': 'getrooms'
            };
            $.ajax({
                url: "../server/script.php",
                type: "POST",
                data: dataObj,
                success: function(data) {
                    var result = JSON.parse(data);
                    if (result) {
                        $.each(result, function(i, item) {
                            var sameRoom = (item.room_id == <?php echo $_GET['id']?>)?true : false;
                            if(!sameRoom){
                                if (item.room_id == $('#parent_idValue').val()) {
                                    $('#parent_id').append(
                                        '<option value="' + item.room_id + '" selected >' + item.roomId + '</option>',
                                    );
                                } else {
                                    $('#parent_id').append(
                                        '<option value="' + item.room_id + '">' + item.roomId + '</option>',
                                    );
                                }
                            }  
                        });
                    } else {
                        $('#parent_id').append(
                            $('#parent_id').html('<option value=""></option>'),
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
include_once 'footer.php';
