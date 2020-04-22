<?php
require_once('./functions.php');
checkIfUserNotLoggedIn($handler = false);
if (!isset($_GET['id'])) {
    header('Location: ./index.php');
}
$post = viewPostById($_GET['id']);
?>
<!DOCTYPE html>
<html>

<head>
    <title> Travel The World | View Post</title>
    <?php head(); ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php topNavigation(); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php sideNavigation(); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" id="contentWrapper">
            <!-- Content Header (Page header) -->
            <?php if ($post['postId']) {
            ?>
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Post View</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <?php if ($post['postPublished']) {
                                    ?>
                                        <li class="breadcrumb-item badge badge-success p-1">Post Approved</li>
                                    <?php } elseif ($post['postRejected']) {
                                    ?>
                                        <li class="breadcrumb-item badge badge-danger p-1">Post Rejected</li>
                                    <?php } else {
                                    ?>
                                        <li class="breadcrumb-item badge badge-warning p-1">Post In Review</li>
                                    <?php } ?>
                                </ol>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <!-- Main row -->
                        <div class="row">
                            <!-- col -->
                            <div class="col-md-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Post Details</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                                <i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <h3 class="text-center"><?php echo $post['postTitle']; ?></h3>
                                        </div>
                                        <div class="form-group">
                                            <div class="text-center marginTop10">
                                                <img id="headerImageShow" class="img-fluid col-md-8 col-12" src="../assets/postImages/<?php echo $post['postId'] . "." . $post['postImage']; ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group bg-gray p-3">
                                            <p class="text-justify"><?php echo $post['postDescrip']; ?></p>
                                        </div>
                                        <div class="form-group text-justify p-2">
                                            <?php echo htmlspecialchars_decode($post['postContent']); ?>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <?php if ($isAdmin && !$post['postPublished'] && !$post['postRejected']) {
                            ?>
                                <div class="col-12 form-group text-center" id="approveRejectDiv">
                                    <input type="hidden" id="postId" value="<?php echo $post['postId']; ?>" />
                                    <input type="button" id="editPost" value="Edit Post" class="btn btn-warning">
                                    <input type="button" id="rejectPost" value="Reject Post" class="btn btn-danger">
                                    <input type="button" id="approvePost" value="Approve Post" class="btn btn-success">
                                </div>
                            <?php } ?>
                        </div>
                        <!-- /.row (main row) -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            <?php } else {
            ?>
                <div class="content-header">
                    <h4 class="text-center mandatory">You are not authorised for this section.</h4>
                    <p class="text-center"><a href="./index.php">Go to Home &rightarrow;</a></p>
                </div>
            <?php } ?>
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer Wrapper -->
        <?php footer(); ?>

    </div>
    <!-- ./wrapper -->
    <?php bodyJs(); ?>
    <script nonce="<?php echo $nonce; ?>">
        <?php if ($isAdmin && !$post['postPublished'] && !$post['postRejected']) {
            // generate csrf token with random string using bcrypt and store in session
            $csrfApproveRejectToken = password_hash(md5(uniqid(mt_rand(), true)) . $userDetails['username'], PASSWORD_BCRYPT);
            $_SESSION['csrf-approve-reject-token'] = $csrfApproveRejectToken;
            $csrfEditPostToken = password_hash(md5(uniqid(mt_rand(), true)) . $userDetails['emailId'], PASSWORD_BCRYPT);
            $_SESSION['csrf-edit-post-token'] = $csrfEditPostToken;
        ?>
            $("#approvePost").on('click', function() {
                approveRejectPostAjax(this, "approve", "<?php echo $csrfApproveRejectToken; ?>");
            });
            $("#rejectPost").on('click', function() {
                approveRejectPostAjax(this, "reject", "<?php echo $csrfApproveRejectToken; ?>");
            });
            $("#editPost").on('click', function() {
                getEditPostAjax(this, "<?php echo $csrfEditPostToken; ?>");
            });
        <?php
        }
        ?>
    </script>
</body>

</html>