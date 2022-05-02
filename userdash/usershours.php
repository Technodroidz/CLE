<?php
include_once 'header.php';
?>

<?php if ($_SESSION["tenant"] == 'user') { ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left" data-localize="users"></h6>
            <div class="float-right"><a href="javascript:void(0)" onclick="exportCSV()">Export CSV</a></div>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered" id="users_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center" data-localize="chat_id">logged id</th>
                            <th class="text-center" data-localize="message">Message</th>
                            <th class="text-center" data-localize="message">System Message</th>
                            <th class="text-center" data-localize="date_created">Room</th>
                            <th class="text-center" data-localize="date_created">Date Created</th>
                            <th class="text-center" data-localize="date_created">Minutes spend</th>
                            <!-- <th class="text-center" data-localize="action"></th> -->
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
