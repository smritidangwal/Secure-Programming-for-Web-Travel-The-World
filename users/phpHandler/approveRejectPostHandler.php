<?php
include_once '../functions.php';
checkIfNotAjaxRequest();
checkIfUserNotLoggedIn($handler = true);
checkIfRoleAuthorized('ADMIN', $handler = true);
checkValidReferer();
csrfTokenVerify('csrf-approve-reject-token');
$_POST = array_map('htmlspecialchars', $_POST);
$_POST = array_map('strip_tags', $_POST);
if (isset($_POST['postId']) && isset($_POST['operation'])) {
    extract($_POST);
    $response = array();
    if (empty($_POST['postId']) || empty($_POST['operation'])) {
        sendAjaxResponse(1, 'Please enter all required values.');
    } elseif (!is_numeric($postId)) {
        sendAjaxResponse(1, 'Invalid input found.');
    } elseif ($operation != "reject" && $operation != "approve") {
        sendAjaxResponse(1, 'Invalid operation found.');
    } else {
        try {
            $reject = 0;
            $approve = 0;
            if ($operation == "reject")
                $reject = 1;
            elseif ($operation == "approve")
                $approve = 1;
            $ktmt = $db->prepare("UPDATE post SET postPublished = ?, postRejected = ?, postController = ? WHERE postId = ?");
            if ($ktmt->execute([$approve, $reject, $userDetails['memberId'], $postId])) {
                $ktmt = $db->prepare("SELECT postTitle, postModerator FROM post WHERE postId = ?");
                $ktmt->execute([$postId]);
                $postDetails = $ktmt->fetch(PDO::FETCH_ASSOC);
                $ktmt = $db->prepare("SELECT firstName, emailId FROM admin where memberId = ?");
                $ktmt->execute([$postDetails['postModerator']]);
                $postUserDetails = $ktmt->fetch(PDO::FETCH_ASSOC);
                $to = $postUserDetails['emailId'];
                $subject = "Update on your post | " . $postDetails['postTitle'];
                if ($reject) {
                    $body = "Hi " . $postUserDetails['firstName'] . ", <br> <br> We have reviewed your post '" . $postDetails['postTitle'] . "' and we are unable to approve the same due to some issues in the content. <br> You can get back to us regarding this on our email. <br> Thank you for posting with us. <br><br> Regards, <br> Site Admin, <br> Smriti Dangwal, <br> Travel The World";
                    sendMail($to, $subject, $body);
                    sendAjaxResponse(0, 'Post rejected and the user has been notified via email.');
                } elseif ($approve) {
                    $body = "Hi " . $postUserDetails['firstName'] . ", <br> <br> We have reviewed your post '" . $postDetails['postTitle'] . "' and we have approved the same for our users. <br> Thank you for posting with us. <br><br> Regards, <br> Site Admin, <br> Smriti Dangwal, <br>Travel The World";
                    sendMail($to, $subject, $body);
                    sendAjaxResponse(0, 'Post approved and the user has been notified via email.');
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
