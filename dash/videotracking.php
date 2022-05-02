<style>
    #video {
        position: absolute;
        top: 0px;
        right: 0px;
        bottom: 0px;
        left: 0px;
        max-height: 100%;
        max-width: 100%;
        margin: auto;
    }
</style>
<video id="video" controls controlsList="nodownload" name="btn" value="Start">
    <source src="../server/recordings/<?php echo $_GET['video']; ?>" type="video/webm">
</video>

<input type="hidden" name="btn" id='btn' value="Start" onclick="to_start()" ;>

<!-- status--> <input type="hidden" name="status" id='status' value=""><br> 
<!--previous time --> <input type="hidden" name="previous_time" id='previous_time'><br>
<!--current time--> <input type="hidden" name="current_time" id='current_time'>

<br><br>
<div id=n1 style="display:none; z-index: 2; position: relative; right: 0px; top: 10px; background-color: #00cc33;
     width: 100px; padding: 10px; color: white; font-size:20px; border: #0000cc 2px dashed; ">
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script>
    // stop watch start    
    var h = 0;
    var m = 0;
    var s = 0;

    function to_start() {

        switch (document.getElementById('btn').value) {
            case 'Stop':
                window.clearInterval(tm); // stop the timer 
                document.getElementById('btn').value = 'Start';
                break;
            case 'Start':
                tm = window.setInterval('disp()', 1000);
                document.getElementById('btn').value = 'Stop';
                break;
        }
    }

    function disp() {
        // Format the output by adding 0 if it is single digit //
        if (s < 10) {
            var s1 = '0' + s;
        } else {
            var s1 = s;
        }
        if (m < 10) {
            var m1 = '0' + m;
        } else {
            var m1 = m;
        }
        if (h < 10) {
            var h1 = '0' + h;
        } else {
            var h1 = h;
        }
        // Display the output //
        str = h1 + ':' + m1 + ':' + s1;
        document.getElementById('n1').innerHTML = str;
        // Calculate the stop watch // 
        if (s < 59) {
            s = s + 1;
        } else {
            s = 0;
            m = m + 1;
            if (m == 60) {
                m = 0;
                h = h + 1;
            } // end if  m ==60
        } // end if else s < 59
        // end of calculation for next display
    }
    // stop watch end
    var vid = document.getElementById("video")
    $(document).ready(function() {

        var e = document.getElementById("video");
        console.log(e.readyState);
        var cancelAlert = false;
        
    });

    $('#video').on("blur focus", function(e) {
        var prevType = $(this).data("prevType");
        if (prevType != e.type) { //  reduce double fire issues
            switch (e.type) {
                case "blur":
                    $('#status').val("Blured");
                    break;
                case "focus":
                    $('#status').val("Focused");
                    vid.onplay = function() {
                        console.log('video is playing');
                        if($('#previous_time').val() == ""){
                            $('#previous_time').val("00:00:00");
                        }else{
                            $('#previous_time').val($('#current_time').val());
                        }
                        to_start();
                    }
                    vid.onpause = function() {
                        $('#current_time').val( $('#n1').html());
                        console.log('video is pause');
                        to_start();
                        var Ajaxfunction = $.ajax({
                                type: 'POST',
                                url: '../server/script.php',
                                data: {
                                    'type': 'videoTracking',
                                    'video_id': '<?php echo $_GET['video_id']; ?>',
                                    'video_duration': vid.duration,
                                    'video_watched_time': $('#n1').text(),
                                    'previous_time': $('#previous_time').val(),
                                    'current_time': $('#current_time').val(),
                                    'user': '<?php echo $_GET['username']; ?>',
                                }
                            })
                            .done(function(data) {
                                // location.reload();
                            })
                            .fail(function() {
                                console.log(false);
                            });
                    }
                    vid.onended = function() {
                        console.log('video is ended');
                    }
                    break;
            }
        }
        $(this).data("prevType", e.type);
    })
   
</script>