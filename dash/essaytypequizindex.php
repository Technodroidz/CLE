<?php
    include_once 'header.php';
?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" data-localize="quiz_list">Essay Type Quiz List</h6>
        <div class="float-right"><a href="essaytypequiz.php" class=""><i class="fas fa-plus fa-2x text-300"></i></a></div>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="chats_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" data-localize="s.no">S.no</th>
                        <th class="text-center" data-localize="category">Category</th>
                        <th class="text-center" data-localize="question_name">Question name</th>
                        <th class="text-center" data-localize="status">Status</th>
                        <th class="text-center" data-localize="default_marks">Default Marks</th>
                        <th class="text-center" data-localize="gernal_feedback">Gernal Feedback</th>
                        <th class="text-center" data-localize="action">Action</th>
                        <!-- <th class="text-center" data-localize="action">Action</th> -->
                    </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
        </div>

    </div>

</div>
<div id="dialogs">
    <div class="dialog-tmpl">
        <div class="dialog-body"></div>
    </div>
</div>
<?php
include_once 'footer.php';
?>
