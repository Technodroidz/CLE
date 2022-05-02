<?php
    include_once 'header.php';
?>
<?php if ($_SESSION["tenant"] != 'user') { ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left" data-localize="usersbyrooms">Agents Hours By Rooms</h6>
            <div class="float-right"><a href="javascript:void(0)" onclick="exportCSV()">Export CSV</a></div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="users_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center" data-localize="chat_id">logged id</th>
                            <th class="text-center" data-localize="date_created">Rooms</th>
                            <!-- <th class="text-center" data-localize="date_created">Date Created</th> -->
                            <!-- <th class="text-center" data-localize="date_created">Minutes spend</th> -->
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
<script>
    function exportCSV(){
      //  alert("working");
        $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'exportcsvagentshours'
                    }
                })
                .done(function(data) {
                   // alert(data);
                    //console.log(data);
                   if(data){
                    var result = JSON.parse(data);
                    // console.log(result);
                     const csvString = [
                        [
                          "LOGGED ID",
                          "ROOMS"
                        ],
                        result.map(item => [
                          item.logged_id,
                          item.room_id
                        ])
                      ];

                    //console.log(csvString);

                    document.write(csvString);  
                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csvString);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'agentshoursByrooms.csv';
                    hiddenElement.click();

                    window.location.href = 'https://continuouslegaleducation.online/dash/agentshoursByrooms.php';
                     
                   }
                })
                .fail(function(e) {
                    console.log(e);
                });
    }
</script>
