<?php
include_once 'header.php';
include_once '../server/inline_connect.php';
?>

<h1 class="h3 mb-2 text-gray-800" id="agentTitle" data-localize="agent_result">Status</h1>
<div id="error" style="display:none;" class="alert alert-danger"></div>
<?php
//  if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) { 
?>

<?php
    // $category = $_POST['category'];
    // $_SESSION['score'] = 0;
    // $fetchqry = "SELECT * FROM `lsv_essay_quiz`  WHERE `category`='$category';";
    // $result = mysqli_query($con, $fetchqry);
    // $num = mysqli_num_rows($result);
    // $i = 1;
    
    $answers = [];
    $arrayvar = [];
    foreach($_POST as $key => $value){
        if($key !='category' && $key !='click')
        $answers[$key] = $value;
        $arrayvar['formasd'] = $answers;
    }
    // var_dump($arrayvar);
    // die;
    // sending data in post
    $answers= $_POST['answer_data'] = $arrayvar;
    
?>
<div>
    <p>Your anwsers are submitted successfully and reviewd by teacher as soon as possible</p>
</div>
  <table>
    <tr>
        <td></td><td></td>
    </tr>
</table>

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