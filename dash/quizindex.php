<?php
include_once 'header.php';
?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" data-localize="quiz_list">Quiz List</h6>
        <div class="float-right"><a href="quiz.php" class=""><i class="fas fa-plus fa-2x text-300"></i></a></div>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="chats_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" data-localize="s.no">S.no</th>
                        <th class="text-center" data-localize="category">Category</th>
                        <th class="text-center" data-localize="que">Question</th>
                        <th class="text-center" data-localize="option1">option1</th>
                        <th class="text-center" data-localize="option2">option2</th>
                        <th class="text-center" data-localize="option3">option3</th>
                        <th class="text-center" data-localize="option3">option4</th>
                        <th class="text-center" data-localize="answer">Answer</th>
                        <!-- <th class="text-center" data-localize="correctanswer">user Answer</th> -->
                        <th class="text-center" data-localize="option3">Status</th>
                        <th class="text-center" data-localize="action">Action</th>
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
