		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url('state') ?>"><?= $title ?></a></li>
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
					<form action="<?php echo base_url('state/add') ?>" method="post">
						<?php $error = $validation->getError('country'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Country<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Country of the state."></i></span></label>
							<div class="col-md-9">
								<select class="default-select2 form-control <?php if($error){ echo 'is-invalid'; } ?>" id="country" name="country" data-placeholder="Select a country">
								<?php if(@$country) : ?>
									<option></option>
								<?php foreach ($country as $coun) : ?>
									<option value="<?= $coun->country_id; ?>" <?php if($request->getPost('country') == $coun->country_id){echo 'selected';} ?>><?= $coun->country_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php
						$error1 = $validation->getError('state_iso_code');
						$error2 = $validation->getError('state_ref_code');
						?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Code</label>
							<div class="col-md-5">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text" id="iso-prefix-code"><?= @$iso_prefix_code ?></span></div>
									<input type="text" class="form-control <?php if($error1){ echo 'is-invalid'; } ?>" name="state_iso_code" placeholder="ISO" value="<?= $request->getPost('state_iso_code'); ?>" data-toggle="tooltip" data-placement="bottom" title="Two-character state code based on ISO 3166 (e.g., JK)." />
									<?php if($error1){ echo '<div class="invalid-feedback">'.$error1.'</div>'; } ?>
								</div>
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control <?php if($error2){ echo 'is-invalid'; } ?>" name="state_ref_code" placeholder="Reference" value="<?= $request->getPost('state_ref_code'); ?>" data-toggle="tooltip" data-placement="bottom" title="Two-character state reference code based on the laws used in a country (e.g., 31)." />
								<?php if($error2){ echo '<div class="invalid-feedback">'.$error2.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('state_name'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Name<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="State name based on ISO 3166 (e.g., DKI Jakarta)."></i></span></label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="state_name" value="<?= $request->getPost('state_name'); ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('state_capital'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Capital<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Capital of the state (e.g., Jakarta)."></i></span></label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="state_capital" value="<?= $request->getPost('state_capital'); ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('time_zone'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Time Zone<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Time zone in the state."></i></span></label>
							<div class="col-md-9">
								<select class="default-select2 form-control <?php if($error){ echo 'is-invalid'; } ?>" id="time_zone" name="time_zone" data-placeholder="Select a time zone">
								<?php if(@$time_zone) : ?>
									<option></option>
								<?php foreach ($time_zone as $tz) : ?>
									<option value="<?= $tz->tz_id; ?>" <?php if($request->getPost('time_zone') == $tz->tz_id){echo 'selected';} ?>><?= $tz->tz_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('geo_unit'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Geographical Unit<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Geographical unit of the state."></i></span></label>
							<div class="col-md-9">
								<select class="default-select2 form-control <?php if($error){ echo 'is-invalid'; } ?>" name="geo_unit" data-placeholder="Select a geographical unit">
								<?php if(@$geo_unit) : ?>
									<option></option>
								<?php foreach ($geo_unit as $geou) : ?>
									<option value="<?= $geou->geo_unit_id; ?>" <?php if($request->getPost('geo_unit') == $geou->geo_unit_id){echo 'selected';} ?>><?= $geou->geo_unit_code.' - '.$geou->geo_unit_name ?></option>
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
								<a href="<?php echo base_url('state') ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Back</a>
							</div>
						</div>
					</form>
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
		</div>
		<!-- end #content -->