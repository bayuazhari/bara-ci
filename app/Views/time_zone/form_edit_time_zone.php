		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url('time_zone') ?>"><?= $title ?></a></li>
				<li class="breadcrumb-item active">Edit</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><?= $title ?> <small>Edit</small></h1>
			<!-- end page-header -->
			<!-- begin panel -->
			<div class="panel panel-inverse">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title">Edit <?= $title ?></h4>
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<?php
				$id = $time_zone->tz_id;
				if(@$request->getPost()){
					$country_id = $request->getPost('country');
					$tz_name = $request->getPost('tz_name');
					$tz_abbr = $request->getPost('tz_abbr');
					$status = $request->getPost('status');
				}else{
					$country_id = $time_zone->country_id;
					$tz_name = $time_zone->tz_name;
					$tz_abbr = $time_zone->tz_abbr;
					$status = $time_zone->tz_status;
				} ?>
				<!-- begin panel-body -->
				<div class="panel-body">
					<form action="<?php echo base_url('time_zone/edit/'.$id) ?>" method="post">
						<?php $error = $validation->getError('tz_name'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Name<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Olson time zone (e.g., Asia/Jakarta)."></i></span></label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="tz_name" value="<?= $tz_name; ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('tz_abbr'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Abbreviation<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Time zone abbreviation (e.g., WIB)."></i></span></label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="tz_abbr" value="<?= $tz_abbr; ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('country'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Country<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Country of the time zone."></i></span></label>
							<div class="col-md-9">
								<select class="default-select2 form-control <?php if($error){ echo 'is-invalid'; } ?>" name="country" data-placeholder="Select a country">
								<?php if(@$country) : ?>
									<option></option>
								<?php foreach ($country as $coun) : ?>
									<option value="<?= $coun->country_id; ?>" <?php if($country_id == $coun->country_id){echo 'selected';} ?>><?= $coun->country_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Status<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="This setting allows using the time zone. If inactive, the time zone will be hidden."></i></span></label>
							<div class="col-md-9">
								<div class="custom-control custom-radio mb-1">
									<input type="radio" id="customRadio1" name="status" class="custom-control-input" value="1" <?php if($status == 1){echo 'checked';}?>>
									<label class="custom-control-label" for="customRadio1"><span class="text-success">Active</span></label>
								</div>
								<div class="custom-control custom-radio">
									<input type="radio" id="customRadio2" name="status" class="custom-control-input" value="0" <?php if($status == 0){echo 'checked';}?>>
									<label class="custom-control-label" for="customRadio2"><span class="text-danger">Inactive</span></label>
								</div>
							</div>
						</div>
						<div class="form-group row m-b-0">
							<div class="col-md-12 col-sm-12 text-center">
								<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>&nbsp;&nbsp;
								<a href="<?php echo base_url('time_zone') ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Back</a>
							</div>
						</div>
					</form>
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
		</div>
		<!-- end #content -->