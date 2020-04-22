<?php
include_once '../functions.php';
checkIfNotAjaxRequest();
checkIfUserNotLoggedIn($handler = true);
checkIfRoleAuthorized('ADMIN', $handler = true);
checkValidReferer();
csrfTokenVerify('csrf-disable-enable-token');
$_POST = array_map('htmlspecialchars', $_POST);
$_POST = array_map('strip_tags', $_POST);
if (isset($_POST['memberId']) && isset($_POST['operation'])) {
    extract($_POST);
    $response = array();
    if (empty($_POST['memberId']) || empty($_POST['operation'])) {
        sendAjaxResponse(1, 'Invalid input found.');
    } elseif (!is_numeric($memberId)) {
        sendAjaxResponse(1, 'Invalid input found.');
    } elseif ($operation != "disable" && $operation != "enable") {
        sendAjaxResponse(1, 'Invalid operation found.');
    } else {
        try {
            if ($operation == "enable")
                $disableValue = 0;
            elseif ($operation == "disable")
                $disableValue = 1;
            $ktmt = $db->prepare("UPDATE admin SET disableStatus = ?, session = ? WHERE memberId = ?");
            if ($ktmt->execute([$disableValue, NULL, $memberId])) {
                $ktmt = $db->prepare("SELECT firstName, emailId FROM admin WHERE memberId = ?");
                $ktmt->execute([$memberId]);
                $postUserDetails = $ktmt->fetch(PDO::FETCH_ASSOC);
                $to = $postUserDetails['emailId'];
                if ($disableValue == 1) {
                    $subject = "Your account is disabled | " . SITE_TITLE;
                    $body = "Hi " . $postUserDetails['firstName'] . ", <br> <br> We have disabled your account due to some issues. <br> You can reach out to administrator on " . SITE_EMAIL . " for any query regarding the same. <br><br> Regards, <br> Site Admin, <br> Smriti Dangwal, <br> Travel The World";
                    sendMail($to, $subject, $body);
                    sendAjaxResponse(0, "disabled");
                } else {
                    $subject = "Your account is enabled | " . SITE_TITLE;
                    $body = "Hi " . $postUserDetails['firstName'] . ", <br> <br> We have enabled your account. You can start posting now. <br> Thanks for blogging with us. <br><br> Regards, <br> Site Admin, <br> Smriti Dangwal, <br> Travel The World";
                    sendMail($to, $subject, $body);
                    sendAjaxResponse(0, "enabled");
                }
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
