<?php
include_once 'header.php';
?>
<?php if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) {
    // dynamic text for add and update-----
    (!isset($_GET['id']))? $text= "+ ADD": $text= "Update"; 
?>
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <h1>
            <?=$text?> Multiple Choice Question Quiz</h1>
            <form action="" id="QuizForm" method="post">
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="hidden" id="categoryValue" readonly>
                    <select class="form-control col-sm-4" name="category" id="category" Required></select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control col-sm-4" name="status" id="status">
                        <?php
                        if (!isset($_GET['quiz_essay_id'])) {
                            echo '<option value="1">Active</option>
                            <option value="0">Inactive</option>';
                        } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="requiredred" for="question">Ask Question</label>
                    <input type="text" class="form-control " id="question" name="question" placeholder="Enter your question here" oninput="spaceValidate(this)" Required>
                </div>
                <div class="form-group">
                    <label class="requiredred" for="correct_answer">Correct answer</label>
                    <input type="text" class="form-control" id="correct_answer" name="correct_answer" placeholder="Enter the correct answer here" oninput="spaceValidate(this)" Required>
                </div>
                <div class="form-group">
                    <label class="requiredred" for="wrong_answer1">Wrong Answers</label>
                    <input type="text" class="form-control " id="wrong_answer1" name="wrong_answer1" placeholder="Wrong answer 1" oninput="spaceValidate(this)" Required>
                </div>
                <div class="form-group">
                    <label class="requiredred" class="sr-only" for="wrong_answer2">Wrong Answers 2</label>
                    <input type="text" class="form-control requiredred" id="wrong_answer2" name="wrong_answer2" placeholder="Wrong answer 2" oninput="spaceValidate(this)" Required>
                </div>
                <div class="form-group">
                    <label class="requiredred" class="sr-only" for="wrong_answer3">Wrong Answers 3</label>
                    <input type="text" class="form-control requiredred" id="wrong_answer3" name="wrong_answer3" placeholder="Wrong answer 3" oninput="spaceValidate(this)" Required>
                </div>
                <button id="submitbutton" type="submit" class="btn btn-primary btn-large" value="submit" name="submit"><?=$text?> Question</button>

            </form>
        </div>
    </div>

    <!-- <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <h2>Set New Timer</h2>
            <form action="" method="post">
                <div class="col-sm-3">
                    <label for="minute">Minutes</label>
                    <input type="digit" class="form-control input-group-lg reg_name" id="min" name="min" placeholder="Min" Required>
                </div>
                <div class="col-sm-3">
                    <label for="second">Seconds</label>
                    <input type="digit" class="form-control input-group-lg reg_name" id="sec" name="sec" placeholder="Sec" Required>
                </div><br>
                <button type="submit" id="submittimerbutton" class="btn btn-primary btn-large" value="submit" name="timesubmit">+Set Timer</button>
                <form>
        </div>
    </div> -->
<?php } ?>
<style>
  .requiredred:before {
    content:" *";
    color: red;
  }
</style>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    // for category dropdown in add quiz------
    // script for not allowing whitespaces in the beginning of the chat message     
    function spaceValidate(input){
    if(/^\s/.test(input.value))
        input.value = '';
    }
    jQuery(document).ready(function($) {
        <?php
            if (!isset($_GET['id'])) {
        ?>
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
            
        <?php
            } else {
        ?>
            //update quiz category ajax--------   
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
                            if (item.name == $('#categoryValue').val()) {
                                $('#category').append(
                                    '<option value="' + item.name + '" selected>' + item.name + '</option>',
                                );
                            } else {
                                $('#category').append(
                                    '<option value="' + item.name + '">' + item.name + '</option>',
                                );
                            }
                        });
                    } else {
                        $('#select_category').append(
                            $('#category').html('<option value=""></option>'),
                        );
                    }
                }
            })
        <?php
        }
        ?>
    });
</script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script>
    //this js is for setting timer if required to the client----------

    // $('#submittimerbutton').click(function(event) {
    //     event.preventDefault();
    //     $("#error").hide();
    //     var dataObj = {
    //         'type': 'addTimer',
    //         'min': $('#min').val(),
    //         'sec': $('#sec').val()
    //     };
    //     $.ajax({
    //         url: "../server/script.php",
    //         type: "POST",
    //         data: dataObj,
    //         success: function(data) {
    //             if (data) {
    //                 console.log(data);
    //                 window.location.href = "quizstart.php"
    //             } else {
    //                 $("#error").show();
    //                 $("#error").html("Invalid Data");
    //             }
    //         },
    //         error: function(e) {
    //             console.log(e);
    //         }
    //     });
    // });
</script>
<?php
include_once 'footer.php';
?>