<?php
include_once '../functions.php';
checkIfNotAjaxRequest();
checkIfUserNotLoggedIn($handler = true);
checkIfRoleAuthorized('AUTHOR', $handler = true);
if (
    isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_SERVER['CONTENT_LENGTH']) && empty($_POST)
) {
    $postMaxSize = sizeToBytes(ini_get('post_max_size'));
    $postContentLength = $_SERVER['CONTENT_LENGTH'];
    if ($postContentLength > $postMaxSize) {
        sendAjaxResponse(1, 'Your post with size ' . $postContentLength / 1024 / 1024 . ' MB has exceeded the allowed size of ' . $postMaxSize / 1024 / 1024 . ' MB.');
    }
}
checkValidReferer();
csrfTokenVerify('csrf-add-post-token');
$_POST = array_map('htmlspecialchars', $_POST);
if (isset($_POST['postTitle']) && isset($_POST['postDescrp']) && isset($_POST['postCont']) && isset($_FILES['headerImage'])) {
    extract($_POST);
    $headerImage = $_FILES['headerImage'];
    $response = array();
    if (empty($_POST['postTitle']) || empty($_POST['postDescrp']) || empty($_POST['postCont']) || $headerImage['size'] == 0) {
        sendAjaxResponse(1, 'Please enter all required values.');
    } elseif (!validateFileUpload($headerImage)) {
        sendAjaxResponse(1, 'Please upload valid image file.');
    } else {
        try {
            $memberID = $_SESSION['memberId'];
            $ktmt = $db->prepare('SELECT * FROM post WHERE postDate >= (NOW() - INTERVAL 1 DAY) AND postModerator = ?');
            $ktmt->execute([$memberID]);
            $lastPostedIn24H = $ktmt->rowCount();
            if ($lastPostedIn24H > 0) {
                sendAjaxResponse(1, 'You have already posted in last 24 hours and you can only post once in 24 hours.');
            } else {
                $ktmt = $db->prepare('INSERT into post (postTitle, postDescrip, postContent, postImage, postModerator) values (?, ?, ?, ?, ?)');
                if ($ktmt->execute([$postTitle, $postDescrp, $postCont, strtolower(pathinfo(basename($headerImage["name"]), PATHINFO_EXTENSION)), $memberID])) {
                    $postID = $db->lastInsertId();
                    $imageFileType = strtolower(pathinfo($headerImage["name"], PATHINFO_EXTENSION));
                    $uploadFile = "../../assets/postImages/" . $postID . "." . $imageFileType;
                    if (createImageAndUpload($headerImage["tmp_name"], $uploadFile)) {
                        sendAjaxResponse(0, 'Your post has been sent for review. You can check it in My Posts section.');
                    } else {
                        sendAjaxResponse(1, 'Error occurred while uploading your header image. Please try again.');
                    }
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
