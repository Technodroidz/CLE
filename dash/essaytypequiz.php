
<?php
include_once 'header.php';

?>
<?php if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) { ?>
    <div class="row">
    <!-- <div id="pageerror" class="alert" style="display:none;"> -->
        
        <div class="col-md-offset-2 col-md-8">
            <h3>Add Essay Type Quiz</h3>
            <form method="post" name="Form">
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="hidden" id="categoryValue">
                    <select class="form-control col-sm-4" name="category" id="category" Required></select>
                </div>
                <div class="form-group ">
                    <label class="requiredred" for="questionname">Question</label>
                    <input type="text" class="form-control col-sm-4 required_error required" id="question_name" name="question_name" placeholder="Enter your question" Required>
                </div>
                <div class="form-group">
                    <label class="requiredred" for="questiontext">Question Description</label>
                    <textarea class="form-control" id="question_text" name="question_text" placeholder="Enter the question description here" Required></textarea>
                </div>
                <div class="form-group">
                    <label class="requiredred"  for="wrong_answer1">Marks</label>
                    <input type="text" class="form-control col-sm-3 " id="default_marks" name="default_marks" placeholder="Input Number" Required>
                </div>
                <div class="form-group">
                    <label for="Gernal Feedback">Gernal Feedback</label>
                    <textarea class="form-control col-sm-8" id="gernal_feedback" name="gernal_feedback" placeholder="Gernal Feedback or hint for the question"></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control col-sm-8" name="status" id="status">
                        <?php
                        if (!isset($_GET['quiz_essay_id'])) {
                            echo '<option value="1">Active</option>
                            <option value="0">Inactive</option>';
                        }
                        ?>

                    </select>
                </div>
                <div class="form-group">
                    <button id="submitbutton" type="submit" class="btn btn-primary btn-large" value="submit" name="submit"><?php  echo $value = isset($_GET['quiz_essay_id'])? 'Update Question': '+ Add Question'; ?></button>
                </div>


            </form>
        </div>
    </div>
<?php } ?>
<style>
  .requiredred:after {
    content:" *";
    color: red;
  }
</style>
<!-- Ckeditor  JavaScript-->
<script src="../ws/server/node_modules/ckeditor4/ckeditor.js"></script>
<script>

    // Using ES6 imports:
    var editor = CKEDITOR.replace('question_text');
    // The "change" event is fired whenever a change is made in the editor.
    editor.on('change', function(evt) {
        // getData() returns CKEditor's HTML content.
        $('#question_text').val(editor.getData());
        console.log($('#question_text').val());
    });
</script>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Custom JavaScripts -->
<script>
    // for category dropdown
    <?php
    if (!isset($_GET['quiz_essay_id'])) {
    ?>
        jQuery(document).ready(function($) {
            
            var dataObj = {
                'type': 'getCategorydropdown'
            };
            $.ajax({
                url: "../server/script.php",
                type: "POST",
                data: dataObj,
                success: function(data) {
                    var result = JSON.parse(data);
                    if (result) {
                        $.each(result, function(i, item) {
                            $('#category').append(
                                '<option value="' + item.name + '">' + item.name + '</option>',
                            );
                        });
                    } else {
                        $('#select_category').append(
                            $('#category').html('<option value=""></option>'),
                        );
                    }
                }
            })
        })
    <?php
    }
    ?>
</script>
<?php
include_once 'footer.php';
?>