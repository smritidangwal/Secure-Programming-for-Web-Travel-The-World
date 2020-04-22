<?php
include_once '../functions.php';
checkIfNotAjaxRequest();
checkIfUserNotLoggedIn($handler = true);
checkIfRoleAuthorized('ADMIN', $handler = true);
checkValidReferer();
csrfTokenVerify('csrf-edit-post-token');
$_POST = array_map('htmlspecialchars', $_POST);
$_POST = array_map('strip_tags', $_POST);
if (isset($_POST['postId'])) {
    extract($_POST);
    $response = array();
    if (empty($_POST['postId'])) {
        sendAjaxResponse(1, 'Invalid data detected. Please refresh and try again.');
    } elseif (!is_numeric($postId)) {
        sendAjaxResponse(1, 'Invalid input found.');
    } else {
        try {
            $ktmt = $db->prepare("SELECT * FROM post WHERE postId = ?");
            $ktmt->execute([$postId]);
            $post = $ktmt->fetch();
            if ($post['postId']) {
                $postId = $post['postId'];
                $postTitle = $post['postTitle'];
                $postDescrip = $post['postDescrip'];
                $postContent = $post['postContent'];
                $csrfSavePostToken = password_hash(md5(uniqid(mt_rand(), true)) . $userDetails['username'], PASSWORD_BCRYPT);
                $_SESSION['csrf-save-post-token'] = $csrfSavePostToken;
                $responseData = <<<EOM
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0 text-dark">Post Edit</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="./">Home</a></li>
                                    <li class="breadcrumb-item"><a href="./pendingApproval.php">Pending Posts</a></li>
                                    <li class="breadcrumb-item active">Edit Post</li>
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
                            <form class="col-12" action="./phpHandler/saveEditPostHandler.php" method="POST" name="editPostForm" id="editPostForm" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Post Details</h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                                    <i class="fas fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="postTitle">Post Title <span class="mandatory">*</span></label>
                                                <input type='text' class="form-control" name='postTitle' id="postTitle" value="$postTitle" required>
                                                <input type='hidden' class="form-control" name='postId' value="$postId">
                                            </div>
                                            <div class="form-group">
                                                <label for="postDescText">Add Description <span class="mandatory">*</span></label>
                                                <textarea class="form-control" rows="3" name="postDescrp" id="postDescrp" placeholder="Place some description here">$postDescrip</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="postContText">Add Content (To add an image, drag & drop)<span class="mandatory">*</span></label>
                                                <textarea class="textarea form-control" rows="3" name="postCont" id="postCont" placeholder="Place post content here">$postContent</textarea>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->
                                <div class="col-12 form-group">
                                    <a href="./pendingApproval.php" class="btn btn-secondary">Cancel</a>
                                    <input type="hidden" value="$csrfSavePostToken" name="cToken" />
                                    <input type="submit" name="submitEditPostForm" id="submitEditPostForm" value="Save Post" class="btn btn-success float-right">
                                </div>
                            </form>
                        </div>
                        <!-- /.row (main row) -->
                    </div><!-- /.container-fluid -->
                </section>
                EOM;
                sendAjaxResponse(0, $responseData);
            } else {
                sendAjaxResponse(1, 'Error occurred while processing your request. Please try again.');
            }
        } catch (Exception $e) {
            sendAjaxResponse(-1, $e->getMessage(), $e->getCode());
        }
    }
} else {
    sendAjaxResponse(1, 'All required parameters not found. Refresh your page and try again.');
}
