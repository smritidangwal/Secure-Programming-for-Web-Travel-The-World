<div class="col-lg-4">
    <div class="blog_right_sidebar">
        <aside class="single_sidebar_widget search_widget">
            <form id="searchFormSide" action="#" class="search-form" method="post" role="search">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <input type="search" class="form-control" name="searchTextSide" id="searchTextSide" placeholder='Search Keyword'>
                        <div class="input-group-append">
                            <button class="btn" id="searchFormSideButton" type="submit"><i class="ti-search"></i></button>
                        </div>
                    </div>
                </div>
                <button class="button rounded-0 primary-bg text-white w-100 btn_1 boxed-btn" type="submit">Search</button>
            </form>
        </aside>

        <?php
        $popularPosts = getPopularPosts(7);
        if (count($popularPosts)) { ?>
            <aside class="single_sidebar_widget popular_post_widget">
                <h3 class="widget_title">Popular Posts</h3>

                <?php foreach ($popularPosts as $post) { ?>
                    <div class="m-2">
                        <?php
                        $imgContents = base64_encode(file_get_contents('./assets/postImages/' . $post['postId'] . "." . $post['postImage']));
                        ?>
                        <img class="img p-2" src="data:image/<?php echo $post['postImage']; ?>;base64,<?php echo $imgContents; ?>" alt="<?php echo $post['postTitle']; ?>">
                        <div class="media post_item m-2">
                            <div class="media-body">
                                <a href="viewPost.php?id=<?php echo $post['postId']; ?>">
                                    <h3><?php echo $post['postTitle']; ?></h3>
                                </a>
                                <p><?php echo date('M dS, Y', strtotime($post['postDate'])); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="p-2"></div>

                <?php } ?>
            </aside>
        <?php } ?>
    </div>
</div>