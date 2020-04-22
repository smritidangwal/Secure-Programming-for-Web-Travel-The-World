<?php
require_once('./functions.php');
checkIfUserNotLoggedIn($handler = false);
checkIfRoleAuthorized('ADMIN', $handler = false);
?>
<!DOCTYPE html>
<html>

<head>
    <title> Travel The World | Posts</title>
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
                            <h1 class="m-0 text-dark"> Approved Posts </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="./">Home</a></li>
                                <li class="breadcrumb-item active">Approved Posts</li>
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
                                $stmt = $db->query('SELECT count(*) FROM post where postPublished = 1');
                                //pass number of records to
                                $rowCount = $stmt->fetchColumn();
                            ?>
                                <div class="card">
                                    <!-- /.card-header -->
                                    <?php if ($rowCount) { ?>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table m-0" id="tableView">
                                                    <thead>
                                                        <tr>
                                                            <th>Action</th>
                                                            <th>Title</th>
                                                            <th>Description</th>
                                                            <th>Date</th>
                                                            <th>Views</th>
                                                            <th>Author</th>
                                                            <th>Controller</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $pages->set_total($rowCount);
                                                        $stmt = $db->query('SELECT * FROM post where postPublished = 1 ORDER BY postId DESC ' . $pages->get_limit());
                                                        while ($row = $stmt->fetch()) {
                                                            $moderator = getUserById($row['postModerator']);
                                                            $postController = getUserById($row['postController']);
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <a href="viewPost.php?id=<?php echo $row['postId']; ?>">
                                                                        <button class="badge badge-primary">View</button>
                                                                    </a>
                                                                </td>
                                                                <td><?php echo $row['postTitle']; ?></td>
                                                                <td><?php echo $row['postDescrip']; ?></td>
                                                                <td><?php echo date('jS M Y', strtotime($row['postDate'])); ?></td>
                                                                <td><?php echo $row['postViews']; ?></td>
                                                                <td><?php echo $moderator['firstName'] . " " . $moderator['lastName']; ?></td>
                                                                <td><?php echo $postController['firstName'] . " " . $postController['lastName']; ?></td>
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
</body>

</html>