<?php
include_once 'header.php';
include_once '../server/inline_connect.php';
?>
<h1 class="h3 mb-2 text-gray-800" id="agentTitle" data-localize="agent_result">Visitor Result</h1>
<div id="error" style="display:none;" class="alert alert-danger"></div>
<?php
//  if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) { 
?>

<?php
$user_id = $_SESSION['agent']['user_id'];
$category = $_POST['category'];
$_SESSION['quiz_correct_answer'] = 0;
$_SESSION['quiz_total_questions'] = 0;

$fetchqry = "SELECT * FROM `lsv_quiz`";
$result = mysqli_query($con, $fetchqry);
$num = mysqli_num_rows($result);
$i = 1;

foreach($_POST as $key => $value){
  if($key != 'category' && $key != 'click' && $key != 'roomId'){
      @$userselected = $_POST[$key];
      $fetchqry2 = "UPDATE `lsv_quiz` SET `userans`='$userselected', `user_id`=$user_id, `category`= '$category' WHERE `id`=$key";
     
      mysqli_query($con, $fetchqry2);
    //  die;
  }
} 

// for ($i; $i <= $num; $i++) {
//   @$userselected = $_POST[$i];
// //   var_dump($_POST);
// // die;
//   $fetchqry2 = "UPDATE `lsv_quiz` SET `userans`='$userselected' WHERE `id`=$i";
//   mysqli_query($con, $fetchqry2);
// }

$qry3 = "SELECT `ans`, `userans` FROM `lsv_quiz` WHERE `category`='$category' AND `user_id`='$user_id' AND `status`  = 1;";
$result3 = mysqli_query($con, $qry3);
$count = 0;
// var_dump($result3);
// die;
while ($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
  $count++;
  if ($row3['ans'] == $row3['userans']) {
    @$_SESSION['quiz_correct_answer'] += 1;
  }
}
// var_dump($count);
// die;
//quiz total questions
@$_SESSION['quiz_total_questions'] =  $count;

?>
<div class="col-md-offset-2 col-md-8">
  <br>
  <?php $fetchqry = "SELECT * FROM `lsv_visitorquiz_report` ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($con, $fetchqry);
    $num = mysqli_num_rows($result);
  ?>
  <?php
  
    $total_no_ques = @$_SESSION['quiz_total_questions']; 
    $correct_ans = @$_SESSION['quiz_correct_answer'];
    $out_of_score = $total_no_ques*2;
    $your_score = $out_of_score*2;
  ?>

  <!-- //these variables are for quizreport update  -->
  <input id="quiz_total_score" type="hidden" readonly value=<?php echo @$_SESSION['quiz_total_questions'] * 2; ?>>
  <input id="quiz_your_score" type="hidden" readonly value=<?php echo @$_SESSION['quiz_correct_answer'] * 2; ?>>

  <span><b>Total No. of questions:</b>&nbsp;<?php echo $total_no_ques = @$_SESSION['quiz_total_questions']; ?></span><br>
  <span><b>No. of Correct Answer:</b>&nbsp;<?php echo $no = @$_SESSION['quiz_correct_answer']; ?></span><br><br>
  <span><b>Out of Score:</b>&nbsp<?php if (isset($total_no_ques)) echo $total_no_ques * 2; ?></span><br>
  <span><b>Your Score:</b>&nbsp<?php if (isset($no)) echo $no * 2; ?></span><br><br><br>
  <span><a href="dash.php"><button>Go Home</button></a></span><br>

  <p id="total_score"></p>
</div>

<?php
//  } 
?>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>


<?php
include_once 'footer.php';
?>