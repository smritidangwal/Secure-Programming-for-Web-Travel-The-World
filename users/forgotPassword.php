<?php
require_once('./functions.php');
checkIfUserLoggedIn($handler = false);
$csrfForgotPasswordToken = password_hash(md5(uniqid(mt_rand(), true)) . 's#0pK!@(S*&-K!', PASSWORD_BCRYPT);
$_SESSION['csrf-forgot-password-token'] = $csrfForgotPasswordToken;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Travel The World | Forgot Password</title>
    <?php head(); ?>
    <script src="https://www.google.com/recaptcha/api.js" nonce="<?php echo $nonce; ?>" async defer></script>
</head>

<body class="hold-transition login-page loginBg">
    <div class="login-box">
        <div class="login-logo">
            <a href="../index.php"><b>Travel The World</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg text-bold">Enter username to reset password</p>

                <form name="forgotPasswordForm" id="forgotPasswordForm" action="./phpHandler/forgotPasswordHandler.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" name="formUsername" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div id="recaptcha" class="g-recaptcha" data-sitekey="6LcZOegUAAAAAIOgFelCh_0OMwryVCsPjMQSMPKI"></div>
                        <input type="hidden" value="<?php echo $csrfForgotPasswordToken; ?>" name="cToken" />
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="submit" id="forgotPasswordSubmit" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <div class="mb-3">
                </div>
                <p class="mb-0 text-center">
                    <a href="login.php">Back to Login &rightarrow;</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <?php bodyJs(); ?>
</body>

</html>