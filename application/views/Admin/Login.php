<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin Login</title>


    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!------ Include the above in your HEAD tag ---------->
  </head>
  <body>
    <div id="login">
        <div class="container" style="margin-top:200px">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6" >
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="<?php echo base_url("admin/doLogin"); ?>" method="post">
                            <h3 class="text-center text-info">Login</h3>
                            <?php
                            if ($error = $this->session->flashdata('Login_failed')):?>
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="alert alert-danger">
                                    <?= $error; ?>
                                </div>
                              </div>
                            </div>
                            <?php endif ?>
                            <div class="form-group">
                                <label for="username" class="text-info">Username:</label><br>
                                <input type="text" class="form-control" id="uname" placeholder="Enter email" value="<?php echo set_value('uname'); ?>" name="uname">
                            </div>
                            <div class="col-lg-6" style="margin-top:5px">
                            <?php echo form_error('uname','<div class="text-danger">','</div>') ?>
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">Password:</label><br>
                                <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd" >
                            </div>
                            <div class="col-lg-6" style="margin-top:5px">
                            <?php echo form_error('pwd','<div class="text-danger">','</div>') ?>
                            </div>

                            <div class="form-group">
                                <a href="#" id="forgot_password" onclick="return getConfirmed();" class="stretched-link">Forgot Password?</a>
                                <button type="submit" id="login_button" class="btn btn-info btn-md float-right" name="adminLogin" value="1">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function getConfirmed(){
            swal("An OTP will be sent to +9179******38. Are You sure?", {
            buttons: ["No", "Send OTP"],
        }).then(function(inputValue){
           if(inputValue){
               window.location = "<?=base_url('Admin/forgot_password')?>";
           }
        });
        }
    </script>
</body>
 
</html>
