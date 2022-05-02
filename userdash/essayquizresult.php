<?php
include_once 'header.php';
include_once '../server/inline_connect.php';
?>


<?php
//  if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) { 

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
    
    // sending data in post
    $answers= $_POST['answer_data'] = $arrayvar;
    $dbPrefix = 'lsv_';
    $user_id = $user=isset($_SESSION['agent']['user_id'])?$_SESSION['agent']['user_id'] : null ; $user;

    foreach($answers['formasd'] as $key => $value){
        
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'essay_type_quiz_answer WHERE quiz_essay_id='.$key.' AND user_id= '.$user_id.' ORDER BY id DESC');
        $result = $stmt->execute();
        $result = $stmt->fetch();
        // var_dump($result);
        if($result === false){
            $sql = "INSERT INTO " . $dbPrefix . "essay_type_quiz_answer (quiz_essay_id, quiz_answer, user_id)"
                . "VALUES (?, ?, ?)";
            $pdo->prepare($sql)->execute([$key, $value, $user_id]);
        }else{
            $sql1 = "UPDATE " . $dbPrefix . 'essay_type_quiz_answer SET quiz_answer=? WHERE quiz_essay_id=? AND user_id=?';
            $pdo->prepare($sql1)->execute([$value, $key, $user_id]);    
        }
        
      } 
    
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