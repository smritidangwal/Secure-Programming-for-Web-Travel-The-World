<?php
$loadFiles = array();
$loadFiles[] = "../config/class.paginator.php";
$loadFiles[] = "../../config/PHPMailer.php";
$loadFiles[] = "../config/config.php";
$loadFiles[] = "../../config/config.php";
foreach ($loadFiles as $includeFile) {
    if (file_exists($includeFile)) {
        require_once $includeFile;
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($to, $subject, $body, $from = SITE_EMAIL, $fromName = SITE_TITLE)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->SMTPDebug = 0; // 2 -> ON, 0 -> OFF
        $mail->Host = 'smtp.hostinger.in';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'username@gmail.com';
        $mail->Password = 'random@2251';

        $mail->setFrom($from, $fromName);
        $mail->addReplyTo($from, $fromName);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    } catch (Exception $e) {
    }
}
function checkIfNotAjaxRequest()
{
    if (!AJAX_REQUEST) {
        header('Location: ../index.php');
    }
}
function checkLoggedUserRole($role)
{
    global $userDetails;
    if ($role == $userDetails['role'])
        return 1;
    else return 0;
}
function sendAjaxResponse($errorCode, $responseText, $responseCode = null)
{
    /*
    -1 -> Exception response
    0 -> Success response
    1 -> Error response
    2 -> Unauthorized request
    7 -> Failed Login attempt exceeded
    */
    $response = array();
    $response['error'] = $errorCode;
    $response['responseText'] = $responseText;
    $response['code'] = $responseCode;
    echo json_encode($response);
    exit;
}
if (isset($_SESSION['memberId']) && isset($_SESSION['session'])) {
    $userDetails = currentUser();
}
$isAdmin = checkLoggedUserRole('ADMIN');
$isAuthor = checkLoggedUserRole('AUTHOR');

function checkValidReferer()
{
    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = $_SERVER['HTTP_REFERER'];
        if (!preg_match('/^https:\/\/(www\.)?smritidangwal\.com/', $referer)) {
            sendAjaxResponse(1, 'Unauthorized request found. Please refresh and try again.');
        }
    }
}
function csrfTokenVerify($sessionToken)
{
    if (!isset($_SESSION[$sessionToken]) || !isset($_POST['cToken']) || !(isset($_SESSION[$sessionToken]) && isset($_POST['cToken']) && $_SESSION[$sessionToken] === $_POST['cToken'])) {
        sendAjaxResponse(1, 'Unauthorized request found. Please refresh your page and try again.');
    }
}

