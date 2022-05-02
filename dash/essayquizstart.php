<?php
include_once 'header.php';
include_once '../server/inline_connect.php';
?>

<h1 class="h3 mb-2 text-gray-800" id="agentTitle" data-localize="agent"></h1>
<div id="error" style="display:none;" class="alert alert-danger"></div>

<body onload="hidder();">
  <div class="text-center">
    <button class="button" id="mybut" onclick="myFunction()">START QUIZ</button>
  </div>
  <div id="myDIV" style="padding: 10px 30px;">
    <form action="essayquizresult.php" method="post" id="form">
      <input type="hidden" name="category" readonly value="<?php echo $_GET['category'];?>">
      <table id="chats_table" width="100%" cellspacing="0">
        <tr>
          <!-- //data append from footer -->
        </tr>
      </table>
      <table width="100%" cellspacing="0">
        <tr>
          <td align="center">
            <button class="button3" name="click">Submit Quiz</button>
          </td>
        </tr>
      </table>
    </form>


  </div>
  <script src="../ws/server/node_modules/ckeditor4/ckeditor.js"></script>
  <!-- <script>
    // Using ES6 imports:
    var editor = CKEDITOR.replace('question_text');
    // The "change" event is fired whenever a change is made in the editor.
    editor.on('change', function(evt) {
      // getData() returns CKEditor's HTML content.
      $('#question_text').val(editor.getData());
      // console.log($('#question_text').val());
    });
  </script> -->
  <script>
    function myFunction() {
      var x = document.getElementById("myDIV");
      var b = document.getElementById("mybut");
      var x = document.getElementById("myDIV");
      if (x.style.display === "none") {
        b.style.visibility = 'hidden';
        x.style.display = "block";
        // startTimer();
      }
    }
    window.onload = function() {
      document.getElementById('myDIV').style.display = 'none';
    };
  </script>
  <?php $fetchtime = "SELECT `timer` FROM `lsv_quiz` WHERE id=1";
  $fetched = mysqli_query($con, $fetchtime);
  $time = mysqli_fetch_array($fetched, MYSQLI_ASSOC);
  $settime = $time['timer'];
  ?>


  <script>
    window.onscroll = function() {
      // myFun()
    };

    var navbar = document.getElementById("navbar");
    // var sticky = navbar.offsetTop - 50;

    function myFun() {
      if (window.pageYOffset >= sticky) {
        navbar.classList.add("sticky")
      } else {
        navbar.classList.remove("sticky");
      }
    }
  </script>
</body>

<?php
//  }
?>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script>
  $('#submitbutton').click(function(event) {
    event.preventDefault();
    $("#error").hide();
    var dataObj = {
      'type': 'addQuiz',
      'question': $('#question').val(),
      'correct_answer': $('#correct_answer').val(),
      'wrong_answer1': $('#wrong_answer1').val(),
      'wrong_answer2': $('#wrong_answer2').val(),
      'wrong_answer3': $('#wrong_answer3').val()
    };
    $.ajax({
      url: "../server/script.php",
      type: "POST",
      data: dataObj,
      success: function(data) {
        if (data) {
          console.log(data);
          window.location.href = "quizindex.php"
        } else {
          $("#error").show();
          $("#error").html("Invalid Data");
        }
      },
      error: function(e) {
        console.log(e);
      }
    });
  });

  $('#submittimerbutton').click(function(event) {
    event.preventDefault();
    $("#error").hide();
    var dataObj = {
      'type': 'addTimer',
      'min': $('#min').val(),
      'sec': $('#sec').val()
    };
    $.ajax({
      url: "../server/script.php",
      type: "POST",
      data: dataObj,
      success: function(data) {
        if (data) {
          console.log(data);
          window.location.href = "quizindex.php"
        } else {
          $("#error").show();
          $("#error").html("Invalid Data");
        }
      },
      error: function(e) {
        console.log(e);
      }
    });
  });
</script>

<?php
include_once 'footer.php';
?>