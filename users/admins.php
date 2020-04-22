<?php
require_once('./functions.php');
checkIfUserNotLoggedIn($handler = false);
checkIfRoleAuthorized('ADMIN', $handler = false);
?>
<!DOCTYPE html>
<html>

<head>
    <title> Travel The World | Admins</title>
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
                            <h1 class="m-0 text-dark">Admins</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="./">Home</a></li>
                                <li class="breadcrumb-item active">Admins</li>
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
                            <?php
                            try {
                                $pages = new Paginator('20', 'p');
                                $stmt = $db->query('SELECT count(*) FROM admin WHERE ROLE = "ADMIN"');
                                $rowCount = $stmt->fetchColumn();
                            ?>
                                <!-- TABLE: LATEST ORDERS -->
                                <div class="card">
                                    <div class="card-header border-transparent">
                                        <h3 class="card-title">All Admins</h3>

                                        <div class="card-tools">
                                            <a href="./addAdmin.php" class="btn btn-sm btn-outline-primary float-left"><i class="fas fa-plus"></i> New Admin</a>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <?php if ($rowCount) { ?>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table m-0" id="tableView">
                                                    <thead>
                                                        <tr>
                                                            <th>Action</th>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Username</th>
                                                            <th>Created</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $pages->set_total($rowCount);
                                                        $stmt = $db->query('SELECT * FROM admin WHERE ROLE = "ADMIN" ORDER BY memberId DESC ' . $pages->get_limit());
                                                        while ($row = $stmt->fetch()) {
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <?php if ($row['memberId'] != $userDetails['memberId']) { ?>
                                                                        <button type="button" id="enableUser<?php echo $row['memberId']; ?>" class="enableUserBtn badge btn-success <?php if (!$row['disableStatus']) echo 'collapse'; ?>" memberId="<?php echo $row['memberId']; ?>">Enable </button>
                                                                        <button type="button" id="disableUser<?php echo $row['memberId']; ?>" class="disableUserBtn badge btn-danger <?php if ($row['disableStatus']) echo 'collapse'; ?>" memberId="<?php echo $row['memberId']; ?>">Disable </button>
                                                                    <?php } ?>
                                                                </td>
                                                                <td><?php echo ucwords($row['firstName']) . " " . ucwords($row['lastName']); ?></td>
                                                                <td><?php echo $row['emailId']; ?></td>
                                                                <td><?php echo $row['username']; ?></td>
                                                                <td><?php echo date('M dS, Y', strtotime($row['created'])); ?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- /.table-responsive -->
                                        </div>
                                        <!-- /.card-body -->
                                    <?php } ?>
                                    <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <?php echo $pages->page_links(); ?>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /.card -->
                            <?php
                            } catch (Exception $e) {
                                echo "Some Error occurred. Please try again.";
                                exit;
                            }
                            ?>
                        </div>
                    </div>
                    <!-- /.col -->
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
    <script nonce="<?php echo $nonce; ?>">
        <?php
        // generate csrf token with random string using bcrypt and store in session
        $csrfDisableEnableToken = password_hash(md5(uniqid(mt_rand(), true)) . $userDetails['username'], PASSWORD_BCRYPT);
        $_SESSION['csrf-disable-enable-token'] = $csrfDisableEnableToken;
        ?>
        $(".disableUserBtn").on('click', function() {
            disableEnableUser(this, "disable", "<?php echo $csrfDisableEnableToken; ?>");
        });
        $(".enableUserBtn").on('click', function() {
            disableEnableUser(this, "enable", "<?php echo $csrfDisableEnableToken; ?>");
        });
    </script>
</body>

</html>