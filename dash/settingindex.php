<?php
include_once 'header.php';
?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" data-localize="setting_list">Setting List</h6>
        <div class="float-right"><a href="setting.php" class=""><i class="fas fa-plus fa-2x text-300"></i></a></div>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="chats_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" data-localize="s.no">S.no</th>
                        <th class="text-center" data-localize="subject">Subject</th>
                        <th class="text-center" data-localize="description">Description</th>
                        <th class="text-center" data-localize="key_name">key Name</th>
                        <th class="text-center" data-localize="key_value">Key Value</th>
                        <th class="text-center" data-localize="timestamp">Timestamp</th>
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
