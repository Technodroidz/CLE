<?php
include_once 'header.php';
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left" data-localize="uploads">Uploads</h6>
        <div class="float-right"><a href="uploadform.php" class=""><i class="fas fa-plus fa-2x text-300"></i></a></div>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="uploads_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" data-localize="file">File Name</th>
                        <th class="text-center" data-localize="roomid">Room ID</th>
                        <!-- <th class="text-center" data-localize="agentid">Agent Id</th>
                        <th class="text-center" data-localize="userid">User ID</th> -->
                        <th class="text-center" data-localize="uploadtype">Upload Type</th>
                        <th class="text-center" data-localize="email">Email</th>
                        <th class="text-center" data-localize="date">Date</th>
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
