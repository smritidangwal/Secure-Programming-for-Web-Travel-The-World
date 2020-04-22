<?php
require_once('./functions.php');
checkIfUserLoggedIn($handler = false);
$_GET = array_map('trim', $_GET);
$_GET = array_map('strip_tags', $_GET);
if (isset($_GET['x']) && isset($_GET['y'])) {
    $memberId = $_GET['x'];
    $activationKey =  $_GET['y'];
    try {
        $stmt = $db->prepare("UPDATE admin SET activationStatus = 1, activationKey = NULL WHERE memberId = :memberId AND activationKey = :activationKey AND activationStatus = 0 AND activationResetTime >= NOW() - INTERVAL " . ACTIVATION_TIME_DIFFERENCE . " MINUTE");
        $stmt->execute(array(
            ':memberId' => $memberId,
            ':activationKey' => $activationKey
        ));

        if ($stmt->rowCount() == 1) {
            header('Location: ./login.php?response=activated');
            exit;
        } else {
            $gktmt = $db->prepare('SELECT activationStatus FROM admin WHERE memberId = :memberId');
            $gktmt->execute(array(
                ':memberId' => $memberId
            ));
            $rowkg = $gktmt->fetch(PDO::FETCH_ASSOC);
            if ($rowkg['activationStatus'] == 1) {
                header('Location: ./login.php?response=activated');
                exit;
            } else {
                header('Location: ./login.php?response=invalidactivation');
                exit;
            }
        }
    } catch (Exception $e) {
        header('Location: ./login.php?response=error');
        exit;
    }
} else {
    header('Location: ./login.php?response=invalidactivation');
    exit;
}
