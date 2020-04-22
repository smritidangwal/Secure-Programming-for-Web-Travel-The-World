<?php
include_once '../functions.php';
checkIfNotAjaxRequest();
checkIfUserNotLoggedIn($handler = true);
checkValidReferer();
csrfTokenVerify('csrf-update-profile-token');
$_POST = array_map('strip_tags', $_POST);
if (isset($_POST['fName']) && isset($_POST['lName']) && isset($_POST['userName']) && isset($_POST['bio'])) {
    extract($_POST);
    $response = array();
    if (empty($_POST['fName']) || empty($_POST['lName']) || empty($_POST['userName'])) {
        sendAjaxResponse(1, 'Please enter all required values.');
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $fName)) {
        sendAjaxResponse(1, 'Invalid characters found in first name.');
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $lName)) {
        sendAjaxResponse(1, 'Invalid characters found in last name.');
    } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $userName)) {
        sendAjaxResponse(1, 'Invalid username format. Username can only have alphanumeric characters.');
    } elseif (!empty($bio) && !preg_match("/^[a-zA-Z0-9 .,-]*$/", $bio)) {
        sendAjaxResponse(1, 'Invalid characters found in bio. It can only have alphanumeric characters along with dot(.), comma(,) and hyphen(-).');
    } else {
        try {
            $_POST = array_map('htmlspecialchars', $_POST);
            extract($_POST);
            $stmt = $db->prepare("SELECT memberId from admin where username = ? AND memberId <> ?");
            $stmt->execute([$userName, $userDetails['memberId']]);
            $rowcount = $stmt->rowCount();
            if ($rowcount > 0) {
                sendAjaxResponse(1, 'This username is not available. Please try something else.');
            }
            $ktmt = $db->prepare('UPDATE admin SET firstName = ?, lastName = ?, username = ?, bio = ? WHERE memberId = ?');
            if ($ktmt->execute([$fName, $lName, $userName, $bio, $userDetails['memberId']])) {
                $sessionIp = $_SERVER['REMOTE_ADDR'];
                $ktmt = $db->prepare('INSERT into userlog (userId, username, userIp, action) values (?, ?, ?, ?)');
                $ktmt->execute([$userDetails['memberId'], $userDetails['username'], $sessionIp, "UPDATE PROFILE"]);
                sendAjaxResponse(0, 'Profile details updated successfully.');
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
