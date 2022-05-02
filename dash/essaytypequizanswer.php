<?php
include_once 'header.php';
?>
<?php if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) { ?>
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <h3>Update Marks Manually</h3>
            <form>
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="hidden" id="categoryValue">
                    <select class="form-control col-sm-4" name="category" id="category" readonly></select>
                </div>
                <div class="form-group">
                    <label for="questionname">Question name</label>
                    <input type="text" class="form-control col-sm-12" id="question_name" name="question_name" placeholder="Enter your question name here" readonly>
                </div>
                <div class="form-group">
                    <label for="questiontext">Question Text</label>
                    <textarea class="form-control ckeditor" id="question_text" name="question_text" placeholder="Enter the question text here" readonly ></textarea>
                </div>
                <div class="form-group">
                    <label for="quiz_answer">Answer</label>
                    <textarea class="form-control ckeditor" id="quiz_answer" name="quiz_answer" readonly></textarea>
                </div>
                <div class="form-group">
                    <label for="Gernal Feedback">Gernal Feedback</label>
                    <textarea class="form-control col-sm-8" id="gernal_feedback" name="gernal_feedback" placeholder="Gernal Feedback or hint for the question" readonly></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control col-sm-8" name="status" id="status" readonly>
                        <?php
                        if (!isset($_GET['quiz_essay_id'])) {
                            echo '<option value="1">Active</option>
                            <option value="0">Inactive</option>';
                        }
                        ?>

                    </select>
                </div>
                <div class="form-group">
                    <label for="wrong_answer1">Total Marks</label>
                    <input type="text" class="form-control col-sm-8 " id="default_marks" name="default_marks" placeholder="Total Marks of question" readonly>
                </div>
                <div class="form-group">
                    <label for="manual_marks">Manual Marks</label>
                    <input type="number" class="form-control col-sm-8 " id="manual_marks" name="manual_marks" placeholder="Please give Number for the answer given by user" oninput="manualMarks(this)" required>
                </div>
                <div class="form-group">
                    <button id="submitbutton" type="submit" class="btn btn-primary btn-large" value="submit" name="submit"><?php  echo $value = isset($_GET['quiz_essay_id'])? 'Update Marks': '+ Add Question'; ?></button>
                </div>


            </form>
        </div>
    </div>
<?php } ?>
<!-- Ckeditor  JavaScript-->
<script src="../ws/server/node_modules/ckeditor4/ckeditor.js"></script>
<script>
    // // Using ES6 imports:
    // var editor = CKEDITOR.replace('question_text');
    // // The "change" event is fired whenever a change is made in the editor.
    // editor.on('change', function(evt) {
    //     // getData() returns CKEditor's HTML content.
    //     $('#question_text').val(editor.getData());
    //     console.log($('#question_text').val());
    // });
</script>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<!-- Custom JavaScripts -->
<script>
    // manual marks has to be less than total marks 
    function manualMarks(t){
        var max_marks = Number($('#default_marks').val());
        var manual_value = Number(t.value);
       
            if(manual_value >= max_marks){
                $("#manual_marks").val(max_marks);
            }
            if(manual_value <= -1){
                $("#manual_marks").val(0);
            }
    }
    
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