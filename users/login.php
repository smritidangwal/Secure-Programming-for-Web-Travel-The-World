<?php
require_once('./functions.php');
checkIfUserLoggedIn($handler = false);
$csrfLoginToken = password_hash(md5(uniqid(mt_rand(), true)) . 's#0pK!@(S*&-K!', PASSWORD_BCRYPT);
$_SESSION['csrf-login-token'] = $csrfLoginToken;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Travel The World | Log in</title>
    <?php head(); ?>
</head>

<body class="hold-transition login-page loginBg">
    <div class="login-box">
        <div class="login-logo">
            <a href="../index.php"><b>Travel The World</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form name="loginForm" id="loginForm" autocomplete="off" action="./phpHandler/loginHandler.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" name="formUsername" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="formPassword" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div id="recaptcha" class="g-recaptcha" data-sitekey="6LcZOegUAAAAAIOgFelCh_0OMwryVCsPjMQSMPKI"></div>
                        <input type="hidden" value="<?php echo $csrfLoginToken; ?>" name="cToken" />
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="submit" id="loginSubmit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <div class="mb-3">
                </div>
                <p class="m-0 text-center">
                    <a href="forgotPassword.php">Forgot your password? &rightarrow;</a>
                </p>
                <p class="m-1 text-center">
                    <a href="sendActivationLink.php">Need Activation link? &rightarrow;</a>
                </p>
                <p class="m-0 text-center">
                    <a href="userRegistration.php">Create your own account &rightarrow;</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <?php bodyJs(); ?>
    <script src="https://www.google.com/recaptcha/api.js?render=explicit" nonce="<?php echo $nonce; ?>" async defer>
    </script>
    <script nonce="<?php echo $nonce; ?>">
        function captchaCallback(response) {
            if (response.length > 0)
                $("#loginSubmit").prop('disabled', false);
        };
        var url = new URL(window.location);
        var response = url.searchParams.get("response");
        toastr.options.progressBar = true;
        toastr.options.closeButton = true;
        if (response) {
            if (response == "activated")
                toastr.success('Your account has been activated. You can proceed with login.');
            if (response == "invalidactivation")
                toastr.error('Invalid/expired activation request found. Please try again.');
            if (response == "invalidforgotpassword")
                toastr.error('Invalid/expired reset password request found. Please try again.');
            if (response == "error")
                toastr.error('Some error occurred. Please try again.');
        }
    </script>
</body>

</html>