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
						<div class="alert alert-warning fade show m-b-0">
							<button class="close" data-dismiss="alert">&times;</button>
							<?= $validation->listErrors() ?>
						</div>
						<div class="timeline-content">
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
										$error1 = $validation->getError('country_alpha2_code.*');
										$error2 = $validation->getError('country_alpha3_code.*');
										$error3 = $validation->getError('country_numeric_code.*');
										$error4 = $validation->getError('country_name.*');
										$error5 = $validation->getError('country_capital.*');
										$error6 = $validation->getError('country_demonym.*');
										$error7 = $validation->getError('country_area.*');
										$error8 = $validation->getError('idd_code.*');
										$error9 = $validation->getError('cctld.*');
										$error10 = $validation->getError('currency.*');
										$error11 = $validation->getError('language.*');

										$no=0;
										foreach ($country as $row) {
											$no++;
									?>
										<tr>
											<td class="<?php if($error1){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country_alpha2_code[]" value="<?= $row['alpha2_code'] ?>"><?= $row['alpha2_code'] ?></td>
											<td class="<?php if($error2){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country_alpha3_code[]" value="<?= $row['alpha3_code'] ?>"><?= $row['alpha3_code'] ?></td>
											<td class="<?php if($error3){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country_numeric_code[]" value="<?= $row['numeric_code'] ?>"><?= $row['numeric_code'] ?></td>
											<td class="<?php if($error4){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country_name[]" value="<?= $row['name'] ?>"><?= $row['name'] ?></td>
											<td class="<?php if($error5){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country_capital[]" value="<?= $row['capital'] ?>"><?= $row['capital'] ?></td>
											<td class="<?php if($error6){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country_demonym[]" value="<?= $row['demonym'] ?>"><?= $row['demonym'] ?></td>
											<td class="<?php if($error7){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="country_area[]" value="<?= $row['total_area'] ?>"><?= $row['total_area'] ?></td>
											<td class="<?php if($error8){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="idd_code[]" value="<?= $row['idd_code'] ?>"><?= $row['idd_code'] ?></td>
											<td class="<?php if($error9){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="cctld[]" value="<?= $row['cctld'] ?>"><?= $row['cctld'] ?></td>
											<td class="<?php if($error10){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="currency[]" value="<?= $row['currency'] ?>"><?= $row['currency'] ?></td>
											<td class="<?php if($error11){ echo 'bg-red text-white'; } ?>"><input type="hidden" name="language[]" value="<?= $row['language'] ?>"><?= $row['language'] ?></td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- end timeline-body -->
				</li>
				<?php } ?>
			</ul>
			<!-- end timeline -->
		</div>
		<!-- end #content -->