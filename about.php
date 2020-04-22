<?php
require('./webHandler.php');
$latestPosts = getLatestPosts(3);
$publishedPostCount = getPublishedPostCount();
$authorCount = getAuthorCount();
$totalViewsCount = getTotalViewsCount();
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
                        <h3>About Us</h3>
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

    <div class="about_story">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="story_heading">
                        <h3>Our Story</h3>
                    </div>
                    <div class="row">
                        <div class="col-lg-11 offset-lg-1">
                            <div class="story_info">
                                <div class="row">
                                    <div class="col-lg-9">
                                        <p>We help people share their travel experiences to the world. We encourage people to write their own blog in their free time without having to maintain the blog and share their experiences</p>
                                        <p>This blog is maintained by Travel the world. Travek the world aims to inspire people to start sharing their own experiences by simply registering to the website and writing their travel history. As people have less time today, we aim to give a platform to travellers to start writing their own travel blog.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="story_thumb">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="thumb padd_1">
                                            <img src="assets/images/about/1.png" alt="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="thumb">
                                            <img src="assets/images/about/2.png" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="counter_wrap">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4">
                                        <div class="single_counter text-center">
                                            <h3 class="counter"><?php echo $publishedPostCount; ?></h3>
                                            <p>experiences have been shared</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <div class="single_counter text-center">
                                            <h3 class="counter"><?php echo $totalViewsCount; ?></h3>
                                            <p>people have seen experiences shared altogether</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <div class="single_counter text-center">
                                            <h3 class="counter"><?php echo $authorCount; ?></h3>
                                            <p>authors posted their experiences</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="video_area video_bg overlay">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="video_wrap text-center">
                        <h3>Enjoy Video</h3>
                        <div class="video_icon">
                            <a class="popup-video video_play_button" href="https://www.youtube.com/watch?v=f59dDEk57i0">
                                <i class="fa fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($latestPosts)) { ?>

        <div class="recent_trip_area">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="section_title text-center mb_70">
                            <h3>Recent Experiences</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php foreach ($latestPosts as $post) { ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="single_trip">
                                <div class="thumb">
                                    <?php
                                    $imgContents = base64_encode(file_get_contents('./assets/postImages/' . $post['postId'] . "." . $post['postImage']));
                                    ?>
                                    <img src="data:image/<?php echo $post['postImage']; ?>;base64,<?php echo $imgContents; ?>" alt="<?php echo $post['postTitle']; ?>">
                                </div>
                                <div class="info">
                                    <div class="date">
                                        <span><?php echo date('M dS, Y', strtotime($post['postDate'])); ?></span>
                                    </div>
                                    <a href="viewPost.php?id=<?php echo $post['postId']; ?>">
                                        <h3><?php echo $post['postTitle']; ?></h3>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    <?php } ?>

    <?php jsFooter(); ?>
</body>

</html>