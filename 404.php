<?php
require('./webHandler.php');
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <title>Travel The World | Not Found</title>
    <?php head(); ?>
</head>

<body>
    <!-- header-start -->
    <?php topNavigation(); ?>
    <!-- header-end -->

    <!-- ================ contact section start ================= -->
    <section class="contact-section">
        <div class="container text-center">
            <h3 class="text-center">Looks like you're lost.</h3>
            <a href="index.php"><button class="btn btn-secondary">Go to Home</button></a>
            <div class="shareExperienceSection">
                <p class="text-white">Presents you all best travel experiences of the world.</p>
                <a href="users/"><button class="btn btn-dark m-2"> Login to share your own experience</button></a>
            </div>
        </div>
    </section>
    <?php jsFooter(); ?>
</body>

</html>