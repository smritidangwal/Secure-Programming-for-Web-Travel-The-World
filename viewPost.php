<?php
require('./webHandler.php');
if (!isset($_GET['id'])) {
   header('Location: index.php');
   exit;
}
if (!is_numeric($_GET['id'])) {
   header('Location: index.php');
   exit;
}
$postId = $_GET['id'];
updatePostView($postId);
$post = getPostById($postId);
if (!$post['postId']) {
   header('Location: index.php');
   exit;
}
$authorRow = getAuthorById($post['postModerator']);
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
   <section class="blog_area single-post-area section-padding contentScrollEvent">
      <div class="container">
         <div class="row">
            <div class="col-lg-8 posts-list">
               <div class="single-post">
                  <div class="feature-img">
                     <?php
                     $imgContents = base64_encode(file_get_contents('./assets/postImages/' . $post['postId'] . "." . $post['postImage']));
                     ?>
                     <img class="img-fluid img-pop-up" src="data:image/<?php echo $post['postImage']; ?>;base64,<?php echo $imgContents; ?>" alt="<?php echo $post['postTitle']; ?>">
                  </div>
                  <div class="blog_details">
                     <h2><?php echo htmlspecialchars_decode($post['postTitle']); ?></h2>
                     <ul class="blog-info-link mt-3 mb-4">
                        <!-- <li><a href="#"><i class="fa fa-user"></i> Travel, Lifestyle</a></li>
                        <li><a href="#"><i class="fa fa-comments"></i> 03 Comments</a></li> -->
                        <li><i class="fas fa-clock"></i> <?php echo date('M dS, Y', strtotime($post['postDate'])); ?></li>
                     </ul>
                     <div class="quote-wrapper text-justify">
                        <?php echo htmlspecialchars_decode($post['postDescrip']); ?>
                     </div>
                     <div class="p-1">
                        <?php echo htmlspecialchars_decode($post['postContent']); ?>
                     </div>
                     <div class="m-5">
                     </div>
                  </div>
               </div>
               <div class="navigation-top">
                  <div class="d-sm-flex justify-content-between text-center">
                     <p class="like-info"><span class="align-middle"><i class="fas fa-eye"></i></span> <?php echo $post['postViews']; ?> Views</p>
                     <div class="col-sm-4 text-center my-2 my-sm-0">
                        <!-- <p class="comment-count"><span class="align-middle"><i class="fa fa-comment"></i></span> 06 Comments</p> -->
                     </div>
                     <ul class="social-icons">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-dribbble"></i></a></li>
                        <li><a href="#"><i class="fab fa-behance"></i></a></li>
                     </ul>
                  </div>
                  <div class="navigation-area">
                     <div class="row">
                        <?php
                        $postPrev = getPreviousPost($post['postId']);
                        if ($postPrev) {
                        ?>
                           <div class="col-lg-6 col-md-6 col-12 nav-left flex-row d-flex justify-content-start align-items-center">
                              <div class="thumb">
                                 <a href="viewPost.php?id=<?php echo $postPrev['postId']; ?>">
                                    <?php
                                    $imgContents = base64_encode(file_get_contents('./assets/postImages/' . $postPrev['postId'] . "." . $postPrev['postImage']));
                                    ?>
                                    <img class="prevNextImg img-fluid" src="data:image/<?php echo $post['postImage']; ?>;base64,<?php echo $imgContents; ?>" alt="<?php echo $postPrev['postTitle']; ?>">
                                 </a>
                              </div>
                              <div class="arrow">
                                 <a href="viewPost.php?id=<?php echo $postPrev['postId']; ?>">
                                    <span class="lnr text-white ti-arrow-left"></span>
                                 </a>
                              </div>
                              <div class="detials">
                                 <p>Prev Post</p>
                                 <a href="viewPost.php?id=<?php echo $postPrev['postId']; ?>">
                                    <h4><?php echo $postPrev['postTitle']; ?></h4>
                                 </a>
                              </div>
                           </div>
                        <?php
                        } else {
                        ?>
                           <div class="col-lg-6 col-md-6 col-12 nav-left flex-row d-flex justify-content-start align-items-center">
                              <div class="detials">
                                 <p>No Previous Post</p>
                              </div>
                           </div>
                        <?php
                        }
                        $postNext = getNextPost($post['postId']);
                        if ($postNext) {
                        ?>
                           <div class="col-lg-6 col-md-6 col-12 nav-right flex-row d-flex justify-content-end align-items-center">
                              <div class="detials">
                                 <p>Next Post</p>
                                 <a href="viewPost.php?id=<?php echo $postNext['postId']; ?>">
                                    <h4><?php echo $postNext['postTitle']; ?></h4>
                                 </a>
                              </div>
                              <div class="arrow">
                                 <a href="viewPost.php?id=<?php echo $postNext['postId']; ?>">
                                    <span class="lnr text-white ti-arrow-right"></span>
                                 </a>
                              </div>
                              <div class="thumb">
                                 <a href="viewPost.php?id=<?php echo $postNext['postId']; ?>">
                                    <?php
                                    $imgContents = base64_encode(file_get_contents('./assets/postImages/' . $postNext['postId'] . "." . $postNext['postImage']));
                                    ?>
                                    <img class="prevNextImg img-fluid" src="data:image/<?php echo $post['postImage']; ?>;base64,<?php echo $imgContents; ?>" alt="<?php echo $postNext['postTitle']; ?>">
                                 </a>
                              </div>
                           </div>
                        <?php
                        } else {
                        ?>
                           <div class="col-lg-6 col-md-6 col-12 nav-right flex-row d-flex justify-content-end align-items-center">
                              <div class="detials">
                                 <p class="text-right">No Next Post</p>
                              </div>
                           </div>
                        <?php
                        }
                        ?>
                     </div>
                  </div>
               </div>
               <div class="blog-author">
                  <div class="media align-items-center">
                     <img src="assets/images/author.jpg" alt="">
                     <div class="media-body">
                        <a href="#">
                           <h4><?php echo $authorRow['firstName'] . " " . $authorRow['lastName']; ?></h4>
                        </a>
                        <p><?php echo $authorRow['bio']; ?></p>
                     </div>
                  </div>
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