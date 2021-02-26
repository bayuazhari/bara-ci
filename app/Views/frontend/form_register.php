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
					Sign Up
					<small>Create your <?= $web_title; ?> Account.</small>
				</h1>
				<!-- end register-header -->
				<!-- begin register-content -->
				<div class="register-content">
					<form action="<?php echo base_url('register'); ?>" method="post" class="margin-bottom-0">
						<?php
						$error1 = $validation->getError('first_name');
						$error2 = $validation->getError('last_name');
						?>
						<label class="control-label">Name</label>
						<div class="row row-space-10">
							<div class="col-md-6 m-b-15">
								<input type="text" class="form-control <?php if($error1){ echo 'is-invalid'; } ?>" name="first_name" placeholder="First Name" value="<?= $request->getPost('first_name'); ?>" />
								<?php if($error1){ echo '<div class="invalid-feedback">'.$error1.'</div>'; } ?>
							</div>
							<div class="col-md-6 m-b-15">
								<input type="text" class="form-control <?php if($error2){ echo 'is-invalid'; } ?>" name="last_name" placeholder="Last Name" value="<?= $request->getPost('last_name'); ?>" />
								<?php if($error2){ echo '<div class="invalid-feedback">'.$error2.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('user_email'); ?>
						<label class="control-label">Email</label>
						<div class="row m-b-15">
							<div class="col-md-12">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="user_email" placeholder="Email Address" value="<?= $request->getPost('user_email'); ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php
						$error1 = $validation->getError('country_calling_code');
						$error2 = $validation->getError('user_phone');
						?>
						<label class="control-label">Phone</label>
						<div class="row m-b-15">
							<div class="col-md-12">
								<div class="input-group">
									<div class="input-group-prepend">
										<select class="default-select2 form-control <?php if($error1){ echo 'is-invalid'; } ?>" name="country_calling_code" data-placeholder="Select a IDD code">
										<?php if(@$country_calling_code) :
											foreach ($country_calling_code as $idd) : ?>
											<option value="<?= $idd->idd_code; ?>" <?php if($request->getPost('country_calling_code') == $idd->idd_code){echo 'selected';} ?>><?= '+'.$idd->idd_code ?></option>
										<?php
											endforeach;
										endif;
										?>
										</select>
									</div>
									<input type="text" class="form-control <?php if($error2){ echo 'is-invalid'; } ?>" name="user_phone" placeholder="Phone Number" value="<?= $request->getPost('user_phone'); ?>" />
									<?php if($error2){ echo '<div class="invalid-feedback">'.$error2.'</div>'; } ?>
								</div>
							</div>
						</div>
						<?php
						$error1 = $validation->getError('user_password');
						$error2 = $validation->getError('user_repassword');
						?>
						<label class="control-label">Password</label>
						<div class="row row-space-10">
							<div class="col-md-6 m-b-15">
								<input type="password" class="form-control <?php if($error1){ echo 'is-invalid'; } ?>" name="user_password" placeholder="Password" />
								<?php if($error1){ echo '<div class="invalid-feedback">'.$error1.'</div>'; } ?>
							</div>
							<div class="col-md-6 m-b-15">
								<input type="password" class="form-control <?php if($error2){ echo 'is-invalid'; } ?>" name="user_repassword" placeholder="Confirm Password" />
								<?php if($error2){ echo '<div class="invalid-feedback">'.$error2.'</div>'; } ?>
							</div>
						</div>
						<?php
						$error1 = $validation->getError('user_address');
						$error2 = $validation->getError('country');
						$error3 = $validation->getError('state');
						$error4 = $validation->getError('city');
						$error5 = $validation->getError('district');
						$error6 = $validation->getError('sub_district');
						?>
						<label class="control-label">Address</label>
						<div class="row row-space-10 m-b-15">
							<div class="col-md-12">
								<textarea class="form-control <?php if($error1){ echo 'is-invalid'; } ?>" name="user_address" placeholder="User Address"><?= $request->getPost('user_address'); ?></textarea>
								<?php if($error1){ echo '<div class="invalid-feedback">'.$error1.'</div>'; } ?>
							</div>
							<div class="col-md-6">
								<select class="default-select2 form-control <?php if($error2){ echo 'is-invalid'; } ?>" id="country" name="country" data-placeholder="Select a country">
								<?php if(@$country) : ?>
									<option></option>
								<?php foreach ($country as $coun) : ?>
									<option value="<?= $coun->country_id; ?>" <?php if($request->getPost('country') == $coun->country_id){echo 'selected';} ?>><?= $coun->country_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<?php if($error2){ echo '<div class="invalid-feedback">'.$error2.'</div>'; } ?>
							</div>
							<div class="col-md-6">
								<select class="default-select2 form-control <?php if($error3){ echo 'is-invalid'; } ?>" id="state" name="state" data-placeholder="Select a state">
								<?php if(@$state) : ?>
									<option></option>
								<?php foreach ($state as $stt) : ?>
									<option value="<?= $stt->state_id; ?>" <?php if($request->getPost('state') == $stt->state_id){echo 'selected';} ?>><?= $stt->state_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<div id="state_loading" style="margin-top: 7px;">
									<img src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/bootstrap4-editable/img/loading.gif'); ?>"> <small>Loading...</small>
								</div>
								<?php if($error3){ echo '<div class="invalid-feedback">'.$error3.'</div>'; } ?>
							</div>
							<div class="col-md-6">
								<select class="default-select2 form-control <?php if($error4){ echo 'is-invalid'; } ?>" id="city" name="city" data-placeholder="Select a city">
								<?php if(@$city) : ?>
									<option></option>
								<?php foreach ($city as $ct) : ?>
									<option value="<?= $ct->city_id; ?>" <?php if($request->getPost('city') == $ct->city_id){echo 'selected';} ?>><?= $ct->city_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<div id="city_loading" style="margin-top: 7px;">
									<img src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/bootstrap4-editable/img/loading.gif'); ?>"> <small>Loading...</small>
								</div>
								<?php if($error4){ echo '<div class="invalid-feedback">'.$error4.'</div>'; } ?>
							</div>
							<div class="col-md-6">
								<select class="default-select2 form-control <?php if($error5){ echo 'is-invalid'; } ?>" id="district" name="district" data-placeholder="Select a district">
								<?php if(@$district) : ?>
									<option></option>
								<?php foreach ($district as $dist) : ?>
									<option value="<?= $dist->district_id; ?>" <?php if($request->getPost('district') == $dist->district_id){echo 'selected';} ?>><?= $dist->district_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<div id="district_loading" style="margin-top: 7px;">
									<img src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/bootstrap4-editable/img/loading.gif'); ?>"> <small>Loading...</small>
								</div>
								<?php if($error5){ echo '<div class="invalid-feedback">'.$error5.'</div>'; } ?>
							</div>
							<div class="col-md-6">
								<select class="default-select2 form-control <?php if($error6){ echo 'is-invalid'; } ?>" id="sub_district" name="sub_district" data-placeholder="Select a sub district">
								<?php if(@$sub_district) : ?>
									<option></option>
								<?php foreach ($sub_district as $sdist) : ?>
									<option value="<?= $sdist->sdistrict_id; ?>" <?php if($request->getPost('sub_district') == $sdist->sdistrict_id){echo 'selected';} ?>><?= $sdist->sdistrict_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<div id="sub_district_loading" style="margin-top: 7px;">
									<img src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/bootstrap4-editable/img/loading.gif'); ?>"> <small>Loading...</small>
								</div>
								<?php if($error6){ echo '<div class="invalid-feedback">'.$error6.'</div>'; } ?>
							</div>
							<div class="col-md-6">
								<input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="Zip Code" value="<?= $request->getPost('zip_code'); ?>" readonly />
							</div>
						</div>
						<?php $error = $validation->getError('user_photo'); ?>
						<label class="control-label">Photo <span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="Files must be less than <strong>2 MB</strong>.<br>Allowed file types: <strong>png jpg gif</strong>."></i></span></label>
						<div class="row m-b-15">
							<div class="col-md-12">
								<input type="file" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="user_photo" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('g-recaptcha-response'); ?>
						<div class="m-b-15">
							<div class="g-recaptcha <?php if($error){ echo 'is-invalid'; } ?>" data-sitekey="<?= @$setting->getSettingById(18)->setting_value; ?>"></div>
							<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
						</div>
						<!--<div class="checkbox checkbox-css m-b-30">
							<div class="checkbox checkbox-css m-b-30">
								<input type="checkbox" id="agreement_checkbox" value="">
								<label for="agreement_checkbox">
								By clicking Sign Up, you agree to our <a href="javascript:;">Terms</a> and that you have read our <a href="javascript:;">Data Policy</a>, including our <a href="javascript:;">Cookie Use</a>.
								</label>
							</div>
						</div>-->
						<div class="register-buttons">
							<button type="submit" class="btn btn-primary btn-block btn-lg">Sign Up</button>
						</div>
						<div class="m-t-30 m-b-30 p-b-30">
							Already a member? Click <a href="<?php echo base_url('login'); ?>">here</a> to login.
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