<?php
include_once '../functions.php';
checkIfNotAjaxRequest();
checkIfUserLoggedIn($handler = true);
checkValidReferer();
csrfTokenVerify('csrf-register-token');
$_POST = array_map('strip_tags', $_POST);
if (isset($_POST['fName']) && isset($_POST['lName']) && isset($_POST['mailId']) && isset($_POST['userName']) && isset($_POST['password']) && isset($_POST['confrmPassword']) && isset($_POST['g-recaptcha-response'])) {
    extract($_POST);
    $response = array();
    if (empty($_POST['fName']) || empty($_POST['lName']) || empty($_POST['mailId']) || empty($_POST['userName']) || empty($_POST['password']) || empty($_POST['confrmPassword']) || empty($_POST['g-recaptcha-response'])) {
        sendAjaxResponse(1, 'Please enter all required values along with captcha.');
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $fName)) {
        sendAjaxResponse(1, 'Invalid characters found in first name.');
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $lName)) {
        sendAjaxResponse(1, 'Invalid characters found in last name.');
    } elseif (!filter_var($mailId, FILTER_VALIDATE_EMAIL)) {
        sendAjaxResponse(1, 'Invalid characters/format found in email.');
    } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $userName)) {
        sendAjaxResponse(1, 'Invalid username format. Username can only have alphanumeric characters.');
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
        try {
            $fName = htmlspecialchars($fName);
            $lName = htmlspecialchars($lName);
            $userName = htmlspecialchars($userName);
            $stmt = $db->prepare("SELECT memberId from admin where emailId = ?");
            $stmt->execute([$mailId]);
            $rowcount = $stmt->rowCount();
            if ($rowcount > 0) {
                sendAjaxResponse(1, 'This email account already exists on this website. Reset your password and try logging in.');
            }
            $stmt = $db->prepare("SELECT memberId from admin where username = ?");
            $stmt->execute([$userName]);
            $rowcount = $stmt->rowCount();
            if ($rowcount > 0) {
                sendAjaxResponse(1, 'This username is not available. Please try something else.');
            }
            $passwordHash = password_hash($password, PASSWORD_ARGON2ID, $hashOptions);
            $activationKey = hash('sha256', uniqid(rand(), true));
            $ktmt = $db->prepare('INSERT into admin (`role`, `firstName`, `lastName`,`emailId`, `username` , `password`, `activationKey`, `activationResetTime`) values (?, ?, ?, ?, ?, ?, ?, NOW())');
            if ($ktmt->execute(['AUTHOR', $fName, $lName, $mailId, $userName, $passwordHash, $activationKey])) {
                $memberId = $db->lastInsertId('userID');
                $to = $mailId;
                $subject = "Verify your account | Travel The World";
                $body = "Thank you for registering at Travel The World. <br><br> Kindly confirm your account by clicking following link <br> <a href='" . SITE_ADDR . "/users/activateAccount.php?x=" . $memberId . "&y=" . $activationKey . "'>Activate my account</a> <br><br> Regards, <br> Site Admin, <br> Smriti Dangwal, <br>Travel The World";
                sendMail($to, $subject, $body);
                sendAjaxResponse(0, 'You are registered. An account activation link link has been sent to your associated email.');
            } else {
                sendAjaxResponse(1, 'Error occurred while processing your request. Please try again.');
            }
        } catch (Exception $e) {
            sendAjaxResponse(-1, $e->getMessage(), $e->getCode());
        }
    }
} else {
    sendAjaxResponse(1, 'All required parameters not found. Refresh your page and try again.');
}
