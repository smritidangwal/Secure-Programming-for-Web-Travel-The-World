<?php
require_once('./functions.php');
checkIfUserNotLoggedIn($handler = false);
$memberId = $_SESSION['memberId'];
$ktmt = $db->prepare('INSERT into userlog (userId, username, userIp, action) values (?, ?, ?, ?)');
$ktmt->execute([$memberId, $userDetails['username'], $_SERVER['REMOTE_ADDR'], "LOGOUT"]);
$uptkmtk = $db->prepare('UPDATE admin SET session  = :session, sessionIp = :sessionIp WHERE memberId = :memberId');
$uptkmtk->execute(array(
    ':memberId' => $memberId,
    ':session' => NULL,
    ':sessionIp' => NULL
));

unset($_SESSION['memberId']);
unset($_SESSION['session']);
unset($_SESSION['sessionIp']);
session_destroy();
header('Location: ./index.php');
exit;
