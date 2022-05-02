<?php
include_once 'header.php';
?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left" data-localize="watched_videos_tracking">Videos Tracking Report By Room</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="recordings_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" data-localize="filename">Filename</th>
                        <th class="text-center" data-localize="roomid">RoomId</th>
                        <th class="text-center" data-localize="videoDuration">Video Category</th>
                        <th class="text-center" data-localize="videoDuration">Video duration</th>
                        <th class="text-center" data-localize="videoWatchedTime">Video watched Time</th>
                        <th class="text-center" data-localize="minimumTime">Minimum Time</th>
                        <th class="text-center" data-localize="username">Username</th>
                        <th class="text-center" data-localize="quizstatus">Quiz Access</th>
                        <th class="text-center" data-localize="timestamp">Timestamp</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
        </div>

    </div>

</div>



<?php
include_once 'footer.php';
?>
