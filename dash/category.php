<?php
include_once 'header.php';
?>
<?php if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) { ?>
    <div class="row">
        <div class="form-group col-md-offset-2 col-md-8">
            <div>
                <h3>Add Category</h3>
            </div>
            <div class="form-group">
                <a href="categoryindex.php">
                    <button class="btn btn-primary ">View Categories</button>
                </a>
            </div>
            <div id="pageerror" class="alert" style="display:none;">
                <ul>
                    <li></li>
                </ul>
            </div><br />
            <form method="post">
                <div class="form-group">
                    <label for="category_name">Name</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Enter Category" required="true" oninput="spaceValidate(this)" >
                </div>
                <div class="form-group">
                    <label for="minimum_score">Minimum Score</label>
                    <input type="number" class="form-control" id="minimum_score" name="minimum_score" placeholder="Enter Minimum Score" required="true" oninput="spaceValidate(this)" >
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
     // script for not allowing whitespaces in the beginning of the chat message     
     function spaceValidate(input){
        if(/^\s/.test(input.value)){
            input.value = '';
        }
        // minimum score not less than 0
        var minimum_score = Number(input.value);
        if(minimum_score <= -1){
            $("#minimum_score").val(0);
        }

    }

    jQuery(document).ready(function($) {
       
        $('#submitbutton').click(function(event) {
            event.preventDefault();
            <?php
            if (isset($_GET['id'])) {
            ?>

                var dataObj = {
                    'type': 'editcategory',
                    'id': <?php echo $_GET['id']; ?>,
                    'category_name': $('#category_name').val(),
                    'minimum_score': $('#minimum_score').val()
                };
            <?php
            } else {
            ?>
                var dataObj = {
                    'type': 'addcategory',
                    'category_name': $('#category_name').val(),
                    'minimum_score': $('#minimum_score').val()
                };
            <?php
            }
            ?>
            if ($('#category_name').val() == '' || $('#minimum_score').val() == '') {
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
                        $("#pageerror").removeClass('alert-danger');
                        $("#pageerror").show();
                        // window.location.href = "categoryindex.php"
                    } else if (data == 201) {
                        $("#pageerror").html("This category already added");
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
                        'type': 'getCategoryById',
                        'id': <?php echo (int) @$_GET['id'] ?>
                    }
                })
                .done(function(data) {
                    if (data) {
                        data = JSON.parse(data);
                        $('#category_name').val(data.name);
                        $('#minimum_score').val(data.minimum_score);
                        $('#status').html(data.status == 0 ? '<option value="0" selected>Inactive</option><option value="1">Active</option>' : '<option value="1" selected>Active</option><option value="0">Inactive</option>')
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