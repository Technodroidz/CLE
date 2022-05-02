<?php
include_once 'header.php';
include_once '../server/inline_connect.php';
?>

<h1 class="h3 mb-2 text-gray-800" id="agentTitle" data-localize="agent_result">Agent Result</h1>
<div id="error" style="display:none;" class="alert alert-danger"></div>
<?php
//  if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) { 
?>

<?php

    $category = $_POST['category'];
    $_SESSION['score'] = 0;
    $fetchqry = "SELECT * FROM `lsv_quiz`  WHERE `category`='$category';";
    $result = mysqli_query($con, $fetchqry);
    $num = mysqli_num_rows($result);
    $i = 1;
  
foreach($_POST as $key => $value){
    if($key != 'category' && $key != 'click'){
        @$userselected = $_POST[$key];
        $fetchqry2 = "UPDATE `lsv_quiz` SET `userans`='$userselected' WHERE `id`=$key";
        mysqli_query($con, $fetchqry2);
    }
}    

  $qry3 = "SELECT `ans`, `userans` FROM `lsv_quiz` WHERE `category`='$category';";
  $result3 = mysqli_query($con, $qry3);
  while ($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
    if ($row3['ans'] == $row3['userans']) {
      @$_SESSION['score'] += 1;
    }
  }

  ?>
  <div class="col-md-offset-2 col-md-8">
    <br>
    <span><b>No. of Correct Answer:</b>&nbsp;<?php echo $no = @$_SESSION['score'];
                                              //var_dump($_SESSION['ids']);
                                              //session_unset(); 
                                              ?></span><br><br>
    <span><b>Your Score:</b>&nbsp<?php if (isset($no)) echo $no * 2; ?></span>
  </div>

<?php
//  } ?>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script>
        $('#submitbutton').click(function (event) {
            event.preventDefault();
            $("#error").hide();
            var dataObj = {'type': 'addQuiz', 'question': $('#question').val(), 'correct_answer': $('#correct_answer').val(), 'wrong_answer1': $('#wrong_answer1').val(), 'wrong_answer2': $('#wrong_answer2').val(), 'wrong_answer3': $('#wrong_answer3').val()};
            $.ajax({
                url: "../server/script.php",
                type: "POST",
                data: dataObj,
                success: function (data) {
                    if (data) {
                        // console.log(data);
                        window.location.href = "quizindex.php"
                    } else {
                        $("#error").show();
                        $("#error").html("Invalid Data");
                    }
                },
                error: function (e) {
                    console.log(e);
                }
            });
        });

        $('#submittimerbutton').click(function (event) {
            event.preventDefault();
            $("#error").hide();
            var dataObj = {'type': 'addTimer', 'min': $('#min').val(), 'sec': $('#sec').val()};
            $.ajax({
                url: "../server/script.php",
                type: "POST",
                data: dataObj,
                success: function (data) {
                    if (data) {
                        console.log(data);
                        window.location.href = "quizindex.php"
                    } else {
                        $("#error").show();
                        $("#error").html("Invalid Data");
                    }
                },
                error: function (e) {
                    console.log(e);
                }
            });
        });
    </script>
    
<?php
include_once 'footer.php';
?>