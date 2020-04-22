<?php
include_once '../functions.php';
checkIfNotAjaxRequest();
checkIfUserLoggedIn($handler = true);
checkValidReferer();
csrfTokenVerify('csrf-login-token');
$_POST = array_map('strip_tags', $_POST);
if (isset($_POST['formUsername']) && isset($_POST['formPassword'])) {
    extract($_POST);
    $response = array();
    if (empty($formUsername) || empty($formPassword)) {
        sendAjaxResponse(1, 'Username or Password empty.');
    } elseif (!ctype_alnum($formUsername)) {
        sendAjaxResponse(1, 'Invalid username format. Username can only have alphanumeric characters.');
    } elseif (!empty($formPassword) && !preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*~\'\";:\?\\|\/.,_+-]).{8,}$/", $formPassword)) {
        sendAjaxResponse(1, 'Invalid username or password. Please try again.');
    } else {
        try {
            $formUsername = htmlspecialchars($formUsername);
            $userRole = "AUTHOR";
            $ktmt = $db->prepare("SELECT * FROM admin where username = ? AND ROLE = ?");
            $ktmt->execute([$formUsername, $userRole]);
            $rowk = $ktmt->fetch(PDO::FETCH_ASSOC);
            if ($rowk['memberId']) {
                $memberId = $rowk['memberId'];
                $sessionIp = $_SERVER['REMOTE_ADDR'];
                $failedLoginAttempts = $rowk['failedLoginAttempts'];
                if ($rowk['activationStatus'] == 1) {
                    if ($rowk['disableStatus'] == 0) {
                        if ($rowk['lockStatus'] == 0) {
                            if ($failedLoginAttempts > 1) {
                                if (!isset($_POST['g-recaptcha-response'])) {
                                    sendAjaxResponse(7, 'You had ' . $failedLoginAttempts . ' incorrect login attempts. Your account will be locked after 5 incorrect attempts. Please verify the captcha and try again.', 0);
                                } else {
                                    $captchaResponse = $_POST["g-recaptcha-response"];
                                    $captchaSecret = "6LcZOegUAAAAAC506ODzOviR_cqMNWTNGXXRndcu";
                                    $captchaVerify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$captchaSecret}&response={$captchaResponse}");
                                    $captchaVerify = json_decode($captchaVerify);
                                    if ($captchaVerify->success != true) {
                                        sendAjaxResponse(1, 'Captcha error occurred. Please refresh and try again.');
                                    }
                                }
                            }
                            if (password_verify($formPassword, $rowk['password'])) {
                                session_regenerate_id();
                                $currentCookieParams = session_get_cookie_params();
                                setcookie(
                                    session_name(),
                                    session_id(),
                                    $currentCookieParams["lifetime"],
                                    $currentCookieParams["path"],
                                    null,
                                    $currentCookieParams["secure"],
                                    $currentCookieParams["httponly"]
                                );
                                $session = session_id();
                                $_SESSION['memberId'] = $memberId;
                                $_SESSION['session'] = $session;
                                $_SESSION['sessionIp'] = $sessionIp;
                                $ktmt = $db->prepare("UPDATE admin set session = ?, sessionIp = ?, failedLoginAttempts = 0 WHERE memberId = ?");
                                if ($ktmt->execute([$session, $sessionIp, $memberId])) {
                                    $ktmt = $db->prepare('INSERT into userlog (userId, username, userIp, action) values (?, ?, ?, ?)');
                                    $ktmt->execute([$memberId, $rowk['username'], $sessionIp, "LOGIN"]);
                                    sendAjaxResponse(0, 'You are logged in.');
                                } else {
                                    unset($_SESSION['memberId']);
                                    unset($_SESSION['session']);
                                    unset($_SESSION['sessionIp']);
                                    session_destroy();
                                    sendAjaxResponse(1, 'Error occurred while processing your request. Please try again.');
                                }
                            } else {
                                $ktmt = $db->prepare('INSERT into userlog (userId, username, userIp, action) values (?, ?, ?, ?)');
                                $ktmt->execute([$memberId, $rowk['username'], $sessionIp, "INCORRECT LOGIN"]);
                                $ktmt = $db->prepare('UPDATE admin SET failedLoginAttempts = failedLoginAttempts + 1 WHERE memberId = ?');
                                $ktmt->execute([$memberId]);
                                $actualFailedAttempts = $failedLoginAttempts + 1;
                                if ($actualFailedAttempts == 2) {
                                    sendAjaxResponse(7, 'You had ' . $actualFailedAttempts . ' incorrect login attempts. Your account will be locked after 5 incorrect attempts. Please verify the captcha and try again.', 0);
                                } elseif ($actualFailedAttempts > 2 && $actualFailedAttempts < 5) {
                                    sendAjaxResponse(7, 'You had ' . $actualFailedAttempts . ' incorrect login attempts. Your account will be locked after 5 incorrect attempts. Please verify the captcha and try again.', 1);
                                } elseif ($actualFailedAttempts == 5) {
                                    $ktmt = $db->prepare('UPDATE admin SET lockStatus = 1 WHERE memberId = ?');
                                    $ktmt->execute([$memberId]);
                                    $to = $rowk['emailId'];
                                    $subject = "Account locked | Travel The World";
                                    $body = "Hi, <br> <br> Your account has been locked due to many incorrect login attempts. You can reset the password and unlock your account. <br><br> Regards, <br> Site Admin, <br> Smriti Dangwal, <br> Travel The World";
                                    sendMail($to, $subject, $body);
                                    sendAjaxResponse(7, 'Your account has been locked. Please reset your password and try logging in.', 1);
                                } else {
                                    sendAjaxResponse(1, 'Invalid username or password found.');
                                }
                            }
                        } else {
                            sendAjaxResponse(1, 'Please unlock your account by resetting password and try logging in.');
                        }
                    } else {
                        sendAjaxResponse(1, 'Your account is disabled. Contact administrator on ' . SITE_EMAIL . ' to enable your account.');
                    }
                } else {
                    sendAjaxResponse(1, 'Please activate your account and try logging in.');
                }
            } else {
                sendAjaxResponse(1, 'Invalid username or password. Please try again.');
            }
        } catch (Exception $e) {
            sendAjaxResponse(-1, $e->getMessage(), $e->getCode());
        }
    }
} else {
    sendAjaxResponse(1, 'Username or Password parameters not found.');
}
