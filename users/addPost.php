<?php
require_once('./functions.php');
checkIfUserNotLoggedIn($handler = false);
$csrfAddPostToken = password_hash(md5(uniqid(mt_rand(), true)) . $userDetails['username'], PASSWORD_BCRYPT);
$_SESSION['csrf-add-post-token'] = $csrfAddPostToken;
$lastPostedIn24H = getLastPostTimeIn24H();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Post</title>
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
        <div class="content-wrapper">
            <?php if ($isAuthor) {
            ?>
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Post Add</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="./">Home</a></li>
                                    <li class="breadcrumb-item"><a href="./myPosts.php">My Posts</a></li>
                                    <li class="breadcrumb-item active">Add Post</li>
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
                            <form class="col-12" action="./phpHandler/addPostHandler.php" method="POST" name="postForm" id="postForm" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <?php
                                    if ($lastPostedIn24H > 0) {
                                    ?>
                                        <h5 class="text-center mandatory">You have already posted in last 24 hours and you can only post once in 24 hours.</h5>
                                    <?php } ?>
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
                                                <label for="postTitle">Post Title <span class="mandatory">*</span></label>
                                                <input type='text' class="form-control" name='postTitle' id="postTitle" required <?php if ($lastPostedIn24H > 0) echo "disabled"; ?>>
                                            </div>
                                            <div class="form-group">
                                                <label for="postDescText">Add Description <span class="mandatory">*</span></label>
                                                <textarea class="form-control" rows="3" name="postDescrp" id="postDescrp" placeholder="Place some description here" <?php if ($lastPostedIn24H > 0) echo "disabled"; ?>></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="postContText">Add Content (To add an image, drag & drop)<span class="mandatory">*</span></label>
                                                <textarea class="textarea form-control" rows="3" name="postCont" id="postCont" placeholder="Place post content here"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Header Image <span class="mandatory">*</span></label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" accept="image/jpeg, image/jpg, image/png" id="headerImage" name="headerImage" required <?php if ($lastPostedIn24H > 0) echo "disabled"; ?>>
                                                        <label class="custom-file-label" for="headerImage">Choose file</label>
                                                    </div>
                                                </div>
                                                <div class="text-center marginTop10">
                                                    <img id="headerImageShow" class="img-fluid col-md-4 col-12" />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->
                                <div class="col-12 form-group">
                                    <a href="./myPosts.php" class="btn btn-secondary">Cancel</a>
                                    <input type="hidden" value="<?php echo $csrfAddPostToken; ?>" name="cToken" />
                                    <input type="submit" name="submitAddPostForm" value="Save Post" class="btn btn-success float-right" <?php if ($lastPostedIn24H > 0) echo "disabled"; ?>>
                                </div>
                            </form>
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
    <?php bodyJs();
    if ($lastPostedIn24H > 0) {
    ?>
        <script nonce="<?php echo $nonce; ?>">
            $('#postCont').summernote('disable');
        </script>
    <?php } ?>
</body>

</html>