		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url('user') ?>"><?= $title ?></a></li>
				<li class="breadcrumb-item active">Bulk Upload</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><?= $title ?> <small>Bulk Upload</small></h1>
			<!-- end page-header -->
			<!-- begin timeline -->
			<ul class="timeline">
				<li>
					<!-- begin timeline-time -->
					<div class="timeline-time">
						<h1>1</h1>
					</div>
					<!-- end timeline-time -->
					<!-- begin timeline-icon -->
					<div class="timeline-icon">
						<a href="javascript:;">&nbsp;</a>
					</div>
					<!-- end timeline-icon -->
					<!-- begin timeline-body -->
					<div class="timeline-body">
						<div class="timeline-header">
							<span class="username">Download CSV file</span>
							<span class="views"><a href="<?php echo base_url('user') ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-circle-left"></i> Back</a></span>
						</div>
						<div class="timeline-content">
							<a href="<?php echo base_url('assets/templates/users.csv') ?>" class="btn btn-info btn-block"><i class="fa fa-download"></i> Download CSV Template</a>
						</div>
					</div>
					<!-- end timeline-body -->
				</li>
				<li>
					<!-- begin timeline-time -->
					<div class="timeline-time">
						<h1>2</h1>
					</div>
					<!-- end timeline-time -->
					<!-- begin timeline-icon -->
					<div class="timeline-icon">
						<a href="javascript:;">&nbsp;</a>
					</div>
					<!-- end timeline-icon -->
					<!-- begin timeline-body -->
					<div class="timeline-body">
						<div class="timeline-header">
							<span class="username">Add user info in CSV template</span>
						</div>
						<div class="timeline-content">
							<p>
								Required field is name.
							</p>
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-td-valign-middle">
									<thead>
										<tr>
											<th class="text-nowrap">first_name</th>
											<th class="text-nowrap">last_name</th>
											<th class="text-nowrap">email</th>
											<th class="text-nowrap">calling_code</th>
											<th class="text-nowrap">phone</th>
											<th class="text-nowrap">password</th>
											<th class="text-nowrap">address</th>
											<th class="text-nowrap">sub_district</th>
											<th class="text-nowrap">level</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>John</td>
											<td>Doe</td>
											<td>johndoe@example.com</td>
											<td>62</td>
											<td>81234567890</td>
											<td>(input user password)</td>
											<td>Jl. Lapangan Banteng Utara No. 1</td>
											<td>3173071001</td>
											<td>L12100001</td>
										</tr>
									</tbody>
								</table>
							</div>
							<p>
								Description:<br>
								<strong>first_name</strong> - First name (e.g., John).<br>
								<strong>last_name</strong> - Last name (e.g., Doe).<br>
								<strong>email</strong> - User email (e.g., johndoe@example.com).<br>
								<strong>calling_code</strong> - Country calling code (e.g., 62).<br>
								<strong>phone</strong> - User phone (e.g., 81234567890).<br>
								<strong>password</strong> - User password.<br>
								<strong>address</strong> - User address (e.g., Jl. Lapangan Banteng Utara No. 1).<br>
								<strong>sub_district</strong> - Sub district (e.g., 3173071001)<br>
								<strong>level</strong> - Level of the user (e.g., L12100001)
							</p>
						</div>
					</div>
					<!-- end timeline-body -->
				</li>
				<li>
					<!-- begin timeline-time -->
					<div class="timeline-time">
						<h1>3</h1>
					</div>
					<!-- end timeline-time -->
					<!-- begin timeline-icon -->
					<div class="timeline-icon">
						<a href="javascript:;">&nbsp;</a>
					</div>
					<!-- end timeline-icon -->
					<!-- begin timeline-body -->
					<div class="timeline-body">
						<div class="timeline-header">
							<span class="username">Upload CSV file</span>
						</div>
						<div class="timeline-content">
							<p>
								The upload users file has fields separated by a comma only. The first line contains the valid field names. The rest of the lines (records) contain information about each user.<br>
								<strong>Tip:</strong> Avoid special characters in field information like quotes or other commas. Test a file with only one record before a large upload. You can use a spread sheet program to create the file with the required columns and fields. Then save the file as "CSV (comma delimited)". These files can be opened with simple text editors (e.g., Notepad++) for verification.
							</p>
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
							</div>
							<?php } ?>
						</div>
						<div class="timeline-comment-box">
							<div class="input">
								<form action="<?php echo base_url('user/bulk_upload') ?>" method="post" enctype="multipart/form-data">
									<?php $error = $validation->getError('user_csv'); ?>
									<div class="input-group">
										<input type="file" name="user_csv" class="form-control rounded-corner <?php if($error){ echo 'is-invalid'; } ?>" />
										<span class="input-group-btn p-l-10">
										<button class="btn btn-primary f-s-12 rounded-corner" type="submit"><i class="fa fa-upload"></i> Upload</button>
										</span>
										<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- end timeline-body -->
				</li>
				<?php if(@$user) { ?>
				<li>
					<!-- begin timeline-time -->
					<div class="timeline-time">
						<h1>4</h1>
					</div>
					<!-- end timeline-time -->
					<!-- begin timeline-icon -->
					<div class="timeline-icon">
						<a href="javascript:;">&nbsp;</a>
					</div>
					<!-- end timeline-icon -->
					<!-- begin timeline-body -->
					<div class="timeline-body">
						<div class="timeline-header">
							<span class="username">CSV preview</span>
						</div>
						<div class="timeline-content">
							<form action="<?php echo base_url('user/bulk_save') ?>" method="post">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-td-valign-middle">
									<thead class="text-center">
										<tr>
											<th class="text-nowrap">First Name</th>
											<th class="text-nowrap">Last Name</th>
											<th class="text-nowrap">Email</th>
											<th class="text-nowrap">Calling Code</th>
											<th class="text-nowrap">Phone</th>
											<th class="text-nowrap">Password</th>
											<th class="text-nowrap">Address</th>
											<th class="text-nowrap">Sub District</th>
											<th class="text-nowrap">Level</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$no=0;
										foreach ($user as $row) {
											$user_email = $model->getUserByField('user_email', @$row['email']);
											$user_phone = $model->getUserByField('user_phone', @$row['phone']);
											$sdistrict_code = $model->getSubDistrictByField('sdistrict_code', @$row['sub_district']);
											$level_id = $model->getLevelByField('level_id', @$row['level']);

											if(empty(@$row['first_name'])){
												$first_name_error = true;
											}else{
												$first_name_error = false;
											}
											$check_errors[] = $first_name_error;

											if(empty(@$row['last_name'])){
												$last_name_error = true;
											}else{
												$last_name_error = false;
											}
											$check_errors[] = $last_name_error;

											if(empty(@$row['email']) OR @$user_email){
												$email_error = true;
											}else{
												$email_error = false;
											}
											$check_errors[] = $email_error;

											if(empty(@$row['calling_code']) OR (is_numeric(@$row['calling_code']) != 1)){
												$calling_code_error = true;
											}else{
												$calling_code_error = false;
											}
											$check_errors[] = $calling_code_error;

											if(empty(@$row['phone']) OR (is_numeric(@$row['phone'])) != 1 OR @$user_phone){
												$phone_error = true;
											}else{
												$phone_error = false;
											}
											$check_errors[] = $phone_error;

											if(empty(@$row['password']) OR (strlen(@$row['password']) < 6)){
												$password_error = true;
											}else{
												$password_error = false;
											}
											$check_errors[] = $password_error;

											if(empty(@$row['address'])){
												$address_error = true;
											}else{
												$address_error = false;
											}
											$check_errors[] = $address_error;

											if(empty(@$row['sub_district']) OR !$sdistrict_code){
												$sdistrict_error = true;
											}else{
												$sdistrict_error = false;
											}
											$check_errors[] = $sdistrict_error;

											if(empty(@$row['level']) OR !$level_id){
												$level_error = true;
											}else{
												$level_error = false;
											}
											$check_errors[] = $level_error;
									?>
										<tr>
											<td class="<?php if($first_name_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="user[<?= $no; ?>][first_name]" value="<?= @$row['first_name'] ?>"><?= @$row['first_name'] ?></td>
											<td class="<?php if($last_name_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="user[<?= $no; ?>][last_name]" value="<?= @$row['last_name'] ?>"><?= @$row['last_name'] ?></td>
											<td class="<?php if($email_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="user[<?= $no; ?>][user_email]" value="<?= @$row['email'] ?>"><?= @$row['email'] ?></td>
											<td class="<?php if($calling_code_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="user[<?= $no; ?>][country_calling_code]" value="<?= @$row['calling_code'] ?>"><?= @$row['calling_code'] ?></td>
											<td class="<?php if($phone_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="user[<?= $no; ?>][user_phone]" value="<?= @$row['phone'] ?>"><?= @$row['phone'] ?></td>
											<td class="<?php if($password_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="user[<?= $no; ?>][user_password]" value="<?= @$row['password'] ?>"><?= @$row['password'] ?></td>
											<td class="<?php if($address_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="user[<?= $no; ?>][user_address]" value="<?= @$row['address'] ?>"><?= @$row['address'] ?></td>
											<td class="<?php if($sdistrict_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="user[<?= $no; ?>][sub_district]" value="<?= @$sdistrict_code->sdistrict_id ?>"><?= @$row['sub_district'] ?></td>
											<td class="<?php if($level_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="user[<?= $no; ?>][level]" value="<?= @$row['level'] ?>"><?= @$row['level'] ?></td>
										</tr>
									<?php $no++; } ?>
									</tbody>
								</table>
							</div>
						<?php if(in_array(true, $check_errors) != 1){ ?>
							<br>
							<div class="col-md-12 col-sm-12 text-center">
								<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
							</div>
						<?php } ?>
							</form>
						</div>
					</div>
					<!-- end timeline-body -->
				</li>
				<?php } ?>
			</ul>
			<!-- end timeline -->
		</div>
		<!-- end #content -->