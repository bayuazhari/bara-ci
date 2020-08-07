		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url('city') ?>"><?= $title ?></a></li>
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
				$id = $city->city_id;
				if(@$request->getPost()){
					$state_id = $request->getPost('state');
					$city_code = $request->getPost('city_code');
					$city_name = $request->getPost('city_name');
					$capital_city_code = $request->getPost('capital_city_code');
					$capital_city_name = $request->getPost('capital_city_name');
					$status = $request->getPost('status');
				}else{
					$state_id = $city->state_id;
					$city_code = $city->city_code;
					$city_name = $city->city_name;
					$capital_city_code = $city->capital_city_code;
					$capital_city_name = $city->capital_city_name;
					$status = $city->city_status;
				} ?>
				<!-- begin panel-body -->
				<div class="panel-body">
					<form action="<?php echo base_url('city/edit/'.$id) ?>" method="post">
						<?php $error = $validation->getError('state'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">State<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="State of the city."></i></span></label>
							<div class="col-md-9">
								<select class="default-select2 form-control <?php if($error){ echo 'is-invalid'; } ?>" name="state" data-placeholder="Select a state">
								<?php if(@$state) : ?>
									<option></option>
								<?php foreach ($state as $key => $stt) :
									if(@$state[$key-1]->country_id != $stt->country_id) {
								?>
                                	<optgroup label="<?= $stt->country_name ?>">
                                <?php } ?>
										<option value="<?= $stt->state_id; ?>" <?php if($state_id == $stt->state_id){echo 'selected';} ?>><?= $stt->state_name ?></option>
								<?php if(@$state[$key+1]->country_id != $stt->country_id) { ?>
								<?php
										}
									endforeach;
								endif;
								?>
								</select>
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('city_code'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Code<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Four-character city code based on the laws used in a country (e.g., 3171)."></i></span></label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="city_code" value="<?= $city_code; ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('city_name'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Name<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="City name based on the laws used in a country (e.g., Kota Jakarta Selatan)."></i></span></label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="city_name" value="<?= $city_name; ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php
						$error1 = $validation->getError('capital_city_code');
						$error2 = $validation->getError('capital_city_name');
						?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Capital</label>
							<div class="col-md-3">
								<input type="text" class="form-control <?php if($error1){ echo 'is-invalid'; } ?>" name="capital_city_code" placeholder="Code" value="<?= $capital_city_code; ?>" data-toggle="tooltip" data-placement="bottom" title="Three-character capital city code based on the laws used in a country (e.g., KYB)." />
								<?php if($error1){ echo '<div class="invalid-feedback">'.$error1.'</div>'; } ?>
							</div>
							<div class="col-md-6">
								<input type="text" class="form-control <?php if($error2){ echo 'is-invalid'; } ?>" name="capital_city_name" placeholder="Reference" value="<?= $capital_city_name; ?>" data-toggle="tooltip" data-placement="bottom" title="Capital city name based on the laws used in a country (e.g., Kebayoran Baru)." />
								<?php if($error2){ echo '<div class="invalid-feedback">'.$error2.'</div>'; } ?>
							</div>
						</div>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Status<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="This setting allows using the state. If inactive, the state will be hidden."></i></span></label>
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
								<a href="<?php echo base_url('city') ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Back</a>
							</div>
						</div>
					</form>
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
		</div>
		<!-- end #content -->