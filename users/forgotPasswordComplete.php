<?php
require_once('./functions.php');
checkIfUserLoggedIn($handler = false);
$csrfForgotPasswordToken = password_hash(md5(uniqid(mt_rand(), true)) . 's#0pK!@(S*&-K!', PASSWORD_BCRYPT);
$_SESSION['csrf-forgot-password-token'] = $csrfForgotPasswordToken;
$_GET = array_map('trim', $_GET);
$_GET = array_map('strip_tags', $_GET);
if (isset($_GET['x']) && isset($_GET['y'])) {
    $memberId = $_GET['x'];
    $activationKey = $_GET['y'];
    //update users record set the activation column to YES where the email and key value match the ones provided in the array
    $stmt = $db->prepare("SELECT * FROM admin WHERE memberId = :memberId AND activationKey = :activationKey AND activationResetTime >= NOW() - INTERVAL " . ACTIVATION_TIME_DIFFERENCE . " MINUTE");
    $stmt->execute(array(
        ':memberId' => $memberId,
        ':activationKey' => $activationKey
    ));
    //if the details are correct
    if ($stmt->rowCount() == 0) {
        //redirect to login page with error
        header('Location: ./login.php?response=invalidforgotpassword');
        exit;
    }
} else {
    //redirect to login page with error
    header('Location: ./login.php?response=invalidforgotpassword');
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Travel The World | Reset Password </title>
    <?php head(); ?>
    <script src="https://www.google.com/recaptcha/api.js" nonce="<?php echo $nonce; ?>" async defer></script>
</head>

<body class="hold-transition login-page registerBg">
    <div class="login-box">
        <div class="login-logo">
            <a href="../index.php"><b>Travel The World</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg text-bold">Enter your new password.</p>
                <form id="forgotPasswordCompleteForm" name='forgotPasswordCompleteForm' action="./phpHandler/forgotPasswordCompleteHandler.php" method="POST">
                    <div class="input-group mb-3">
                        <input type="hidden" name="x" value="<?php echo $memberId; ?>" />
                        <input type="hidden" name="y" value="<?php echo $activationKey; ?>" />
                        <input type="password" class="form-control" placeholder="Enter your password" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Confirm your password" name="confrmPassword" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div id="recaptcha" class="g-recaptcha" data-sitekey="6LcZOegUAAAAAIOgFelCh_0OMwryVCsPjMQSMPKI"></div>
                        <input type="hidden" value="<?php echo $csrfForgotPasswordToken; ?>" name="cToken" />
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Save Password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <div class="mb-3">
                </div>
                <p class="mb-0 text-center">
                    <a href="login.php">Go to Login &rightarrow;</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <?php bodyJs(); ?>
</body>

</html>