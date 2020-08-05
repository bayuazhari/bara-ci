		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url('state') ?>"><?= $title ?></a></li>
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
							<span class="views"><a href="<?php echo base_url('state') ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-circle-left"></i> Back</a></span>
						</div>
						<div class="timeline-content">
							<a href="<?php echo base_url('assets/templates/states.csv') ?>" class="btn btn-info btn-block"><i class="fa fa-download"></i> Download CSV Template</a>
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
							<span class="username">Add state info in CSV template</span>
						</div>
						<div class="timeline-content">
							<p>
								Required fields are iso_code, ref_code, name, time_zone, and geo_unit.
							</p>
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-td-valign-middle">
									<thead>
										<tr>
											<th class="text-nowrap">iso_code</th>
											<th class="text-nowrap">ref_code</th>
											<th class="text-nowrap">name</th>
											<th class="text-nowrap">time_zone</th>
											<th class="text-nowrap">geo_unit</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>JK</td>
											<td>31</td>
											<td>DKI Jakarta</td>
											<td>Asia/Jakarta</td>
											<td>JW</td>
										</tr>
									</tbody>
								</table>
							</div>
							<p>
								Description:<br>
								<strong>iso_code</strong> - Two-character state code based on ISO 3166 (e.g., JK).<br>
								<strong>ref_code</strong> - Two-character state reference code based on the laws used in a country (e.g., 31).<br>
								<strong>name</strong> - State name based on ISO 3166 (e.g., DKI Jakarta).<br>
								<strong>capital</strong> - Capital of the state (e.g., Jakarta).<br>
								<strong>time_zone</strong> - Time zone in the state (e.g., Asia/Jakarta).<br>
								<strong>geo_unit</strong> - Geographical unit of the state (e.g., JW).
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
								The upload states file has fields separated by a comma only. The first line contains the valid field names. The rest of the lines (records) contain information about each state.<br>
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
								<form action="<?php echo base_url('state/bulk_upload') ?>" method="post" enctype="multipart/form-data">
									<?php $error = $validation->getError('state_csv'); ?>
									<div class="input-group">
										<input type="file" name="state_csv" class="form-control rounded-corner <?php if($error){ echo 'is-invalid'; } ?>" />
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
				<?php if(@$state) { ?>
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
							<form action="<?php echo base_url('state/bulk_save') ?>" method="post">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-td-valign-middle">
									<thead class="text-center">
										<tr>
											<th class="text-nowrap" colspan="2">Code</th>
											<th class="text-nowrap" rowspan="2">Name</th>
											<th class="text-nowrap" rowspan="2">Capital</th>
											<th class="text-nowrap" rowspan="2">Time Zone</th>
											<th class="text-nowrap" rowspan="2">Geo Unit</th>
										</tr>
										<tr>
											<th class="text-nowrap">ISO</th>
											<th class="text-nowrap">Ref.</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$no=0;
										foreach ($state as $row) {
											$state_iso_code = $model->getStateByField('state_iso_code', @$row['iso_code']);
											$state_ref_code = $model->getStateByField('state_ref_code', @$row['ref_code']);
											$time_zone_name = $model->getTimeZoneByField('tz_name', @$row['time_zone']);
											$geo_unit_code = $model->getGeoUnitByField('geo_unit_code', @$row['geo_unit']);

											if(empty(@$row['iso_code']) OR (is_string(@$row['iso_code']) != 1) OR (strlen(@$row['iso_code']) != 2) OR @$state_iso_code){
												$iso_code_error = true;
											}else{
												$iso_code_error = false;
											}
											$check_errors[] = $iso_code_error;

											if(empty(@$row['ref_code']) OR (is_string(@$row['ref_code']) != 1) OR (strlen(@$row['ref_code']) != 2) OR @$state_ref_code){
												$ref_code_error = true;
											}else{
												$ref_code_error = false;
											}
											$check_errors[] = $ref_code_error;

											if(empty(@$row['name'])){
												$name_error = true;
											}else{
												$name_error = false;
											}
											$check_errors[] = $name_error;

											if(!empty(@$row['time_zone']) AND !$time_zone_name){
												$time_zone_error = true;
											}else{
												$time_zone_error = false;
											}
											$check_errors[] = $time_zone_error;

											if(!empty(@$row['geo_unit']) AND !$geo_unit_code){
												$geo_unit_error = true;
											}else{
												$geo_unit_error = false;
											}
											$check_errors[] = $geo_unit_error;
									?>
										<tr>
											<td class="<?php if($iso_code_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="state[<?= $no ?>][state_iso_code]" value="<?= @$row['iso_code'] ?>"><?= @$row['iso_code'] ?></td>
											<td class="<?php if($ref_code_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="state[<?= $no ?>][state_ref_code]" value="<?= @$row['ref_code'] ?>"><?= @$row['ref_code'] ?></td>
											<td class="<?php if($name_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="state[<?= $no ?>][state_name]" value="<?= @$row['name'] ?>"><?= @$row['name'] ?></td>
											<td><input type="hidden" name="state[<?= $no ?>][state_capital]" value="<?= @$row['capital'] ?>"><?= @$row['capital'] ?></td>
											<td class="<?php if($time_zone_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="state[<?= $no ?>][time_zone]" value="<?= @$time_zone_name->tz_id ?>"><?= @$row['time_zone'] ?></td>
											<td class="<?php if($geo_unit_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="state[<?= $no ?>][geo_unit]" value="<?= @$geo_unit_code->geo_unit_id ?>"><?= @$row['geo_unit'] ?></td>
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