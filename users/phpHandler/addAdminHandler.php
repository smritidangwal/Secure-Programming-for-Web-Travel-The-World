<?php
include_once '../functions.php';
checkIfNotAjaxRequest();
checkIfUserNotLoggedIn($handler = true);
checkIfRoleAuthorized('ADMIN', $handler = true);
checkValidReferer();
csrfTokenVerify('csrf-add-admin-token');
$_POST = array_map('strip_tags', $_POST);
if (isset($_POST['fName']) && isset($_POST['lName']) && isset($_POST['mailId']) && isset($_POST['userName']) && isset($_POST['password']) && isset($_POST['confrmPassword'])) {
    extract($_POST);
    $response = array();
    if (empty($_POST['fName']) || empty($_POST['lName']) || empty($_POST['mailId']) || empty($_POST['userName']) || empty($_POST['password']) || empty($_POST['confrmPassword'])) {
        sendAjaxResponse(1, 'Please enter all required values.');
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
        try {
            $_POST = array_map('htmlspecialchars', $_POST);
            extract($_POST);
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
            $activationStatus = 1;
            $lockStatus = 1;
            $ktmt = $db->prepare('INSERT into admin (`role`, `firstName`, `lastName`,`emailId`, `username` , `password`, `activationStatus`, `lockStatus`) values (?, ?, ?, ?, ?, ?, ?, ?)');
            if ($ktmt->execute(['ADMIN', $fName, $lName, $mailId, $userName, $passwordHash, $activationStatus, $lockStatus])) {
                $memberId = $db->lastInsertId('userID');
                $to = $mailId;
                $subject = "You're an Admin | Travel The World";
                $body = "Hi, <br> <br> You have been added as admin for Travel The World website. Kindly reset your password, then login and start reviewing here on custom admin link: <a href='" . SITE_ADDR . "/users/atravellworld_admlog.php' target='_blank'>Admin Login</a> <br><br> Regards, <br> Site Admin, <br> Smriti Dangwal, Travel The World";
                sendMail($to, $subject, $body);
                sendAjaxResponse(0, 'Admin added successfully. An email has been sent with login instructions.');
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
