<?php
include_once 'header.php';
?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left" data-localize="watched_videos_tracking">Videos Tracking Report By User</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="recordings_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" data-localize="username">Username</th>
                        <th class="text-center" data-localize="room">room</th>
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
