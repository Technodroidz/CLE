<?php
include_once 'header.php';
?>

<?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left" data-localize="usersReport">User Report</h6>
            
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered" id="users_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center" data-localize="name">Name</th>
                            <th class="text-center" data-localize="username">Username</th>
                            <th class="text-center" data-localize="loginstatus">Status</th>
                            <th class="text-center" data-localize="blocked">Blocked</th>
                            <th class="text-center" data-localize="credits">Credits In Minutes</th>
                            <th class="text-center" data-localize="credits">Minimum Credits Score</th>
                            <th class="text-center" data-localize="credits">Event Attends</th>
                            
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
