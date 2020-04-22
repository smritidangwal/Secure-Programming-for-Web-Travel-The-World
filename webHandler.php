<?php
require('./config/config.php');
function head()
{
?>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge,chrome=1">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Site Verificaiton -->
    <meta name="google-site-verification" content="UzWUEAxK-epT1qNpDIv3rGzdXS9qcWIHmzdL-0vF4pg">

    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">

    <!-- CSS here -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/themify-icons.css">
    <link rel="stylesheet" href="./assets/css/flaticon.css">
    <link rel="stylesheet" href="./assets/css/animate.css">
    <link rel="stylesheet" href="./assets/css/slicknav.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/custom.css">
<?php
}
function topNavigation()
{
?>
    <header>
        <div class="header-area ">
            <div id="sticky-header" class="main-header-area">
                <div class="container-fluid">
                    <div class="header_bottom_border">
                        <div class="row align-items-center">
                            <div class="col-xl-2 col-lg-2">
                                <div class="logo">
                                    <a href="./">
                                        <img src="./assets/images/logo.png" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="main-menu  d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li><a href="index.php">Home</a></li>
                                            <li><a href="about.php">About</a></li>
                                            <li><a href="contact.php">Contact</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 d-none d-lg-block">
                                <div class="social_wrap d-flex align-items-center justify-content-end">
                                    <div class="number">
                                        <p> <i class="fa fa-phone"></i> +3535 899 643526</p>
                                    </div>
                                    <div class="social_links d-none d-xl-block">
                                        <ul>
                                            <li><a href="#"> <i class="fa fa-instagram"></i> </a></li>
                                            <li><a href="#"> <i class="fa fa-linkedin"></i> </a></li>
                                            <li><a href="#"> <i class="fa fa-facebook"></i> </a></li>
                                            <li><a href="#"> <i class="fa fa-pinterest"></i> </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="seach_icon">
                                <a data-toggle="modal" data-target="#exampleModalCenter" href="#">
                                    <i class="fa fa-search"></i>
                                </a>
                            </div>
                            <div class="col-12">
                                <div class="mobile_menu d-block d-lg-none"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>
<?php
}
function jsFooter()
{
?>
    <!-- footer start -->
    <footer class="footer">
        <div class="footer_top">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="footer_widget m-5">
                            <div class="footer_logo text-center">
                                <a href="#">
                                    <img src="./assets/images/logo.png" alt="">
                                </a>
                                <h3 class="text-white m-2 p-2">Travel The World</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="footer_widget p-3">
                            <h3 class="footer_title">
                                Contact Us
                                <hr class=" bg-dark" />
                            </h3>
                            <p> City Centre Dublin <br> Dublin, Ireland <br>
                                <a href="#">+353 899 643526 </a> <br>
                                <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a>
                            </p>
                            <div class="socail_links text-center">
                                <ul>
                                    <li><a href="#"> <i class="fa fa-instagram"></i> </a></li>
                                    <li><a href="#"> <i class="fa fa-linkedin"></i> </a></li>
                                    <li><a href="#"> <i class="fa fa-facebook"></i> </a></li>
                                    <li><a href="#"> <i class="fa fa-pinterest"></i> </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="footer_widget p-3">
                            <h3 class="footer_title">
                                Quick Links
                                <hr class=" bg-dark" />
                            </h3>
                            <ul class="links">
                                <li><a href="index.php">Home</a></li>
                                <li><a href="about.php">About</a></li>
                                <li><a href="contact.php"> Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copy-right_text">
            <div class="container">
                <div class="footer_border"></div>
                <div class="row">
                    <div class="col-xl-12">
                        <p class="copy_right text-center">
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;<?php echo date('Y'); ?> All rights reserved | Travel The World | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--/ footer end  -->

    <!-- Modal -->
    <div class="modal fade custom_search_pop" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="serch_form">
                    <form id="searchForm" action="javascript:void(0)" class="search-form" method="POST" role="search">
                        <input type="text" placeholder="Search" name="searchText" id="searchText">
                        <button type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- JS here -->
    <script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/ajax-form.js"></script>
    <script src="./assets/js/waypoints.min.js"></script>
    <script src="./assets/js/jquery.counterup.min.js"></script>
    <script src="./assets/js/scrollIt.js"></script>
    <script src="./assets/js/jquery.scrollUp.min.js"></script>
    <script src="./assets/js/jquery.slicknav.min.js"></script>
    <script src="./assets/js/plugins.js"></script>

    <!--contact js-->
    <script src="./assets/js/contact.js"></script>
    <script src="./assets/js/jquery.ajaxchimp.min.js"></script>
    <script src="./assets/js/jquery.form.js"></script>
    <script src="./assets/js/jquery.validate.min.js"></script>

    <script src="./assets/js/main.js"></script>
<?php
}
function getLatestPosts($limit)
{
    global $db;
    $stmt = $db->prepare('SELECT * FROM post WHERE postPublished = 1 ORDER BY postDate DESC LIMIT :limit');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getPopularPosts($limit)
{
    global $db;
    $stmt = $db->prepare('SELECT * FROM post WHERE postPublished = 1 ORDER BY postViews DESC LIMIT :limit');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getPostById($id)
{
    global $db;
    if (is_numeric($id)) {
        $stmt = $db->prepare('select * from post where postId = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}
function updatePostView($id)
{
    global $db;
    $stmt = $db->prepare('UPDATE post SET postViews = postViews+1 WHERE postId = :postId');
    $stmt->execute(array(':postId' => $id));
}
function getAuthorById($id)
{
    global $db;
    $stmt = $db->prepare('SELECT * FROM admin WHERE memberID = :memberID');
    $stmt->execute(array(':memberID' => $id));
    return $stmt->fetch();
}
function getPreviousPost($id)
{
    if (is_numeric($id)) {
        global $db;
        $stmt = $db->prepare('SELECT postTitle, postId, postImage FROM post where postPublished = 1 AND postId < :currentPostId ORDER BY postId DESC');
        $stmt->execute(array(':currentPostId' => $id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return null;
    }
}
function getNextPost($id)
{
    if (is_numeric($id)) {
        global $db;
        $stmt = $db->prepare('SELECT postTitle, postId, postImage FROM post where postPublished = 1 AND postId > :currentPostId ORDER BY postId');
        $stmt->execute(array(':currentPostId' => $id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return null;
    }
}
function getPublishedPostCount()
{
    global $db;
    $stmt = $db->prepare('SELECT count(*) as cnt FROM post where postPublished = 1');
    $stmt->execute();
    return $stmt->fetch()['cnt'];
}
function getAuthorCount()
{
    global $db;
    $stmt = $db->prepare('SELECT count(distinct(postModerator)) as cnt FROM post where postPublished = 1');
    $stmt->execute();
    return $stmt->fetch()['cnt'];
}
function getTotalViewsCount()
{
    global $db;
    $stmt = $db->prepare('SELECT sum(postViews) as cnt FROM post where postPublished = 1');
    $stmt->execute();
    return $stmt->fetch()['cnt'];
}
