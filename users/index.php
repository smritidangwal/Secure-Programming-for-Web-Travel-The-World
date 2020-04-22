<?php
require_once('./functions.php');
checkIfUserNotLoggedIn($handler = false);
?>

<!DOCTYPE html>
<html>

<head>
	<title> Travel The World | Dashboard</title>
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
							<h1 class="m-0 text-dark">Dashboard</h1>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<!-- Small boxes (Stat box) -->
					<div class="row">
						<div class="col-md-5">
							<div class="small-box bg-gray p-3">
								<table class="table table-borderless m-0">
									<tr>
										<td>
											<h5 class="m-0">Name</h5>
										</td>
										<td>
											<h5 class="m-0"><?php echo $userDetails['firstName'] . " " . $userDetails['lastName']; ?></h5>
										</td>
									</tr>
									<tr>
										<td>
											<h5 class="m-0">Email</h5>
										</td>
										<td>
											<h5 class="m-0"><?php echo $userDetails['emailId']; ?></h5>
										</td>
									</tr>
									<tr>
										<td>
											<h5 class="m-0">Username</h5>
										</td>
										<td>
											<h5 class="m-0"><?php echo $userDetails['username']; ?></h5>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="col-md-7">
							<div class="small-box bg-gray p-3">
								<h5 class="p-1">Bio</h5> <?php echo $userDetails['bio']; ?>
							</div>
						</div>
					</div>
					<?php if ($isAdmin) {
						$userCount = getUserCount();
						$activePostCount = getActivePostCount();
						$totalPostCount = getTotalPostCount();
						$rejectedPostCount = getRejectedPostCount();
						$approvedPostCount = getApprovedPostCount();
						$pendingPostCount = getPendingPostCount();
						$popularPosts = getPopularPosts();
					?>
						<div class="row">
							<div class="col-6">
								<!-- small box -->
								<div class="small-box bg-danger">
									<div class="inner">
										<h3><?php echo $userCount; ?></h3>
										<p>Total Users</p>
									</div>
									<div class="icon">
										<i class="ion ion-person-add"></i>
									</div>
								</div>
							</div>
							<!-- ./col -->
							<div class="col-6">
								<!-- small box -->
								<div class="small-box bg-info">
									<div class="inner">
										<h3><?php echo $activePostCount; ?></h3>
										<p>Active Posts</p>
									</div>
									<div class="icon">
										<i class="ion ion-bag"></i>
									</div>
								</div>
							</div>
							<!-- ./col -->
						</div>
						<!-- /.row -->
						<!-- Small boxes (Stat box) -->
						<div class="row">
							<div class="col-lg-3 col-6">
								<!-- small box -->
								<div class="small-box bg-info">
									<div class="inner">
										<h3><?php echo $totalPostCount; ?></h3>
										<p>Total Posts</p>
									</div>
									<div class="icon">
										<i class="fas fa-book"></i>
									</div>
								</div>
							</div>
							<!-- ./col -->
							<div class="col-lg-3 col-6">
								<!-- small box -->
								<div class="small-box bg-success">
									<div class="inner">
										<h3><?php echo $approvedPostCount; ?></h3>
										<p>Approved Posts</p>
									</div>
									<div class="icon">
										<i class="fas fa-file-alt"></i>
									</div>
								</div>
							</div>
							<!-- ./col -->
							<div class="col-lg-3 col-6">
								<!-- small box -->
								<div class="small-box bg-warning">
									<div class="inner">
										<h3><?php echo $pendingPostCount; ?></h3>
										<p>Pending Posts</p>
									</div>
									<div class="icon">
										<i class="fas fa-shopping-bag"></i>
									</div>
								</div>
							</div>
							<!-- ./col -->
							<div class="col-lg-3 col-6">
								<!-- small box -->
								<div class="small-box bg-danger">
									<div class="inner">
										<h3><?php echo $rejectedPostCount; ?></h3>
										<p>Rejected Posts</p>
									</div>
									<div class="icon">
										<i class="fas fa-bars"></i>
									</div>
								</div>
							</div>
							<!-- ./col -->
						</div>
						<!-- /.row -->
						<!-- Main row -->
						<div class="row">
							<!-- Left col -->
							<div class="col-md-12">
								<div class="card card-primary">
									<!-- /.card-header -->
									<div class="card-header">
										<h3 class="card-title">Popular Posts</h3>
									</div>
									<div class="card-body p-0">
										<?php if (count($popularPosts)) { ?>
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
														</tr>
													</thead>
													<tbody>
														<?php
														foreach ($popularPosts as $post) {
															$moderator = getUserById($post['postModerator']);
														?>
															<tr>
																<td>
																	<a href="viewPost.php?id=<?php echo $post['postId']; ?>">
																		<button class="badge badge-primary">View</button>
																	</a>
																</td>
																<td><?php echo $post['postTitle']; ?></td>
																<td><?php echo $post['postDescrip']; ?></td>
																<td><?php echo date('jS M Y', strtotime($post['postDate'])); ?></td>
																<td><?php echo $post['postViews']; ?></td>
																<td><?php echo $moderator['firstName'] . " " . $moderator['lastName']; ?></td>
															</tr>
														<?php
														}
														?>
													</tbody>
												</table>
											</div>
											<!-- /.table-responsive -->
										<?php } else { ?>
											<div class="content-header">
												<h4 class="text-center text-gray">No posts have been published so far.</h4>
											</div>
										<?php } ?>
									</div>
									<!-- /.card-body -->
								</div>
								<!-- /.card -->
							</div>
						</div>
						<!-- /.col -->
					<?php } elseif ($isAuthor) {
						$userPosts = getApprovedNPendingUserPosts();
						$approvedUserPostCount = getApprovedUserPostCount();
						$pendingUserPostCount = getPendingUserPostCount();
					?>
						<div class="row">
							<div class="col-6">
								<!-- small box -->
								<div class="small-box bg-info">
									<div class="inner">
										<h3><?php echo $approvedUserPostCount; ?></h3>
										<p>Approved Posts</p>
									</div>
									<div class="icon">
										<i class="fas fa-file-alt"></i>
									</div>
								</div>
							</div>
							<!-- ./col -->
							<div class="col-6">
								<!-- small box -->
								<div class="small-box bg-danger">
									<div class="inner">
										<h3><?php echo $pendingUserPostCount; ?></h3>
										<p>Pending Posts</p>
									</div>
									<div class="icon">
										<i class="ion ion-edit"></i>
									</div>
								</div>
							</div>
							<!-- ./col -->
						</div>
						<!-- /.row -->
						<!-- Main row -->
						<div class="row">
							<!-- Left col -->
							<div class="col-md-12">
								<div class="card card-primary">
									<!-- /.card-header -->
									<div class="card-header">
										<h3 class="card-title">My Posts</h3>
									</div>
									<div class="card-body p-0">
										<?php if (count($userPosts)) { ?>
											<div class="table-responsive">
												<table class="table m-0" id="tableView">
													<thead>
														<tr>
															<th>Action</th>
															<th>Title</th>
															<th>Description</th>
															<th>Date</th>
															<th>Status</th>
															<th>Views</th>
														</tr>
													</thead>
													<tbody>
														<?php
														foreach ($userPosts as $post) {
														?>
															<tr>
																<td>
																	<a href="viewPost.php?id=<?php echo $post['postId']; ?>">
																		<button class="badge badge-primary">View</button>
																	</a>
																</td>
																<td><?php echo $post['postTitle']; ?></td>
																<td><?php echo $post['postDescrip']; ?></td>
																<td><?php echo date('jS M Y', strtotime($post['postDate'])); ?></td>
																<td>
																<?php if($post['postPublished']){
																?>
																	<span class="badge badge-success">Approved</span>
																<?php
																} else {
																?>
																	<span class="badge badge-warning">Pending</span>
																<?php
																}
																?>
																</td>
																<td><?php echo $post['postViews']; ?></td>
															</tr>
														<?php
														}
														?>
													</tbody>
												</table>
											</div>
											<!-- /.table-responsive -->
										<?php } else { ?>
											<div class="content-header">
												<h4 class="text-center text-gray">You don't have any approved posts.</h4>
											</div>
										<?php } ?>
									</div>
									<!-- /.card-body -->
								</div>
								<!-- /.card -->
							</div>
						</div>
						<!-- /.col -->
					<?php } ?>
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