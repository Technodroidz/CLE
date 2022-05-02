<?php
include_once 'header.php';
// include_once '../server/saveuploadedfile.php';
?>
<style type="text/css">
    body {
        background: #f2f2f2;
    }

    .page-container {
        width: 50%;
        margin: 5% auto 0 auto;
    }

    .form-container {
        padding: 30px;
        border: 1px solid #cccc;
        background: #FEFEFE;
    }

    .error,
    .success {
        font-size: 18px;
    }

    .error {
        color: #b30000;
    }

    .success {
        color: #155724;
    }

    .download-zip {
        color: #000000;
    }
</style>
<body>
<h1 class="h3 mb-2 text-gray-800" id="userTitle" data-localize="uploadform">Import Essay Type Quiz</h1>

<div id="error" style="display:none;" class="alert alert-danger"></div>
<div id="success" style="display:none;" class="alert alert-success"></div>
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
            
            <div class="float-right" style="margin-right:20px;">
                <!-- <a href="importform.php" class=""> -->
                <a href="../server/downloads/essay_type_quiz_sample_list.csv" download data-localize="download" target="_blank" title="Please download sample file for importing format">Download Essay Type Quiz Sample CSV
                    <i class="fas fa-download fa-2x text-300"></i>
                </a>
            </div>
            
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="page-container row-12">
                        <h4 class="col-12 text-center mb-5">Create Essay Type Quiz By Importing CSV File </h4>
                        <div class="row-8 form-container">
                            <?php
                            if (!empty($error)) {
                            ?>
                                <p class="error text-center"><?php echo $error; ?></p>
                            <?php
                            }
                            ?>
                            <?php
                            if (!empty($success)) {
                            ?>
                                <p class="success text-center">
                                    Files imported successfully 
                                </p>
                            <?php
                            }
                            ?>
                            <!--  $serverfolder_path. taken from header file -->
                            <?php if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) { ?>    
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <input class="btn btn-primary" type="submit" value="Import" id="submit" name="Import">
                                        </div>
                                        <div class="custom-file">
                                            <!-- upload type value save here -->
                                            <input type="text" class="custom-file-input" name="upload_type" value="form_upload">
                                            <!-- get tenant value for user or agent finding  -->
                                            <input type="text" class="custom-file-input" name="tenant" value="<?php echo $_SESSION["tenant"]; ?>">
                                            <input id="csv_file" type="file" class="custom-file-input" name="file" multiple required>
                                            <label id="input_file" class="custom-file-label">Please Select CSV Format File</label>
                                        </div>
                                    </div>
                                </form>
                            <?php }else{
                                echo "<h3>You are not authorised user.</h1>";
                            }?>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $("input:file").change(function() {
                var fileName = $(this).val();
                var fileExt = fileName.split('.').pop();
                if(fileExt != "csv"){
                    alert("Invalid File:Please Upload CSV File.");
                    window.location.href = "importform.php";
                }else{
                    $("#input_file").html('File Selected Successfully');
                    $("#input_file").css("background-color", "lightgreen");
                }
            });
            <?php   if (isset($_POST["Import"]) == "Import") { 
                        $file_ext =  pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
                        if($file_ext != "csv"){
                            return header("Location: 'importform.php'?message=$error");
                        }
                        $file = fopen($_FILES["file"]["tmp_name"], "r");
                        $getData = fgetcsv($file, 10000, ",");
                        $data_array = [];
                        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
                            $data_array[] = $getData;
                        }
                        fclose($file);
             ?>
                    var final_data = '<?php echo json_encode($data_array); ?>';
                    var size = '<?php echo json_encode($_FILES["file"]['size']); ?>';
                    $.ajax({
                            type: 'POST',
                            url: '../server/script.php',
                            data: {
                                type: 'importEssayTypeQuiz',
                                final_data: final_data,
                                filesize: size,
                                upload_type: 'importCsv'
                            }
                        })
                        .done(function(data) {
                           // console.log(data);
                            //alert(data);
                                <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
                                    if (data == 1) {   
                                    alert('successfully added question' );
                                        window.location.href = 'essaytypequizindex.php';
                                    }else if(data == 2){
                                        alert('There are some duplicate questions please check once.');   
                                    }else{
                                        alert('" '+data+ ' " category is not available. Please add in category list first.');   
                                        window.location.href = 'categoryindex.php';
                                    }   
                                <?php } else { ?>
                                    window.location.href = 'essaytypequizindex.php';
                                <?php } ?>
                            
                        })
                        .fail(function(e) {
                            console.log(e);
                        });
            <?php   } ?>

        });
    </script>
</body>

</html>
<?php
include_once 'footer.php';
