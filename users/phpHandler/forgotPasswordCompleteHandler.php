<?php
include_once '../functions.php';
checkIfNotAjaxRequest();
checkIfUserLoggedIn($handler = true);
checkValidReferer();
csrfTokenVerify('csrf-forgot-password-token');
$_POST = array_map('strip_tags', $_POST);
if (isset($_POST['x']) && isset($_POST['y']) && isset($_POST['password']) && isset($_POST['confrmPassword']) && isset($_POST['g-recaptcha-response'])) {
    extract($_POST);
    $response = array();
    if (empty($x) || empty($y) || empty($password) || empty($confrmPassword) || empty($_POST['g-recaptcha-response'])) {
        sendAjaxResponse(1, 'Please enter all required values along with captcha.');
    } elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*~\'\";:\?\\|\/.,_+-]).{8,}$/", $password)) {
        sendAjaxResponse(1, 'Invalid password format. It should have atleast one capital, one small, one number, one special character with minimum 8 characters.');
    } elseif ($password !== $confrmPassword) {
        sendAjaxResponse(1, 'Passwords do not match. Please try again.');
    } else {
        $captchaResponse = $_POST["g-recaptcha-response"];
        $captchaSecret = "6LcZOegUAAAAAC506ODzOviR_cqMNWTNGXXRndcu";
        $captchaVerify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$captchaSecret}&response={$captchaResponse}");
        $captchaVerify = json_decode($captchaVerify);
        if ($captchaVerify->success != true) {
            sendAjaxResponse(1, 'Captcha error occurred. Please refresh and try again.');
        }
        $memberId = $x;
        $activationKey = $y;
        try {
            $ktmt = $db->prepare("SELECT * FROM admin where memberId = ? AND activationKey = ? AND activationResetTime >= NOW() - INTERVAL " . ACTIVATION_TIME_DIFFERENCE . " MINUTE");
            $ktmt->execute([$memberId, $activationKey]);
            $user = $ktmt->fetch();
            if ($user['memberId']) {
                $passwordHash = password_hash($password, PASSWORD_ARGON2ID, $hashOptions);
                $stmt = $db->prepare('UPDATE admin SET password = ?, activationKey = NULL, lockStatus = 0, failedLoginAttempts = 0 WHERE memberId = ? AND activationKey = ?');
                if ($stmt->execute([$passwordHash, $memberId, $activationKey])) {
                    $sessionIp = $_SERVER['REMOTE_ADDR'];
                    $ktmt = $db->prepare('INSERT into userlog (userId, username, userIp, action) values (?, ?, ?, ?)');
                    $ktmt->execute([$user['memberId'], $user['username'], $sessionIp, "FORGOT PASSWORD"]);
                    $to = $user['emailId'];
                    $subject = "Account Password Changed | Travel The World";
                    $body = "Hi, <br> <br> Your account password has been changed. If it wasn't you, contact the site administrator at earliest on mail: " . SITE_EMAIL . " <br><br> Regards, <br> Site Admin, <br> Smriti Dangwal, <br> Travel The World";
                    sendMail($to, $subject, $body);
                    sendAjaxResponse(0, 'Password changed successfully. You will be redirected to login now.');
                } else {
                    sendAjaxResponse(1, 'Error occurred while processing your request. Please try again.');
                }
            } else {
                sendAjaxResponse(1, 'Invalid/expired forgot password request found. Please refresh and try again.');
            }
        } catch (PDOException $e) {
            sendAjaxResponse(-1, $e->getMessage(), $e->getCode());
        }
    }
} else {
    sendAjaxResponse(1, 'All required parameters not found. Refresh your page and try again.');
}
