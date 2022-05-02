<?php
include_once 'header.php';
include_once '../server/inline_connect.php';
?>

<h1 class="h3 mb-2 text-gray-800" id="agentTitle" data-localize="agent"></h1>
<div id="error" style="display:none;" class="alert alert-danger"></div>
<?php
//  if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) {
   ?>
    <body onload="hidder();">
    <div class="text-center">
      <!-- <div class="time" id="navbar">Time left :<span id="timer"></span></div> -->
      <button class="button" id="mybut" onclick="myFunction()">START QUIZ</button>
    </div>
    <div id="myDIV" style="padding: 10px 30px;">
    
      <!-- <form action="quizresult.php" method="post" id="form">
        <table  id="chats_table" width="100%" cellspacing="0">
        <tr>
            <td align="center">
              <button class="button3" name="click">Submit Quiz</button>
            </td>
          </tr>
        </table>
      </form> -->
     
      <form action="quizresult.php" method="post" id="form">
        <input type="hidden" name="category" readonly value="<?php echo $_GET['category'];?>">
        <table>
         
          <?php $fetchqry = "SELECT * FROM `lsv_quiz` WHERE category = '$_GET[category]' AND `status` = 0";
          $result = mysqli_query($con, $fetchqry);
          $num = mysqli_num_rows($result);

          while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
          ?>
            <tr>
              <td>
                <h3><br><?php echo @$snr += 1; ?>&nbsp;-&nbsp;<?php echo @$row['que']; ?></h3>
              </td>
            </tr>
            <tr>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;a )&nbsp;&nbsp;&nbsp;<input required type="radio" name="<?php echo $row['id']; ?>" value="<?php echo $row['option1']; ?>">&nbsp;<?php echo $row['option1']; ?><br>
            <tr>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;b )&nbsp;&nbsp;&nbsp;<input required type="radio" name="<?php echo $row['id']; ?>" value="<?php echo $row['option2']; ?>">&nbsp;<?php echo $row['option2']; ?></td>
            </tr>
            <tr>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;c )&nbsp;&nbsp;&nbsp;<input required type="radio" name="<?php echo $row['id']; ?>" value="<?php echo $row['option3']; ?>">&nbsp;<?php echo $row['option3']; ?></td>
            </tr>
            <tr>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;d )&nbsp;&nbsp;&nbsp;<input required type="radio" name="<?php echo $row['id']; ?>" value="<?php echo $row['option4']; ?>">&nbsp;<?php echo $row['option4']; ?><br><br><br></td>
            </tr>
          <?php  }
          ?>
          <tr>
            <td align="center">
              <button class="button3" name="click">Submit Quiz</button>
            </td>
          </tr>
        </table>
        <form>
    </div>
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
    <script type="text/javascript">
      // document.getElementById('timer').innerHTML = '<?php echo $settime; ?>';
      //03 + ":" + 00 ;


      // function startTimer() {
      //   var presentTime = document.getElementById('timer').innerHTML;
      //   var timeArray = presentTime.split(/[:]+/);
      //   var m = timeArray[0];
      //   var s = checkSecond((timeArray[1] - 1));
      //   if (s == 59) {
      //     m = m - 1
      //   }
      //   if (m == 0 && s == 0) {
      //     alert('stop it your time '+ presentTime +' completed')
      //     document.getElementById("form").submit();
      //   }
      //   document.getElementById('timer').innerHTML =
      //     m + ":" + s;
      //   setTimeout(startTimer, 1000);
      // }

      function checkSecond(sec) {
        if (sec < 10 && sec >= 0) {
          sec = "0" + sec
        }; // add zero in front of numbers < 10
        if (sec < 0) {
          sec = "59"
        };
        return sec;
        if (sec == 0 && m == 0) {
          alert('stop it')
        };
      }
    </script>

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