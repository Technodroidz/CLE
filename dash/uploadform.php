<?php
include_once 'header.php';
// include_once '../server/saveuploadedfile.php';
?>

<h1 class="h3 mb-2 text-gray-800" id="userTitle" data-localize="uploadform">File's Upload</h1>
<div id="error" style="display:none;" class="alert alert-danger"></div>
<div id="success" style="display:none;" class="alert alert-success"></div>
<style type="text/css">
    body {
        background:#f2f2f2;
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
    .error,.success  {
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
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3"></div>
            <div class="card-body">
                <div class="row">
                        <div class="page-container row-12">
                            <h4 class="col-12 text-center mb-5">Create Archieve For Multiple Uploaded Files </h4>
                            <div class="row-8 form-container">
                                <?php 
                                if(!empty($error)) { 
                                ?>
                                    <p class="error text-center"><?php echo $error; ?></p>
                                <?php 
                                }
                                ?>
                                <?php 
                                if(!empty($success)) { 
                                ?>
                                    <p class="success text-center">
                                Files uploaded successfully and compressed into a zip format
                                </p>
                                <?php 
                                }
                                ?>
                                <?php
                                // $php_self taken from header file
                                    
                                ?>
                                <form action="<?= $serverfolder_path."/saveuploadedfile.php" ?>" method="post" enctype="multipart/form-data">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <input type="submit" class="btn btn-primary" value="Upload" >
                                        </div>
                                        <div class="custom-file">
                                            <input type="text" class="custom-file-input" name="upload_type" value="form_upload">
                                            <input type="text" class="custom-file-input" name="tenant" value="<?php echo $_SESSION["tenant"]; ?>">
                                            <input id="select_file" type="file" class="custom-file-input" name="file[]" multiple required>
                                            <label id="input_file" class="custom-file-label">Please select one or more files</label>
                                        </div>
                                    </div>
                                </form>
                                    
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
            $("input:file").change(function (){
                var fileName = $(this).val();
                $("#input_file").html('File Selected Successfully');
                $("#input_file").css("background-color", "lightgreen");
            });
            <?php if(isset($_GET['zipname'])){ ?>
                
                $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: {
                            type: 'uploads',
                            filename: '<?php echo $_GET["zipname"]; ?>',
                            email: '<?php echo $_SESSION['agent']["email"]; ?>',
                            room_id: 'null',
                            upload_type: 'form_upload'
                        }
                    })
                    .done(function(data) {
                        // console.log(data)
                        if (data) {
                            <?php if ($_SESSION["tenant"] == 'lsv_mastertenant'){?>
                             window.location.href= 'uploads.php';
                             <?php }else{?>
                             window.location.href= 'upload.php';
                             <?php } ?> 
                            // $('#success').val('File successfully uploaded').attr('style','display:block');
                        }
                    })
                    .fail(function(e) {
                        console.log(e);
                    });
            <?php } ?>        
        });
    </script>
    </body>
</html>
<?php
include_once 'footer.php';
