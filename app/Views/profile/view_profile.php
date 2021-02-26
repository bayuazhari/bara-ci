		<!-- begin #content -->
		<div id="content" class="content content-full-width">
			<!-- begin profile -->
			<div class="profile">
				<div class="profile-header">
					<!-- BEGIN profile-header-cover -->
					<div class="profile-header-cover"></div>
					<!-- END profile-header-cover -->
					<!-- BEGIN profile-header-content -->
					<div class="profile-header-content">
					<?php
						if(@$user->user_photo_name){
							$user_photo = $user->user_id.'/'.$user->user_photo_name;
						}else{
							$user_photo = 'user-0.png';
						}
					?>
						<!-- BEGIN profile-header-img -->
						<div class="profile-header-img">
							<img src="<?php echo base_url('assets/img/user/'.$user_photo); ?>" alt="">
						</div>
						<!-- END profile-header-img -->
						<!-- BEGIN profile-header-info -->
						<div class="profile-header-info">
							<h4 class="mt-0 mb-1"><?= @$user->first_name.' '.@$user->last_name ?></h4>
							<p class="mb-2"><?= @$user->level_name ?></p>
							<a href="<?php echo base_url('profile/edit') ?>" class="btn btn-xs btn-yellow <?php if(@$checkLevel->update == 0){ echo 'disabled'; } ?>">Edit Profile</a>
						</div>
						<!-- END profile-header-info -->
					</div>
					<!-- END profile-header-content -->
					<?php
						if($request->getPost('current_password') OR $request->getPost('new_password') OR $request->getPost('confirm_password') OR session()->getFlashdata('warning')){
							$header_tab1 = '';
							$tab_pane1 = '';

							$header_tab2 = 'active';
							$tab_pane2 = 'show active';
						}else{
							$header_tab1 = 'active';
							$tab_pane1 = 'show active';

							$header_tab2 = '';
							$tab_pane2 = '';
						}
					?>
					<!-- BEGIN profile-header-tab -->
					<ul class="profile-header-tab nav nav-tabs">
						<li class="nav-item"><a href="#profile-about" class="nav-link <?= $header_tab1; ?>" data-toggle="tab">About</a></li>
						<li class="nav-item"><a href="#profile-cpassword" class="nav-link <?= $header_tab2; ?>" data-toggle="tab">Change Password</a></li>
					</ul>
					<!-- END profile-header-tab -->
				</div>
			</div>
			<!-- end profile -->
			<!-- begin profile-content -->
			<div class="profile-content">
				<!-- begin tab-content -->
				<div class="tab-content p-0">
					<!-- begin #profile-about tab -->
					<div class="tab-pane fade <?= $tab_pane1; ?>" id="profile-about">
						<!-- begin table -->
						<div class="table-responsive form-inline">
							<table class="table table-profile">
								<thead>
									<tr>
										<th></th>
										<th>
											<h4><?= @$user->first_name.' '.@$user->last_name ?> <small><?= @$user->level_name ?></small></h4>
										</th>
									</tr>
								</thead>
								<tbody>
								<?php
									if($user->email_verification == 1){
										$email_verification = ' <i class="fa fa-check-circle text-success" title="Verified"></i>';
									}elseif($user->email_verification == 0){
										$email_verification = ' <i class="fa fa-exclamation-triangle text-warning" title="Not Verified"></i>';
									}else{
										$email_verification = '';
									}

									if($user->phone_verification == 1){
										$phone_verification = ' <i class="fa fa-check-circle text-success" title="Verified"></i>';
									}elseif($user->phone_verification == 0){
										$phone_verification = ' <i class="fa fa-exclamation-triangle text-warning" title="Not Verified"></i>';
									}else{
										$phone_verification = '';
									}
								?>
									<tr>
										<td class="field">Email</td>
										<td><i class="fa fa-envelope fa-lg m-r-5"></i> <?= @$user->user_email.$email_verification ?></td>
									</tr>
									<tr>
										<td class="field">Phone</td>
										<td><i class="fa fa-phone fa-lg m-r-5"></i> <?= @'+'.$user->country_calling_code.$user->user_phone.$phone_verification ?></td>
									</tr>
									<tr>
										<td class="field">Address</td>
										<td><i class="fa fa-home fa-lg m-r-5"></i> <?= @$user->user_address.', '.@$user->sdistrict_name.', '.@$user->district_name.', '.@$user->city_name.', '.@$user->state_name.', '.@$user->country_name.' '.@$user->zip_code ?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- end table -->
					</div>
					<!-- end #profile-about tab -->
					<!-- begin #profile-cpassword tab -->
					<div class="tab-pane fade <?= $tab_pane2; ?>" id="profile-cpassword">
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
						<div class="alert alert-<?= $alert_color ?> fade show m-b-0">
							<button class="close" data-dismiss="alert">&times;</button>
							<?= $alert_message ?>
						</div><br>
						<?php } ?>
						<form action="<?php echo base_url('profile') ?>" method="post">
						<?php $error = $validation->getError('current_password'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Current Password<span class="text-grey-darker ml-2"></span></label>
							<div class="col-md-9">
								<input type="password" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="current_password" placeholder="Current Password" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php
						$error1 = $validation->getError('new_password');
						$error2 = $validation->getError('confirm_password');
						?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">New Password</span></label>
							<div class="col-md-9">
								<div class="row">
									<div class="col-md-6">
										<input type="password" class="form-control <?php if($error1){ echo 'is-invalid'; } ?>" name="new_password" placeholder="New Password" data-toggle="tooltip" data-placement="bottom" title="New Password" />
										<?php if($error1){ echo '<div class="invalid-feedback">'.$error1.'</div>'; } ?>
									</div>
									<div class="col-md-6">
										<input type="password" class="form-control <?php if($error2){ echo 'is-invalid'; } ?>" name="confirm_password" placeholder="Confirm Password" data-toggle="tooltip" data-placement="bottom" title="Confirm Password" />
										<?php if($error2){ echo '<div class="invalid-feedback">'.$error2.'</div>'; } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group row m-b-0">
							<div class="col-md-12 col-sm-12 text-center">
								<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Change Password</button>
							</div>
						</div>
						</form>
					</div>
					<!-- end #profile-cpassword tab -->
				</div>
				<!-- end tab-content -->
			</div>
			<!-- end profile-content -->
		</div>
		<!-- end #content -->