		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url('user') ?>"><?= $title ?></a></li>
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
				$id = $user->user_id;
				if(@$request->getPost()){
					$level_id = $request->getPost('level');
					$first_name = $request->getPost('first_name');
					$last_name = $request->getPost('last_name');
					$user_email = $request->getPost('user_email');
					$calling_code = $request->getPost('country_calling_code');
					$user_phone = $request->getPost('user_phone');
					$user_address = $request->getPost('user_address');
					$country_id = $request->getPost('country');
					$state_id = $request->getPost('state');
					$city_id = $request->getPost('city');
					$district_id = $request->getPost('district');
					$sdistrict_id = $request->getPost('sub_district');
					$status = $request->getPost('status');
				}else{
					$level_id = $user->level_id;
					$first_name = $user->first_name;
					$last_name = $user->last_name;
					$user_email = $user->user_email;
					$calling_code = $user->country_calling_code;
					$user_phone = $user->user_phone;
					$user_address = $user->user_address;
					$country_id = $user->country_id;
					$state_id = $user->state_id;
					$city_id = $user->city_id;
					$district_id = $user->district_id;
					$sdistrict_id = $user->sdistrict_id;
					$status = $user->user_status;
				} ?>
				<!-- begin panel-body -->
				<div class="panel-body">
					<form action="<?php echo base_url('user/edit/'.$id) ?>" method="post">
						<?php
						$error1 = $validation->getError('first_name');
						$error2 = $validation->getError('last_name');
						?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Name</label>
							<div class="col-md-9">
								<div class="row">
									<div class="col-md-6">
										<input type="text" class="form-control <?php if($error1){ echo 'is-invalid'; } ?>" name="first_name" placeholder="First Name" value="<?= $first_name; ?>" data-toggle="tooltip" data-placement="bottom" title="First Name (e.g., John)." />
										<?php if($error1){ echo '<div class="invalid-feedback">'.$error1.'</div>'; } ?>
									</div>
									<div class="col-md-6">
										<input type="text" class="form-control <?php if($error2){ echo 'is-invalid'; } ?>" name="last_name" placeholder="Last Name" value="<?= $last_name; ?>" data-toggle="tooltip" data-placement="bottom" title="Last Name (e.g., Doe)." />
										<?php if($error2){ echo '<div class="invalid-feedback">'.$error2.'</div>'; } ?>
									</div>
								</div>
							</div>
						</div>
						<?php $error = $validation->getError('user_email'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Email<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="User email (e.g., johndoe@example.com)."></i></span></label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="user_email" value="<?= $user_email; ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php
						$error1 = $validation->getError('country_calling_code');
						$error2 = $validation->getError('user_phone');
						?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Phone<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="User phone (e.g., +62 81234567890)."></i></span></label>
							<div class="col-md-9">
								<div class="input-group">
									<div class="input-group-prepend">
										<select class="default-select2 form-control <?php if($error1){ echo 'is-invalid'; } ?>" name="country_calling_code" data-placeholder="Select a IDD code">
										<?php if(@$country_calling_code) :
											foreach ($country_calling_code as $idd) : ?>
											<option value="<?= $idd->idd_code; ?>" <?php if($calling_code == $idd->idd_code){echo 'selected';} ?>><?= '+'.$idd->idd_code ?></option>
										<?php
											endforeach;
										endif;
										?>
										</select>
									</div>
									<input type="text" class="form-control <?php if($error2){ echo 'is-invalid'; } ?>" name="user_phone" value="<?= $user_phone; ?>" />
									<?php if($error2){ echo '<div class="invalid-feedback">'.$error2.'</div>'; } ?>
								</div>
							</div>
						</div>
						<?php
						$error1 = $validation->getError('user_address');
						$error2 = $validation->getError('country');
						$error3 = $validation->getError('state');
						$error4 = $validation->getError('city');
						$error5 = $validation->getError('district');
						$error6 = $validation->getError('sub_district');
						?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Address<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="User Address (e.g., Jl. Lapangan Banteng Utara No. 1)."></i></span></label>
							<div class="col-md-9">
								<div class="row">
									<div class="col-md-12">
										<textarea class="form-control <?php if($error1){ echo 'is-invalid'; } ?>" name="user_address"><?= $user_address; ?></textarea>
										<?php if($error1){ echo '<div class="invalid-feedback">'.$error1.'</div>'; } ?>
									</div>
									<div class="col-md-6">
										<select class="default-select2 form-control <?php if($error2){ echo 'is-invalid'; } ?>" id="country" name="country" data-placeholder="Select a country">
										<?php if(@$country) : ?>
											<option></option>
										<?php foreach ($country as $coun) : ?>
											<option value="<?= $coun->country_id; ?>" <?php if($country_id == $coun->country_id){echo 'selected';} ?>><?= $coun->country_name ?></option>
										<?php
											endforeach;
										endif;
										?>
										</select>
										<?php if($error2){ echo '<div class="invalid-feedback">'.$error2.'</div>'; } ?>
									</div>
									<div class="col-md-6">
										<select class="default-select2 form-control <?php if($error3){ echo 'is-invalid'; } ?>" id="state" name="state" data-placeholder="Select a state">
										<?php if(@$state) : ?>
											<option></option>
										<?php foreach ($state as $stt) : ?>
											<option value="<?= $stt->state_id; ?>" <?php if($state_id == $stt->state_id){echo 'selected';} ?>><?= $stt->state_name ?></option>
										<?php
											endforeach;
										endif;
										?>
										</select>
										<div id="state_loading" style="margin-top: 7px;">
											<img src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/bootstrap4-editable/img/loading.gif'); ?>"> <small>Loading...</small>
										</div>
										<?php if($error3){ echo '<div class="invalid-feedback">'.$error3.'</div>'; } ?>
									</div>
									<div class="col-md-6">
										<select class="default-select2 form-control <?php if($error4){ echo 'is-invalid'; } ?>" id="city" name="city" data-placeholder="Select a city">
										<?php if(@$city) : ?>
											<option></option>
										<?php foreach ($city as $ct) : ?>
											<option value="<?= $ct->city_id; ?>" <?php if($city_id == $ct->city_id){echo 'selected';} ?>><?= $ct->city_name ?></option>
										<?php
											endforeach;
										endif;
										?>
										</select>
										<div id="city_loading" style="margin-top: 7px;">
											<img src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/bootstrap4-editable/img/loading.gif'); ?>"> <small>Loading...</small>
										</div>
										<?php if($error4){ echo '<div class="invalid-feedback">'.$error4.'</div>'; } ?>
									</div>
									<div class="col-md-6">
										<select class="default-select2 form-control <?php if($error5){ echo 'is-invalid'; } ?>" id="district" name="district" data-placeholder="Select a district">
										<?php if(@$district) : ?>
											<option></option>
										<?php foreach ($district as $dist) : ?>
											<option value="<?= $dist->district_id; ?>" <?php if($district_id == $dist->district_id){echo 'selected';} ?>><?= $dist->district_name ?></option>
										<?php
											endforeach;
										endif;
										?>
										</select>
										<div id="district_loading" style="margin-top: 7px;">
											<img src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/bootstrap4-editable/img/loading.gif'); ?>"> <small>Loading...</small>
										</div>
										<?php if($error5){ echo '<div class="invalid-feedback">'.$error5.'</div>'; } ?>
									</div>
									<div class="col-md-6">
										<select class="default-select2 form-control <?php if($error6){ echo 'is-invalid'; } ?>" id="sub_district" name="sub_district" data-placeholder="Select a sub district">
										<?php if(@$sub_district) : ?>
											<option></option>
										<?php foreach ($sub_district as $sdist) : ?>
											<option value="<?= $sdist->sdistrict_id; ?>" <?php if($sdistrict_id == $sdist->sdistrict_id){echo 'selected';} ?>><?= $sdist->sdistrict_name ?></option>
										<?php
											endforeach;
										endif;
										?>
										</select>
										<div id="sub_district_loading" style="margin-top: 7px;">
											<img src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/bootstrap4-editable/img/loading.gif'); ?>"> <small>Loading...</small>
										</div>
										<?php if($error6){ echo '<div class="invalid-feedback">'.$error6.'</div>'; } ?>
									</div>
								</div>
							</div>
						</div>
						<?php $error = $validation->getError('level'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Level<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Level of the user."></i></span></label>
							<div class="col-md-9">
								<select class="default-select2 form-control <?php if($error){ echo 'is-invalid'; } ?>" name="level" data-placeholder="Select a level">
								<?php if(@$level) : ?>
									<option></option>
								<?php foreach ($level as $lvl) : ?>
									<option value="<?= $lvl->level_id; ?>" <?php if($level_id == $lvl->level_id){echo 'selected';} ?>><?= $lvl->level_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Status<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="This setting allows using the menu group. If inactive, the menu group will be hidden."></i></span></label>
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
								<a href="<?php echo base_url('user') ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Back</a>
							</div>
						</div>
					</form>
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
		</div>
		<!-- end #content -->