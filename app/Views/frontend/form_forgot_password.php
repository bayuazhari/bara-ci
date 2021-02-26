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
					Forgot Password
					<small>Enter your e-mail address below and we will send you instructions how to recover a password.</small>
				</h1>
				<!-- end register-header -->
				<!-- begin register-content -->
				<div class="register-content">
					<?php
						$success_alert = session()->getFlashdata('success');
						$info_alert = session()->getFlashdata('info');
						$warning_alert = session()->getFlashdata('warning');
						$danger_alert = session()->getFlashdata('danger');
						if($success_alert OR $info_alert OR $warning_alert OR $danger_alert){
							if($success_alert){
								$alert_color = 'success';
								$alert_message = '<strong>Success!</strong> '.$success_alert;
							}elseif($info_alert){
								$alert_color = 'info';
								$alert_message = '<strong>Info!</strong> '.$info_alert;
							}elseif($warning_alert){
								$alert_color = 'warning';
								$alert_message = '<strong>Warning!</strong> '.$warning_alert;
							}elseif($danger_alert){
								$alert_color = 'danger';
								$alert_message = '<strong>Error!</strong> '.$danger_alert;
							}
					?>
					<div class="alert alert-<?= $alert_color ?>">
						<button class="close" data-dismiss="alert">&times;</button>
						<?= $alert_message ?>
					</div>
					<?php } ?>
					<form action="<?php echo base_url('forgot_password'); ?>" method="post" class="margin-bottom-0">
						<?php $error = $validation->getError('user_email'); ?>
						<label class="control-label">Email</label>
						<div class="row m-b-15">
							<div class="col-md-12">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="user_email" placeholder="Email Address" value="<?= $request->getPost('user_email'); ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('g-recaptcha-response'); ?>
						<div class="m-b-15">
							<div class="g-recaptcha <?php if($error){ echo 'is-invalid'; } ?>" data-sitekey="<?= @$setting->getSettingById(18)->setting_value; ?>"></div>
							<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
						</div>
						<div class="register-buttons">
							<button type="submit" class="btn btn-primary btn-block btn-lg">Request</button>
						</div>
						<div class="m-t-30 m-b-30 p-b-30">
							<a class="btn btn-default btn-sm" href="<?php echo base_url('login'); ?>"><i class="fa fa-arrow-alt-circle-left"></i> Back to Login</a>
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