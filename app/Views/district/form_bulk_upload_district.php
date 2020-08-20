		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url('district') ?>"><?= $title ?></a></li>
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
							<span class="views"><a href="<?php echo base_url('district') ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-circle-left"></i> Back</a></span>
						</div>
						<div class="timeline-content">
							<a href="<?php echo base_url('assets/templates/districts.csv') ?>" class="btn btn-info btn-block"><i class="fa fa-download"></i> Download CSV Template</a>
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
							<span class="username">Add district info in CSV template</span>
						</div>
						<div class="timeline-content">
							<p>
								Required fields are code, name, and city.
							</p>
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-td-valign-middle">
									<thead>
										<tr>
											<th class="text-nowrap">code</th>
											<th class="text-nowrap">name</th>
											<th class="text-nowrap">city</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>3171010</td>
											<td>Jagakarsa</td>
											<td>3171</td>
										</tr>
									</tbody>
								</table>
							</div>
							<p>
								Description:<br>
								<strong>code</strong> - Seven-character district code based on the laws used in a country (e.g., 3171010).<br>
								<strong>name</strong> - District name (e.g., Jagakarsa).<br>
								<strong>city</strong> - City of the district (e.g., 3171).
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
								The upload districts file has fields separated by a comma only. The first line contains the valid field names. The rest of the lines (records) contain information about each district.<br>
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
								<form action="<?php echo base_url('district/bulk_upload') ?>" method="post" enctype="multipart/form-data">
									<?php $error = $validation->getError('district_csv'); ?>
									<div class="input-group">
										<input type="file" name="district_csv" class="form-control rounded-corner <?php if($error){ echo 'is-invalid'; } ?>" />
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
				<?php if(@$district) { ?>
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
							<form action="<?php echo base_url('district/bulk_save') ?>" method="post">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-td-valign-middle">
									<thead class="text-center">
										<tr>
											<th class="text-nowrap">Code</th>
											<th class="text-nowrap">Name</th>
											<th class="text-nowrap">City</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$no=0;
										foreach ($district as $row) {
											$district_code = $model->getDistrictByField('district_code', @$row['code']);
											$city_code = $model->getCityByField('city_code', @$row['city']);

											if(empty(@$row['code']) OR (is_numeric(@$row['code']) != 1) OR (strlen(@$row['code']) != 7) OR @$district_code){
												$code_error = true;
											}else{
												$code_error = false;
											}
											$check_errors[] = $code_error;

											if(empty(@$row['name'])){
												$name_error = true;
											}else{
												$name_error = false;
											}
											$check_errors[] = $name_error;

											if(!empty(@$row['city']) AND !$city_code){
												$city_error = true;
											}else{
												$city_error = false;
											}
											$check_errors[] = $city_error;
									?>
										<tr>
											<td class="<?php if($code_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="district[<?= $no ?>][district_code]" value="<?= @$row['code'] ?>"><?= @$row['code'] ?></td>
											<td class="<?php if($name_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="district[<?= $no ?>][district_name]" value="<?= @$row['name'] ?>"><?= @$row['name'] ?></td>
											<td class="<?php if($city_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="district[<?= $no ?>][city]" value="<?= @$city_code->city_id ?>"><?= @$row['city'] ?></td>
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