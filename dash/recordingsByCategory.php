<?php
include_once 'header.php';
?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left" data-localize="recordingsByCategory">Recordings By Category</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="recordings_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" data-localize="s.no">S.No</th>
                        <th class="text-center" data-localize="videosCount">Videos Count</th>
                        <th class="text-center" data-localize="quizCategory">Quiz Category</th>
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
