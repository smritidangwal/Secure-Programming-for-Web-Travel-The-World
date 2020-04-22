<?php
require_once('./functions.php');
checkIfUserNotLoggedIn($handler = false);
$csrfChangePasswordToken = password_hash(md5(uniqid(mt_rand(), true)) . $userDetails['emailId'], PASSWORD_BCRYPT);
$_SESSION['csrf-change-password-token'] = $csrfChangePasswordToken;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Change Password</title>
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
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Change Password</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="./">Home</a></li>
                                <li class="breadcrumb-item active">Change Password</li>
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
                        <form class="col-12" action="./phpHandler/changePasswordHandler.php" method="POST" name="changePasswordForm" id="changePasswordForm">
                            <div class="col-md-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Change Password Details</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                                <i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control" placeholder="Enter your old password" name="oldPassword">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-lock"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control" placeholder="Enter your new password" name="password">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-lock"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control" placeholder="Confirm your new password" name="confrmPassword">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-lock"></span>
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
                                <a href="./index.php" class="btn btn-secondary">Cancel</a>
                                <input type="hidden" value="<?php echo $csrfChangePasswordToken; ?>" name="cToken" />
                                <input type="submit" name="submitChangePasswordForm" value="Change Password" class="btn btn-success float-right">
                            </div>
                        </form>
                    </div>
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer Wrapper -->
        <?php footer(); ?>

    </div>
    <!-- ./wrapper -->
    <?php bodyJs(); ?>
</body>

</html>