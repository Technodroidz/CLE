<?php
include_once 'header.php';
?>

<?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left" data-localize="feedbacks">feedbacks</h6>
            <!-- <div class="float-right"><a href="agent.php" class=""><i class="fas fa-plus fa-2x text-300"></i></a></div> -->
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="feedback_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center" data-localize="id">Id</th>
                            <th class="text-center" data-localize="user">User</th>
                            <th class="text-center" data-localize="room_id">Room Id</th>
                            <th class="text-center" data-localize="username">Username</th>
                            <th class="text-center" data-localize="agent_email">Agent email</th>
                            <th class="text-center" data-localize="rate">Feedback</th>
                            <th class="text-center" data-localize="rate">Ratings</th>
                            <th class="text-center" data-localize="date_added">Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                </table>
            </div>

        </div>

    </div>
<?php } ?>

<?php
include_once 'footer.php';
?>
