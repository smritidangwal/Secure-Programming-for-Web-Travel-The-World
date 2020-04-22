<?php
require_once('./functions.php');
checkIfUserNotLoggedIn($handler = false);
checkIfRoleAuthorized('AUTHOR', $handler = false);
?>
<!DOCTYPE html>
<html>

<head>
    <title> Travel The World | My Posts</title>
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
                            <h1 class="m-0 text-dark"> My Posts </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="./">Home</a></li>
                                <li class="breadcrumb-item active">My Posts</li>
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
                                $pages = new Paginator('10', 'p');
                                $stmt = $db->prepare('SELECT count(*) FROM post where postModerator = ?');
                                $stmt->execute([$userDetails['memberId']]);
                                //pass number of records to
                                $rowCount = $stmt->fetchColumn();
                            ?>
                                <!-- TABLE: LATEST ORDERS -->
                                <div class="card">
                                    <div class="card-header border-transparent">
                                        <h3 class="card-title">All Posts</h3>
                                        <div class="card-tools">
                                            <a href="./addPost.php" class="btn btn-sm btn-outline-primary float-left"><i class="fas fa-plus"></i> New Post</a>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <?php if ($rowCount) { ?>
                                        <div class="card-body p-0">
                                            <div class="table-responsive table-hover">
                                                <table class="table m-0" id="tableView">
                                                    <thead>
                                                        <tr>
                                                            <th>Visibility</th>
                                                            <th>Title</th>
                                                            <th>Description</th>
                                                            <th>Date</th>
                                                            <th>Views</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $pages->set_total($rowCount);
                                                        $stmt = $db->prepare('SELECT * FROM post where postModerator = ? ORDER BY postId DESC ' . $pages->get_limit());
                                                        $stmt->execute([$userDetails['memberId']]);
                                                        while ($row = $stmt->fetch()) {
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <a href="viewPost.php?id=<?php echo $row['postId']; ?>">
                                                                        <?php if ($row['postPublished']) { ?>
                                                                            <button class="badge badge-success">Approved</button>
                                                                        <?php } elseif ($row['postRejected']) { ?>
                                                                            <button class="badge badge-danger">Rejected</button>
                                                                        <?php } else { ?>
                                                                            <button class="badge badge-warning">In Review</button>
                                                                        <?php } ?>
                                                                    </a>
                                                                </td>
                                                                <td><?php echo $row['postTitle']; ?></td>
                                                                <td><?php echo $row['postDescrip']; ?></td>
                                                                <td><?php echo date('jS M Y', strtotime($row['postDate'])); ?></td>
                                                                <td><?php echo $row['postViews']; ?></td>
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