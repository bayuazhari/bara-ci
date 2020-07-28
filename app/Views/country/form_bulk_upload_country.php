		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url('country') ?>"><?= $title ?></a></li>
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
							<span class="views"><a href="<?php echo base_url('country') ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-circle-left"></i> Back</a></span>
						</div>
						<div class="timeline-content">
							<a href="<?php echo base_url('assets/templates/countries.csv') ?>" class="btn btn-info btn-block"><i class="fa fa-download"></i> Download CSV Template</a>
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
							<span class="username">Add country info in CSV template</span>
						</div>
						<div class="timeline-content">
							<p>
								Required fields are alpha2_code, alpha3_code, numeric_code, and name.
							</p>
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-td-valign-middle">
									<thead>
										<tr>
											<th class="text-nowrap">alpha2_code</th>
											<th class="text-nowrap">alpha3_code</th>
											<th class="text-nowrap">numeric_code</th>
											<th class="text-nowrap">name</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>ID</td>
											<td>IDN</td>
											<td>360</td>
											<td>Indonesia</td>
										</tr>
									</tbody>
								</table>
							</div>
							<p>
								Description:<br>
								<strong>alpha2_code</strong> - Two-character country code based on ISO 3166 (e.g., ID).<br>
								<strong>alpha3_code</strong> - Three-character country code based on ISO 3166 (e.g., IDN).<br>
								<strong>numeric_code</strong> - Three-character country numeric code based on ISO 3166 (e.g., 360).<br>
								<strong>name</strong> - Country name based on ISO 3166 (e.g., Indonesia).<br>
								<strong>capital</strong> - Capital of the country (e.g., Jakarta).<br>
								<strong>demonym</strong> - Demonym of the country (e.g., Indonesians).<br>
								<strong>total_area</strong> - Total area in km<sup>2</sup> (e.g., 1904569).<br>
								<strong>idd_code</strong> - The IDD prefix to call the city from another country (e.g., 62).<br>
								<strong>cctld</strong> - Country-Code Top-Level Domain (e.g., id).<br>
								<strong>currency</strong> - Currency code based on ISO 4217 (e.g., IDR).<br>
								<strong>language</strong> - Language code based on ISO 639 (e.g., ID).
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
								The upload countries file has fields separated by a comma only. The first line contains the valid field names. The rest of the lines (records) contain information about each country.<br>
								<strong>Tip:</strong> Avoid special characters in field information like quotes or other commas. Test a file with only one record before a large upload. You can use a spread sheet program to create the file with the required columns and fields. Then save the file as "CSV (comma delimited)". These files can be opened with simple text editors (e.g., Notepad++) for verification.
							</p>
						</div>
						<div class="timeline-comment-box">
							<div class="input">
								<form action="<?php echo base_url('country/bulk_upload') ?>" method="post" enctype="multipart/form-data">
									<?php $error = $validation->getError('country_csv'); ?>
									<div class="input-group">
										<input type="file" name="country_csv" class="form-control rounded-corner <?php if($error){ echo 'is-invalid'; } ?>" />
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
				<?php if(@$country) { ?>
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
							<form action="<?php echo base_url('country/bulk_save') ?>" method="post">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-td-valign-middle">
									<thead class="text-center">
										<tr>
											<th class="text-nowrap" colspan="3">Code</th>
											<th class="text-nowrap" rowspan="2">Name</th>
											<th class="text-nowrap" rowspan="2">Capital</th>
											<th class="text-nowrap" rowspan="2">Demonym</th>
											<th class="text-nowrap" rowspan="2">Total Area</th>
											<th class="text-nowrap" rowspan="2">IDD Code</th>
											<th class="text-nowrap" rowspan="2">ccTLD</th>
											<th class="text-nowrap" rowspan="2">Currency</th>
											<th class="text-nowrap" rowspan="2">Language</th>
										</tr>
										<tr>
											<th class="text-nowrap">Alpha 2</th>
											<th class="text-nowrap">Alpha 3</th>
											<th class="text-nowrap">Numeric</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$no=0;
										foreach ($country as $row) {
											$country_alpha2_code = $model->getCountryByField('country_alpha2_code', $row['alpha2_code']);
											$country_alpha3_code = $model->getCountryByField('country_alpha3_code', $row['alpha3_code']);
											$country_numeric_code = $model->getCountryByField('country_numeric_code', $row['numeric_code']);
											$currency_code = $model->getCurrencyByField('currency_code', $row['currency']);
											$lang_code = $model->getLanguageByField('lang_code', $row['language']);

											if(empty($row['alpha2_code']) OR (is_string($row['alpha2_code']) != 1) OR (strlen($row['alpha2_code']) != 2) OR @$country_alpha2_code){
												$alpha2_code_error = true;
											}else{
												$alpha2_code_error = false;
											}
											$check_errors[] = $alpha2_code_error;

											if(empty($row['alpha3_code']) OR (is_string($row['alpha3_code']) != 1) OR (strlen($row['alpha3_code']) != 3) OR @$country_alpha3_code){
												$alpha3_code_error = true;
											}else{
												$alpha3_code_error = false;
											}
											$check_errors[] = $alpha3_code_error;

											if(empty($row['numeric_code']) OR (is_numeric($row['numeric_code']) != 1) OR (strlen($row['numeric_code']) != 3) OR @$country_numeric_code){
												$numeric_code_error = true;
											}else{
												$numeric_code_error = false;
											}
											$check_errors[] = $numeric_code_error;

											if(empty($row['name'])){
												$name_error = true;
											}else{
												$name_error = false;
											}
											$check_errors[] = $name_error;

											if(!empty($row['total_area']) AND (is_numeric($row['total_area']) != 1)){
												$total_area_error = true;
											}else{
												$total_area_error = false;
											}
											$check_errors[] = $total_area_error;

											if(!empty($row['idd_code']) AND ((is_numeric($row['idd_code']) != 1) OR (strlen($row['idd_code']) >= 5))){
												$idd_code_error = true;
											}else{
												$idd_code_error = false;
											}
											$check_errors[] = $idd_code_error;

											if(!empty($row['currency']) AND !$currency_code){
												$currency_error = true;
											}else{
												$currency_error = false;
											}
											$check_errors[] = $currency_error;

											if(!empty($row['language']) AND !$lang_code){
												$language_error = true;
											}else{
												$language_error = false;
											}
											$check_errors[] = $language_error;
									?>
										<tr>
											<td class="<?php if($alpha2_code_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country[<?= $no ?>][country_alpha2_code]" value="<?= $row['alpha2_code'] ?>"><?= $row['alpha2_code'] ?></td>
											<td class="<?php if($alpha3_code_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country[<?= $no ?>][country_alpha3_code]" value="<?= $row['alpha3_code'] ?>"><?= $row['alpha3_code'] ?></td>
											<td class="<?php if($numeric_code_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country[<?= $no ?>][country_numeric_code]" value="<?= $row['numeric_code'] ?>"><?= $row['numeric_code'] ?></td>
											<td class="<?php if($name_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country[<?= $no ?>][country_name]" value="<?= $row['name'] ?>"><?= $row['name'] ?></td>
											<td><input type="hidden" name="country[<?= $no ?>][country_capital]" value="<?= $row['capital'] ?>"><?= $row['capital'] ?></td>
											<td><input type="hidden" name="country[<?= $no ?>][country_demonym]" value="<?= $row['demonym'] ?>"><?= $row['demonym'] ?></td>
											<td class="<?php if($total_area_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country[<?= $no ?>][country_area]" value="<?= $row['total_area'] ?>"><?= $row['total_area'] ?></td>
											<td class="<?php if($idd_code_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country[<?= $no ?>][idd_code]" value="<?= $row['idd_code'] ?>"><?= $row['idd_code'] ?></td>
											<td><input type="hidden" name="country[<?= $no ?>][cctld]" value="<?= $row['cctld'] ?>"><?= $row['cctld'] ?></td>
											<td class="<?php if($currency_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country[<?= $no ?>][currency_id]" value="<?= @$currency_code->currency_id ?>"><?= $row['currency'] ?></td>
											<td class="<?php if($language_error == true){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country[<?= $no ?>][lang_id]" value="<?= @$lang_code->lang_id ?>"><?= $row['language'] ?></td>
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