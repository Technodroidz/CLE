<?php
include_once 'header.php';
?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left" data-localize="room_for_accessing_quiz">Rooms for accessing quizs</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="rooms_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" data-localize="room">room</th>
                        <th class="text-center" data-localize="active">Active</th>
                        <th class="text-center" data-localize="role/child">Role</th>
                        <th class="text-center" data-localize="quiz_categories">Quiz Categories</th>
                        <th class="text-center" data-localize="quiz_categories">Action</th>
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