function validateFileUpload($image)
{
    $size = 2097152; //2MB
    $uploadOk = 1;
    $target_file = basename($image["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($image["tmp_name"]);
    if ($check === false) {
        $uploadOk = 0;
    }    // Check file size
    if ($image["size"] > $size) {
        $uploadOk = 0;
    }
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $uploadOk = 0;
    }
    return $uploadOk;
}
function sizeToBytes($size)
{
    $suff = strtoupper(substr($size, -1));
    if (!in_array($suff, array('G', 'M', 'K'))) {
        $byteValue = (int) $size;
    } else {
        $byteValue = substr($size, 0, -1);
        switch ($suff) {
            case 'G':
                $byteValue *= 1024;
            case 'M':
                $byteValue *= 1024;
            case 'K':
                $byteValue *= 1024;
                break;
        }
    }
    return (int) $byteValue;
}

function createImageAndUpload($tmpImage, $uploadImage)
{
    $imageType = getimagesize($tmpImage)[2];
    $return = false;
    switch ($imageType) {
        case IMAGETYPE_PNG:
            $imageSrc = imagecreatefrompng($tmpImage);
            $oldImage = imageResize($imageSrc);
            imagepng($oldImage, $uploadImage, 1);
            $return = true;
            break;
        case IMAGETYPE_JPEG:
            $imageSrc = imagecreatefromjpeg($tmpImage);
            $oldImage = imageResize($imageSrc);
            imagejpeg($oldImage, $uploadImage, 90);
            $return = true;
            break;
        default:
            break;
    }
    return $return;
}
function imageResize($imageSrc)
{
    $imw = imagesx($imageSrc);
    $imh = imagesy($imageSrc);
    $newImageLayer = imagecreatetruecolor($imw, $imh);
    imagecopyresampled($newImageLayer, $imageSrc, 0, 0, 0, 0, $imw, $imh, $imw, $imh);
    return $newImageLayer;
}

function head()
{
?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="./custom/main.css">
<?php
}
function bodyJs()
{
    global $nonce;
?>
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <script nonce="<?php echo $nonce; ?>">
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="plugins/toastr/toastr.min.js"></script>
    <script src="dist/js/adminlte.js" nonce="<?php echo $nonce; ?>"></script>
    <script src="./custom/main.js"></script>
<?php
}
function topNavigation()
{
?>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-slide="true" href="./logout.php">Logout &nbsp; <i class="fas fa-sign-out-alt"></i></a>
            </li>
        </ul>
    </nav>
<?php
}
function sideNavigation()
{
    global $isAuthor;
    global $isAdmin;
    global $userDetails;
?>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="../" class="brand-link">
            <img src="../assets/images/logo.png" alt="Travel The World" class="brand-image img-circle elevation-3">
            <span class="brand-text font-weight-light"> Travel The World </span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
                    <?php if ($isAuthor) {
                    ?>
                        <i class="nav-icon fas fa-user-tie fa-2x text-white"></i>
                    <?php
                    } elseif ($isAdmin) {
                    ?>
                        <i class="nav-icon fas fa-user-shield fa-2x text-white"></i>
                    <?php
                    } ?>
                </div>
                <div class="info">
                    <a href="index.php" class="d-block"><?php echo ucwords($userDetails['firstName'] . " " . $userDetails['lastName']); ?></a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-4">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="./" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <?php if ($isAdmin) { ?>
                        <li class="nav-header"> Posts </li>
                        <li class="nav-item">
                            <a href="./pendingApproval.php" class="nav-link">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    Pending Approval
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./approvedPosts.php" class="nav-link">
                                <i class="nav-icon fas fa-blog"></i>
                                <p>
                                    Approved Posts
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./rejectedPosts.php" class="nav-link">
                                <i class="nav-icon fas fa-exclamation-circle"></i>
                                <p>
                                    Rejected Posts
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">Accounts</li>
                        <li class="nav-item">
                            <a href="./addAdmin.php" class="nav-link">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    Add Admin
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./admins.php" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Admins
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./users.php" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Users
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./updateProfile.php" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>
                                    Update Profile
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./changePassword.php" class="nav-link">
                                <i class="nav-icon fas fa-lock"></i>
                                <p>
                                    Change Password
                                </p>
                            </a>
                        </li>
                    <?php } elseif ($isAuthor) { ?>
                        <li class="nav-header"> Posts </li>
                        <li class="nav-item">
                            <a href="./addPost.php" class="nav-link">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    Add Post
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./myPosts.php" class="nav-link">
                                <i class="nav-icon fas fa-bars"></i>
                                <p>
                                    My Posts
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">Accounts</li>
                        <li class="nav-item">
                            <a href="./updateProfile.php" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>
                                    Update Profile
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./changePassword.php" class="nav-link">
                                <i class="nav-icon fas fa-lock"></i>
                                <p>
                                    Change Password
                                </p>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
<?php
}
function footer()
{
?>
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            Share your visit with us!
        </div>
        <!-- Default to the left -->
        <strong>Travel The World</strong> | All rights reserved.
    </footer>
<?php
}
function checkSessionTimeout()
{
    $sessionInactive = 900;
    if (!isset($_SESSION['sessionTimeout']))
        $_SESSION['sessionTimeout'] = time() + $sessionInactive;

    $sessionLife = time() - $_SESSION['sessionTimeout'];

    if ($sessionLife > $sessionInactive) {
        session_destroy();
        header("Location: index.php");
    } else {
        $_SESSION['sessionTimeout'] = time();
    }
}
checkSessionTimeout();

function checkIfUserLoggedIn($handler)
{
    global $db;
    $location = 'Location: ./index.php';
    $errorLocation = 'Location: ./login.php?action=invaliderror';
    if (isset($_SESSION['session']) && isset($_SESSION['sessionIp']) && isset($_SESSION['memberId'])) {
        try {
            $userid = $_SESSION['memberId'];
            $ktmt = $db->prepare('SELECT session, sessionIp FROM admin where memberId = ?');
            $ktmt->execute([$userid]);
            $rowk = $ktmt->fetch(PDO::FETCH_ASSOC);
            if ($_SESSION['session'] === $rowk['session'] && $_SESSION['sessionIp'] === $rowk['sessionIp']) {
                if ($handler) {
                    sendAjaxResponse(2, 'You are already logged in.');
                } else {
                    header($location);
                    exit;
                }
            }
        } catch (Exception $e) {
            if ($handler) {
                sendAjaxResponse(-1, $e->getMessage(), $e->getCode());
            } else {
                header($errorLocation);
                exit;
            }
        }
    }
}
function checkIfUserNotLoggedIn($handler)
{
    global $db;
    $location = 'Location: ./login.php';
    $errorLocation = 'Location: ./login.php?action=invaliderror';
    if (!isset($_SESSION['session']) || !isset($_SESSION['sessionIp']) || !isset($_SESSION['memberId'])) {
        if ($handler) {
            sendAjaxResponse(2, 'You are not logged in. Proceed to login.');
        } else {
            header($location);
            exit;
        }
    } else {
        try {
            $userId = $_SESSION['memberId'];
            $ktmt = $db->prepare('SELECT * FROM admin where memberId = ?');
            $ktmt->execute([$userId]);
            $rowk = $ktmt->fetch(PDO::FETCH_ASSOC);
            if ($_SESSION['session'] !== $rowk['session'] || $_SESSION['sessionIp'] !== $rowk['sessionIp']) {
                if ($handler) {
                    sendAjaxResponse(2, 'You are not logged in. Proceed to login.');
                } else {
                    header($location);
                    exit;
                }
            }
        } catch (Exception $e) {
            if ($handler) {
                sendAjaxResponse(-1, $e->getMessage(), $e->getCode());
            } else {
                header($errorLocation);
                exit;
            }
        }
    }
}
function checkIfRoleAuthorized($reqRole, $handler)
{
    global $isAdmin, $isAuthor;
    $location = 'Location: ./index.php';
    if ($reqRole == "AUTHOR" && !$isAuthor) {
        if ($handler) {
            sendAjaxResponse(2, 'You are not authorized for this operation.');
        } else {
            header($location);
            exit;
        }
    } elseif ($reqRole == "ADMIN" && !$isAdmin) {
        if ($handler) {
            sendAjaxResponse(2, 'You are not authorized for this operation.');
        } else {
            header($location);
            exit;
        }
    }
}

function getLatestUsers()
{
    global $db;
    $stmt = $db->query('SELECT * FROM admin WHERE role = "AUTHOR" ORDER BY memberId DESC LIMIT 8');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getActivePostCount()
{
    global $db;
    $stmt = $db->query('SELECT * FROM post WHERE postRejected = 0');
    $stmt->execute();
    return $stmt->rowCount();
}
function getPendingPostCount()
{
    global $db;
    $stmt = $db->query('SELECT * FROM post WHERE postRejected = 0 AND postPublished = 0');
    $stmt->execute();
    return $stmt->rowCount();
}
function getApprovedPostCount()
{
    global $db;
    $stmt = $db->query('SELECT * FROM post WHERE postPublished = 1');
    $stmt->execute();
    return $stmt->rowCount();
}
function getRejectedPostCount()
{
    global $db;
    $stmt = $db->query('SELECT * FROM post WHERE postRejected = 1');
    $stmt->execute();
    return $stmt->rowCount();
}
function getTotalPostCount()
{
    global $db;
    $stmt = $db->query('SELECT * FROM post');
    $stmt->execute();
    return $stmt->rowCount();
}
function getUserCount()
{
    global $db;
    $stmt = $db->query('SELECT * FROM admin WHERE ROLE = "AUTHOR"');
    $stmt->execute();
    return $stmt->rowCount();
}
function getPopularPosts()
{
    global $db;
    $stmt = $db->prepare('SELECT * FROM post WHERE postPublished = 1 ORDER BY postViews');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getApprovedNPendingUserPosts()
{
    global $db, $userDetails;
    $stmt = $db->prepare('SELECT * FROM post WHERE postModerator = ? AND postRejected = 0');
    $stmt->execute([$userDetails['memberId']]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getApprovedUserPostCount()
{
    global $db, $userDetails;
    $stmt = $db->prepare('SELECT * FROM post WHERE postPublished = 1 AND postModerator = ?');
    $stmt->execute([$userDetails['memberId']]);
    return $stmt->rowCount();
}
function getPendingUserPostCount()
{
    global $db, $userDetails;
    $stmt = $db->prepare('SELECT * FROM post WHERE postRejected = 0 AND postPublished = 0 AND postModerator = ?');
    $stmt->execute([$userDetails['memberId']]);
    return $stmt->rowCount();
}

function currentUser()
{
    $memberId = $_SESSION['memberId'];
    $session = $_SESSION['session'];
    global $db;
    $stmt = $db->prepare('SELECT * from admin where memberId = ? AND session = ?');
    $stmt->execute([$memberId, $session]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getUserById($id)
{
    global $db;
    $stmt = $db->prepare('SELECT firstName, lastName from admin where memberId = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getPostById($id)
{
    global $db;
    if (is_int($id)) {
        $stmt = $db->prepare('select * from post where postId = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}
function getRejectedPostById($id)
{
    global $db;
    if (is_int($id)) {
        $stmt = $db->prepare('SELECT * from post where postId = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}
function viewPostById($id)
{
    global $db, $userDetails;
    if (is_numeric($id)) {
        if ($userDetails['role'] == "ADMIN") {
            $prepareSql = 'SELECT * from post where postId = ?';
            $stmt = $db->prepare($prepareSql);
            $stmt->execute([$id]);
        } else {
            $prepareSql = 'SELECT * from post where postId = ? AND postModerator = ?';
            $stmt = $db->prepare($prepareSql);
            $stmt->execute([$id, $userDetails['memberId']]);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}
function getLastPostTimeIn24H()
{
    global $db, $userDetails;
    $stmt = $db->prepare('SELECT * FROM post WHERE postDate >= (NOW() - INTERVAL 1 DAY) AND postModerator = ?');
    $stmt->execute([$userDetails['memberId']]);
    return $stmt->rowCount();
}
