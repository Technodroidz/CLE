<?php
include_once 'header.php';
?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left" data-localize="rooms"></h6>
        <div class="float-right"><a href="room.php" class=""><i class="fas fa-plus fa-2x text-300"></i></a></div>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="rooms_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" data-localize="room">Room</th>
                        <th class="text-center" data-localize="agent">Agent</th>
                        <th class="text-center" data-localize="visitor">Visitor</th>
                        <th class="text-center" data-localize="Visitors_limit">Visitors limit</th>
                        <th class="text-center" data-localize="onlinevisits">Online visitors</th>
                        <th class="text-center" data-localize="quiz_categories">Quiz Categories</th>
                        <th class="text-center" data-localize="minimum_event_time">Minimum Event Time</th>
                        <th class="text-center" data-localize="minimum_video_time">Minimum Video Time</th>
                        <th class="text-center" data-localize="room_credit">Rooom Credit </th>
                        <th class="text-center" data-localize="agent_url">Agent Url</th>
                        <th class="text-center" data-localize="visitor_url">Visitor Url</th>
                        <th class="text-center" data-localize="date_duration">Date Duration</th>
                        <th class="text-center" data-localize="active">Active</th>
                        <th class="text-center" data-localize="role/child">Role</th>
                        <th class="text-center" data-localize="action">Action</th>
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
