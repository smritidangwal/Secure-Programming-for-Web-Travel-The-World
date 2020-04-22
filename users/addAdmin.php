<?php
require_once('./functions.php');
checkIfUserNotLoggedIn($handler = false);
$csrfAddAdminToken = password_hash(md5(uniqid(mt_rand(), true)) . $userDetails['username'], PASSWORD_BCRYPT);
$_SESSION['csrf-add-admin-token'] = $csrfAddAdminToken;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Admin</title>
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
            <?php if ($isAdmin) {
            ?>
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Admin Add</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="./">Home</a></li>
                                    <li class="breadcrumb-item"><a href="./admins.php">Admins</a></li>
                                    <li class="breadcrumb-item active">Add Admin</li>
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
                            <form class="col-12" action="./phpHandler/addAdminHandler.php" method="POST" name="addAdminForm" id="addAdminForm">
                                <div class="col-md-12">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Admin Details</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                                    <i class="fas fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" placeholder="Enter your first name" name="fName" required>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-user"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" placeholder="Enter your last name" name="lName" required>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-user"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group mb-3">
                                                        <input type="email" class="form-control" placeholder="Enter your email address" name="mailId" required>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-envelope"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" placeholder="Enter your username" name="userName" required>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-user"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group mb-3">
                                                        <input type="password" class="form-control" placeholder="Enter your password" name="password" required>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-lock"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group mb-3">
                                                        <input type="password" class="form-control" placeholder="Confirm your password" name="confrmPassword" required>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-lock"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->
                                <div class="col-12 form-group">
                                    <a href="./admins.php" class="btn btn-secondary">Cancel</a>
                                    <input type="hidden" value="<?php echo $csrfAddAdminToken; ?>" name="cToken" />
                                    <input type="submit" name="submitAddPostForm" value="Add Admin" class="btn btn-success float-right">
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
    <?php bodyJs(); ?>
</body>

</html>