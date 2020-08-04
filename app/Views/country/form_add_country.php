		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url('country') ?>"><?= $title ?></a></li>
				<li class="breadcrumb-item active">Add New</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><?= $title ?> <small>Add New</small></h1>
			<!-- end page-header -->
			<!-- begin panel -->
			<div class="panel panel-inverse">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">Add New <?= $title ?></h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<form action="<?php echo base_url('country/add') ?>" method="post">
						<?php
						$error1 = $validation->getError('country_alpha2_code');
						$error2 = $validation->getError('country_alpha3_code');
						$error3 = $validation->getError('country_numeric_code');
						?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Code</label>
							<div class="col-md-3">
								<input type="text" class="form-control <?php if($error1){ echo 'is-invalid'; } ?>" name="country_alpha2_code" placeholder="Alpha-2" value="<?= $request->getPost('country_alpha2_code'); ?>" data-toggle="tooltip" data-placement="bottom" title="Two-character country code based on ISO 3166 (e.g., ID)." />
								<?php if($error1){ echo '<div class="invalid-feedback">'.$error1.'</div>'; } ?>
							</div>
							<div class="col-md-3">
								<input type="text" class="form-control <?php if($error2){ echo 'is-invalid'; } ?>" name="country_alpha3_code" placeholder="Alpha-3" value="<?= $request->getPost('country_alpha3_code'); ?>" data-toggle="tooltip" data-placement="bottom" title="Three-character country code based on ISO 3166 (e.g., IDN)." />
								<?php if($error2){ echo '<div class="invalid-feedback">'.$error2.'</div>'; } ?>
							</div>
							<div class="col-md-3">
								<input type="text" class="form-control <?php if($error3){ echo 'is-invalid'; } ?>" name="country_numeric_code" placeholder="Numeric" value="<?= $request->getPost('country_numeric_code'); ?>" data-toggle="tooltip" data-placement="bottom" title="Three-character country numeric code based on ISO 3166 (e.g., 360)." />
								<?php if($error3){ echo '<div class="invalid-feedback">'.$error3.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('country_name'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Name<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Country name based on ISO 3166 (e.g., Indonesia)."></i></span></label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="country_name" value="<?= $request->getPost('country_name'); ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('country_capital'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Capital<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Capital of the country (e.g., Jakarta)."></i></span></label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="country_capital" value="<?= $request->getPost('country_capital'); ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('country_demonym'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Demonym<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Demonym of the country (e.g., Indonesians)."></i></span></label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="country_demonym" value="<?= $request->getPost('country_demonym'); ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('country_area'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Total Area<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" data-html="true" title="Total area in km<sup>2</sup> (e.g., 1904569)."></i></span></label>
							<div class="col-md-9">
								<div class="input-group">
									<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="country_area" value="<?= $request->getPost('country_area'); ?>" />
									<div class="input-group-append"><span class="input-group-text">km<sup>2</sup></span></div>
									<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
								</div>
							</div>
						</div>
						<?php $error = $validation->getError('idd_code'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">IDD Code<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="The IDD prefix to call the city from another country (e.g., 62)."></i></span></label>
							<div class="col-md-9">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text">+</span></div>
									<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="idd_code" value="<?= $request->getPost('idd_code'); ?>" />
									<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
								</div>
							</div>
						</div>
						<?php $error = $validation->getError('cctld'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">ccTLD<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Country-Code Top-Level Domain (e.g., id)."></i></span></label>
							<div class="col-md-9">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text">.</span></div>
									<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="cctld" value="<?= $request->getPost('cctld'); ?>" />
									<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
								</div>
							</div>
						</div>
						<?php $error = $validation->getError('currency'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Currency<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Currency of the country."></i></span></label>
							<div class="col-md-9">
								<select class="default-select2 form-control <?php if($error){ echo 'is-invalid'; } ?>" name="currency" data-placeholder="Select a currency">
								<?php if(@$currency) : ?>
									<option></option>
								<?php foreach ($currency as $curr) : ?>
									<option value="<?= $curr->currency_id; ?>" <?php if($request->getPost('currency') == $curr->currency_id){echo 'selected';} ?>><?= $curr->currency_code.' - '.$curr->currency_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('language'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Language<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Language of the country."></i></span></label>
							<div class="col-md-9">
								<select class="default-select2 form-control <?php if($error){ echo 'is-invalid'; } ?>" name="language" data-placeholder="Select a language">
								<?php if(@$language) : ?>
									<option></option>
								<?php foreach ($language as $lang) : ?>
									<option value="<?= $lang->lang_id; ?>" <?php if($request->getPost('language') == $lang->lang_id){echo 'selected';} ?>><?= $lang->lang_code.' - '.$lang->lang_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<div class="form-group row m-b-0">
							<div class="col-md-12 col-sm-12 text-center">
								<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>&nbsp;&nbsp;
								<a href="<?php echo base_url('country') ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Back</a>
							</div>
						</div>
					</form>
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
		</div>
		<!-- end #content -->