<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Password Reset</title>


    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!------ Include the above in your HEAD tag ---------->
  </head>
  <body>
    <div class="form-gap" style="margin-top:5%"></div>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">

            <div class="panel panel-default">
              <div class="panel-body">
                <div class="text-center">
                  <h3><i class="fa fa-lock fa-4x"></i></h3>
                  <h2 class="text-center">Forgot Password?</h2>
                  <p><a href="<?=base_url("Admin/logout")?>"> Go Back To Login Page</a></p>
                  <p>You can reset your password here.</p>
                  <?php if ($msg = $this->session->flashdata('msg')):?>
        <?php if ($this->session->flashdata('msg_class')){$class = $this->session->flashdata('msg_class');} ?>
        <div class="alert alert-dismissible alert-<?=$class ?> ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><?php echo $msg; ?></strong>
        </div>
        <?php endif ?>
                  <p id="resendtxt" style="color:red"></p>
                  <div class="panel-body">

                    <form id="register-form" role="form"  class="form" method="post">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                          <input type="tel" name="otp" placeholder="••••••OTP" max="6"  class="form-control"  />
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                          <input type="password" name="new_password" placeholder="New Password"  max="6" class="form-control"  />
                        </div>
                      </div>
                      <div class="form-group">
                        <button type="submit" name="resetPassword" class="btn btn-lg btn-primary btn-block" value="1">Reset Password</button>
                      </div>

                      <input type="hidden" class="hide" name="token" id="token" value="">
                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
	</div>
</div>
<script>
    var timeLeft = 150;
var elem = document.getElementById('resendtxt');
var timerId = setInterval(countdown, 1000);

function countdown() {
    if (timeLeft == -1) {
        clearTimeout(timerId);
        doSomething();
    } else {
        elem.innerHTML = "Resend OTP after "+timeLeft + ' seconds.';
        timeLeft--;
    }
}

function doSomething() {
    document.getElementById('resendtxt').innerHTML='<form method="post" action="<?=base_url("Admin/forgot_password")?>"><input name="resend_otp"  class="btn btn-lg btn-success" value="Send" type="submit"></form>';
}
</script>
<script type="text/javascript">
if (document.getElementsByTagName) {
var inputElements = document.getElementsByTagName(“input”);

for (i=0; inputElements[i]; i++) {

if (inputElements[i].className && (inputElements[i].className.indexOf(“disableAutoComplete”) != -1)) {

inputElements[i].setAttribute(“autocomplete”,”off”);
}
}
}

</script>
</body>

</html>
