<?php
include_once 'header.php';
?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" data-localize="quiz_list">Assign Quiz List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="chats_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" data-localize="s.no">S.no</th>
                        <th class="text-center" data-localize="category">Category</th>
                        <!-- <th class="text-center" data-localize="questionsCount">Questions Count</th> -->
                        <th class="text-center" data-localize="mcqQuestion">Attempt MCQ Question</th>
                        <th class="text-center" data-localize="essayTypeQuestion">Attempt Essay Type Question</th>
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
