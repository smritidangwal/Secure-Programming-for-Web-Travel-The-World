<?php
require('./webHandler.php');
require('./config/class.paginator.php');
if (!isset($_GET['text'])) {
    header('Location: index.php');
    exit;
}
$searchField = htmlspecialchars(strip_tags($_GET['text']));
try {
    $stmt = $db->prepare('SELECT count(postId) FROM post WHERE postPublished = 1 AND (postTitle like :searchField OR postDescrip like :searchField) ORDER BY postDate DESC');
    $stmt->execute(array(':searchField' => '%' . $searchField . '%'));
    $total = $stmt->fetchColumn();
    if ($total) {
        $limit = 5;
        $pages = ceil($total / $limit);
        $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
            'options' => array(
                'default'   => 1,
                'min_range' => 1,
            ),
        )));
        $offset = ($page - 1)  * $limit;
        $start = $offset + 1;
        $end = min(($offset + $limit), $total);
        $sql = 'SELECT * FROM post WHERE postPublished = 1 AND (postTitle like :searchField OR postDescrip like :searchField) ORDER BY postDate DESC LIMIT :limit OFFSET :offset';
        $stmt2 = $db->prepare($sql);
        $tagParam = '%' . $searchField . '%';
        $stmt2->bindParam(':searchField', $tagParam, PDO::PARAM_STR);
        $stmt2->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt2->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt2->execute();
        $latestPosts = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    echo "Some Error occurred while processing your request. Please try again after some time.";
    exit;
}
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
                <?php
                try {
                    if ($total > 0) {
                ?>
                        <div class="col-lg-8 mb-5 mb-lg-0">
                            <div class="blog_left_sidebar">

                                <h4 class="p-3 text-center">Search results for: <?php echo $searchField; ?></h4>
                                <?php
                                foreach ($latestPosts as $post) {
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
                                            <!-- <ul class="blog-info-link">
                                            <li><a href="#"><i class="fa fa-user"></i> Travel, Lifestyle</a></li>
                                            <li><a href="#"><i class="fa fa-comments"></i> 03 Comments</a></li>
                                        </ul> -->
                                        </div>
                                    </article>
                                <?php } ?>

                                <nav class="blog-pagination justify-content-center d-flex">
                                    <ul class="pagination">
                                        <?php
                                        if ($page > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="search.php?text=' . $searchField . '&page=1" title="First">&laquo; </a></li>';
                                            echo '<li class="page-item"><a class="page-link" href="search.php?text=' . $searchField . '&page=' . ($page - 1) . '" title="Previous">&lsaquo; </a></li>';
                                        }
                                        echo "<p class='p-2'>Displaying $start - $end of $total results.</p>";
                                        if ($page < $pages) {
                                            echo '<li class="page-item"><a class="page-link" href="search.php?text=' . $searchField . '&page=' . ($page + 1) . '" title="Next"> &rsaquo;</a></li>';
                                            echo '<li class="page-item"><a class="page-link" href="search.php?text=' . $searchField . '&page=' . $pages . '" title="Last"> &raquo;</a></li>';
                                        }
                                        ?>
                                    </ul>
                                </nav>

                            </div>
                        </div>
                        <?php include('sidebar.php'); ?>
                    <?php } else {
                    ?>
                        <div class="col-12 text-center">
                            <h4 class="p-3">We are sorry but no results found for your search.</h4>
                            <a href="index.php"><button class="btn btn-dark genric-btn">Go to Home</button></a>
                        </div>
                    <?php
                    } ?>
                <?php
                } catch (Exception $e) {
                    echo "Some Error occurred while processing your request. Please try again after some time.";
                    exit;
                }
                ?>
            </div>
        </div>
    </section>
    <!--================Blog Area =================-->
    <?php jsFooter(); ?>
</body>

</html>