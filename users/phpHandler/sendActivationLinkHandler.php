<?php
include_once '../functions.php';
checkIfNotAjaxRequest();
checkIfUserLoggedIn($handler = true);
checkValidReferer();
csrfTokenVerify('csrf-activation-link-token');
$_POST = array_map('strip_tags', $_POST);
if (isset($_POST['g-recaptcha-response']) && isset($_POST['formUsername'])) {
    extract($_POST);
    $response = array();
    if (empty($formUsername) || empty($_POST['g-recaptcha-response'])) {
        sendAjaxResponse(1, 'Please enter username along with captcha.');
    } elseif (!ctype_alnum($formUsername)) {
        sendAjaxResponse(1, 'Invalid username format. Username can only have alphanumeric characters.');
    } else {
        $captchaResponse = $_POST["g-recaptcha-response"];
        $captchaSecret = "6LcZOegUAAAAAC506ODzOviR_cqMNWTNGXXRndcu";
        $captchaVerify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$captchaSecret}&response={$captchaResponse}");
        $captchaVerify = json_decode($captchaVerify);
        if ($captchaVerify->success != true) {
            sendAjaxResponse(1, 'Captcha error occurred. Please refresh and try again.');
        }
        try {
            $formUsername = htmlspecialchars($formUsername);
            $ktmt = $db->prepare("SELECT memberId, emailId, activationStatus FROM admin where username = ?");
            $ktmt->execute([$formUsername]);
            $row = $ktmt->fetch();
            if (!$row['memberId']) {
                sendAjaxResponse(0, 'An account activation link has been sent to your associated email.');
            } else if ($row['activationStatus']) {
                sendAjaxResponse(0, 'Your account is already activated. You can proceed with login.');
            } else {
                $memberId = $row['memberId'];
                $activationKey = hash('sha256', uniqid(rand(), true));
                $ktmt = $db->prepare("UPDATE admin SET activationKey = ?, activationResetTime = NOW() WHERE memberId = ?");
                if ($ktmt->execute([$activationKey, $memberId])) {
                    $to = $row['emailId'];
                    $subject = "Verify your account | Travel The World";
                    $body = "Thank you for registering at Travel The World. <br><br> Kindly confirm your account by clicking following link <br> <a href='" . SITE_ADDR . "/users/activateAccount.php?x=" . $memberId . "&y=" . $activationKey . "'>Activate my account</a> <br><br> Regards, <br> Site Admin, <br> Smriti Dangwal, <br>Travel The World";
                    sendMail($to, $subject, $body);
                    sendAjaxResponse(0, 'An account activation link has been sent to your associated email.');
                } else {
                    sendAjaxResponse(1, 'Error occurred while processing your request. Please try again.');
                }
            }
        } catch (Exception $e) {
            sendAjaxResponse(-1, $e->getMessage(), $e->getCode());
        }
    }
} else {
    sendAjaxResponse(1, 'All required parameters not found. Refresh your page and try again.');
}
