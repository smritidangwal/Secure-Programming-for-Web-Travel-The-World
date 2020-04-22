<?php
include_once '../functions.php';
checkIfNotAjaxRequest();
checkIfUserNotLoggedIn($handler = true);
checkIfRoleAuthorized('ADMIN', $handler = true);
checkValidReferer();
csrfTokenVerify('csrf-save-post-token');
$_POST = array_map('htmlspecialchars', $_POST);
if (isset($_POST['postId']) && isset($_POST['postTitle']) && isset($_POST['postDescrp']) && isset($_POST['postCont'])) {
    extract($_POST);
    $response = array();
    if (empty($_POST['postId']) || empty($_POST['postTitle']) || empty($_POST['postDescrp']) || empty($_POST['postCont'])) {
        sendAjaxResponse(1, 'Please enter all required values.');
    } else {
        try {
            $stmt = $db->prepare('UPDATE post SET postTitle = ?, postDescrip = ?, postContent = ? WHERE postId = ?');
            if ($stmt->execute([$postTitle, $postDescrp, $postCont, $postId])) {
                sendAjaxResponse(0, 'Post saved. Redirecting..', $postId);
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
