<?php
include_once 'header.php';
?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left" data-localize="users_quiz_index">Quiz Index</h6>
            <!-- <div class="float-right"><a href="user.php" class=""><i class="fas fa-plus fa-2x text-300"></i></a></div> -->
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="users_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                        <th class="text-center" data-localize="chat_id">S.no</th>
                            <th class="text-center" data-localize="username">Username</th>
                            <th class="text-center" data-localize="room">Room</th>
                            <th class="text-center" data-localize="quiz_id">Quiz_category</th>
                            <th class="text-center" data-localize="chat_id">Joining_time</th>
                            <th class="text-center" data-localize="message">Left_time</th>
                            <th class="text-center" data-localize="message">Spending_minutes</th>
                            <th class="text-center" data-localize="Required_minutes">Required_minutes</th>
                            <th class="text-center" data-localize="Is_eligible">Is_eligible</th>
                            <th class="text-center" data-localize="user_score">User_Score</th>
                            <th class="text-center" data-localize="Out_of_total_Score">Out_of_total_Score</th>
                            <!-- <th class="text-center" data-localize="action"></th> -->
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
