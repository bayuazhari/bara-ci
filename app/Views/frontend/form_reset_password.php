<!-- begin #page-container -->
	<div id="page-container" class="fade">
		<!-- begin register -->
		<div class="register register-with-news-feed">
			<!-- begin news-feed -->
			<div class="news-feed">
				<div class="news-image" style="background-image: url(<?php echo base_url('assets/img/login-bg/login-bg-15.jpg'); ?>)"></div>
				<div class="news-caption">
					<h4 class="caption-title"><?= $nav_brand ?></h4>
					<p>Download the <?= $web_title; ?> for iOS and Android™.</p>
				</div>
			</div>
			<!-- end news-feed -->
			<!-- begin right-content -->
			<div class="right-content">
				<!-- begin register-header -->
				<h1 class="register-header">
					Reset Password
					<small>A strong password helps prevent unauthorized access to your <?= $web_title; ?> account.</small>
				</h1>
				<!-- end register-header -->
				<!-- begin register-content -->
				<div class="register-content">
					<form action="<?php echo base_url('forgot_password/reset_password/'.@$user->user_id); ?>" method="post" class="margin-bottom-0">
						<label class="control-label"><?= $web_title; ?> Account</label>
						<div class="row m-b-15">
							<div class="col-md-12">
								<input type="text" class="form-control" value="<?= @$user->user_email; ?>" disabled />
							</div>
						</div>
						<?php $error = $validation->getError('user_password'); ?>
						<label class="control-label">New Password</label>
						<div class="row m-b-15">
							<div class="col-md-12">
								<input type="password" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="user_password" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('user_repassword'); ?>
						<label class="control-label">Confirm Password</label>
						<div class="row m-b-15">
							<div class="col-md-12">
								<input type="password" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="user_repassword" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<div class="register-buttons">
							<button type="submit" class="btn btn-primary btn-block btn-lg">Reset Password</button>
						</div>
						<hr />
						<p class="text-center mb-0">
							<?= @$setting->getSettingById(6)->setting_value; ?>
						</p>
					</form>
				</div>
				<!-- end register-content -->
			</div>
			<!-- end right-content -->
		</div>
		<!-- end register -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->