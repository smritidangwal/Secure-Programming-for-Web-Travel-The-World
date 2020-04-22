<?php
require('./webHandler.php');
require('./config/class.paginator.php');
$latestPosts = getLatestPosts(5);
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <title>Travel The World</title>
    <?php head(); ?>
</head>

<body>
    <!-- header-start -->
    <?php topNavigation(); ?>
    <!-- header-end -->

    <!-- bradcam_area  -->
    <div class="bradcam_area bradcam_bg">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="bradcam_text text-center">
                        <h3>Travel The World</h3>
                        <div class="shareExperienceSection">
                            <p>Presents you all best travel experiences of the world.</p>
                            <a href="users/"><button class="btn btn-dark m-2"> Login to share your own experience</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ bradcam_area  -->


    <!--================Blog Area =================-->
    <section class="blog_area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="blog_left_sidebar">

                        <?php
                        try {
                            $pages = new Paginator('5', 'p');
                            $stmt = $db->prepare('SELECT count(*) FROM post where postPublished = 1');
                            $stmt->execute();
                            //pass number of records to
                            $rowCount = $stmt->fetchColumn();
                            if ($rowCount) {
                                $pages->set_total($rowCount);
                                $stmt = $db->prepare('SELECT * FROM post where postPublished = 1 ORDER BY postDate DESC ' . $pages->get_limit());
                                $stmt->execute();
                                while ($post = $stmt->fetch()) {
                        ?>
                                    <article class="blog_item">
                                        <div class="blog_item_img">
                                            <?php
                                            $imgContents = base64_encode(file_get_contents('./assets/postImages/' . $post['postId'] . "." . $post['postImage']));
                                            ?>
                                            <img class="card-img rounded-0" src="data:image/<?php echo $post['postImage']; ?>;base64,<?php echo $imgContents; ?>" alt="<?php echo $post['postTitle']; ?>">
                                            <a href="viewPost.php?id=<?php echo $post['postId']; ?>" class="blog_item_date">
                                                <h3><?php echo date('d', strtotime($post['postDate'])); ?></h3>
                                                <p><?php echo date('M', strtotime($post['postDate'])); ?>'<?php echo date('y', strtotime($post['postDate'])); ?></p>
                                            </a>
                                        </div>

                                        <div class="blog_details">
                                            <a class="d-inline-block" href="viewPost.php?id=<?php echo $post['postId']; ?>">
                                                <h2><?php echo $post['postTitle']; ?></h2>
                                            </a>
                                            <p><?php echo $post['postDescrip']; ?></p>
                                        </div>
                                    </article>
                                <?php } ?>

                                <nav class="blog-pagination justify-content-center d-flex">
                                    <ul class="pagination">
                                        <?php echo $pages->page_links(); ?>
                                    </ul>
                                </nav>

                            <?php } ?>
                        <?php
                        } catch (Exception $e) {
                            echo "Some Error occurred. Please try again.";
                            exit;
                        }
                        ?>

                    </div>
                </div>
                <?php include('sidebar.php'); ?>
            </div>
        </div>
    </section>
    <!--================Blog Area =================-->
    <?php jsFooter(); ?>
</body>

</html>