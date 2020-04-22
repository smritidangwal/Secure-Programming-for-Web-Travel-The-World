<?php
include_once '../functions.php';
checkIfNotAjaxRequest();
checkIfUserNotLoggedIn($handler = true);
checkValidReferer();
csrfTokenVerify('csrf-change-password-token');
$_POST = array_map('strip_tags', $_POST);
if (isset($_POST['oldPassword']) && isset($_POST['password']) && isset($_POST['confrmPassword'])) {
    extract($_POST);
    $response = array();
    if (empty($_POST['oldPassword']) || empty($_POST['password']) || empty($_POST['confrmPassword'])) {
        sendAjaxResponse(1, 'Please enter all required values.');
    } elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*~\'\";:\?\\|\/.,_+-]).{8,}$/", $oldPassword)) {
        sendAjaxResponse(1, 'Invalid password format in old password. It should have atleast one capital, one small, one number, one special character with minimum 8 characters.');
    } elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*~\'\";:\?\\|\/.,_+-]).{8,}$/", $password)) {
        sendAjaxResponse(1, 'Invalid password format in new password. It should have atleast one capital, one small, one number, one special character with minimum 8 characters.');
    } elseif ($password !== $confrmPassword) {
        sendAjaxResponse(1, 'Passwords do not match. Please try again.');
    } elseif ($password == $oldPassword) {
        sendAjaxResponse(1, 'Old password and new password cannot be same. Please try again.');
    } else {
        try {
            $stmt = $db->prepare("SELECT password, emailId from admin where memberId = ?");
            $stmt->execute([$userDetails['memberId']]);
            $row = $stmt->fetch();
            if (password_verify($oldPassword, $row['password'])) {
                $passwordHash = password_hash($password, PASSWORD_ARGON2ID, $hashOptions);
                $ktmt = $db->prepare('UPDATE admin SET password = ?, session = ? WHERE memberId = ?');
                if ($ktmt->execute([$passwordHash, NULL, $userDetails['memberId']])) {
                    $sessionIp = $_SERVER['REMOTE_ADDR'];
                    $ktmt = $db->prepare('INSERT into userlog (userId, username, userIp, action) values (?, ?, ?, ?)');
                    $ktmt->execute([$userDetails['memberId'], $userDetails['username'], $sessionIp, "CHANGE PASSWORD"]);
                    $to = $row['emailId'];
                    $subject = "Account Password Changed | Travel The World";
                    $body = "Hi, <br> <br> Your account password has been changed. If it wasn't you, contact the site administrator at earliest on mail: " . SITE_EMAIL . " <br><br> Regards, <br> Site Admin, <br> Smriti Dangwal, Travel The World";
                    sendMail($to, $subject, $body);
                    sendAjaxResponse(0, 'Password changed successfully. Please refresh and login again with new password.');
                } else {
                    sendAjaxResponse(1, 'Error occurred while processing your request. Please try again.');
                }
            } else {
                sendAjaxResponse(1, 'Your old password is incorrect. Please try again.');
            }
        } catch (Exception $e) {
            sendAjaxResponse(-1, $e->getMessage(), $e->getCode());
        }
    }
} else {
    sendAjaxResponse(1, 'All required parameters not found. Refresh your page and try again.');
}
