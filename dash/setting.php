<?php
include_once 'header.php';
?>
<?php if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) { ?>
    <div class="row">
        <div class="form-group col-md-offset-2 col-md-8">
            <div>
                <h3>Add Setting</h3>
            </div>
            <div class="form-group">
                <a href="settingindex.php">
                    <button class="btn btn-primary ">View Settings</button>
                </a>
            </div>
            <div id="pageerror" class="alert" style="display:none;">
                <ul>
                    <li></li>
                </ul>
            </div><br />
            <form method="post">
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter Subject" required="true">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description" required="true">
                </div>
                <div class="form-group">
                    <label for="key_name">Key Name</label>
                    <input type="text" class="form-control" id="key_name" name="key_name" placeholder="Enter Key Name" required="true">
                </div>
                <div class="form-group">
                    <label for="key_value">Key Value</label>
                    <input type="text" class="form-control" id="key_value" name="key_value" placeholder="Enter Key Value" required="true">
                </div>
                <button id="submitbutton" type="submit" class="btn btn-primary btn-large" value="submit" name="submit">Submit</button>

            </form>
        </div>
    </div>
<?php } ?>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script>
    jQuery(document).ready(function($) {

        $('#submitbutton').click(function(event) {
            event.preventDefault();
            <?php
            if (isset($_GET['id'])) {
            ?>
                var dataObj = {
                    'type': 'editSetting',
                    'id': <?php echo $_GET['id']; ?>,
                    'category_name': $('#category_name').val(),
                    'subject': $('#subject').val(),
                    'description': $('#description').val(),
                    'key_name': $('#key_name').val(),
                    'key_value': $('#key_value').val()
                };
            <?php
            } else {
            ?>
                var dataObj = {
                    'type': 'addSetting',
                    'subject': $('#subject').val(),
                    'description': $('#description').val(),
                    'key_name': $('#key_name').val(),
                    'key_value': $('#key_value').val()
                };
            <?php
            }
            ?>
            if ($('#key_name').val() == '') {
                $("#pageerror").addClass('alert-danger');
                $("#pageerror").html("Please enter some value");
            }
            $("#pageerror").hide();

            $.ajax({
                url: "../server/script.php",
                type: "POST",
                data: dataObj,
                success: function(data) {
                    if (data == 200) {
                        console.log(data);
                        $("#pageerror").html("Succesfully added");
                        $("#pageerror").addClass('alert-success');
                        $("#pageerror").show();
                        // window.location.href = "categoryindex.php"
                    } else if (data == 201) {
                        $("#pageerror").html("This key name already added");
                        $("#pageerror").addClass('alert-danger');
                        $("#pageerror").show();
                    } else if (data == 202) {
                        $("#pageerror").show();
                        $("#pageerror").addClass('alert-danger');
                        $("#pageerror").html("Invalid Data");
                    } else if (data == 1) {
                        $("#pageerror").show();
                        $("#pageerror").addClass('alert-success');
                        $("#pageerror").html("Succesfully Updated");
                    }
                },
                pageerror: function(e) {
                    console.log(e);
                }
            });
        });

        <?php
        if (isset($_GET['id'])) {
        ?>
            $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {
                        'type': 'getSettingById',
                        'id': <?php echo (int) @$_GET['id'] ?>
                    }
                })
                .done(function(data) {
                    if (data) {
                        data = JSON.parse(data);
                        $('#subject').val(data.subject);
                        $('#description').val(data.description);
                        $('#key_name').val(data.key_name);
                        $('#key_value').val(data.key_value);
                        // $('#status').html(data.status == 0 ? '<option value="0" selected>Inactive</option><option value="1">Active</option>' : '<option value="1" selected>Active</option><option value="0">Inactive</option>')
                        var opts = {
                            language: 'en',
                            pathPrefix: 'locales',
                            loadBase: true
                        };
                        $('[data-localize]').localize('dashboard', opts);
                    }
                })
                .fail(function(e) {
                    console.log(e);
                });
        <?php
        }
        ?>
    });
</script>
<?php
include_once 'footer.php';
?>