		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url('country') ?>"><?= $title ?></a></li>
				<li class="breadcrumb-item active">Edit</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><?= $title ?> <small>Edit <?= $title ?></small></h1>
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
				$id = $country->country_id;
				if(@$request->getPost()){
					$country_alpha2_code = $request->getPost('country_alpha2_code');
					$country_alpha3_code = $request->getPost('country_alpha3_code');
					$country_numeric_code = $request->getPost('country_numeric_code');
					$country_name = $request->getPost('country_name');
					$country_capital = $request->getPost('country_capital');
					$country_demonym = $request->getPost('country_demonym');
					$country_area = $request->getPost('country_area');
					$idd_code = $request->getPost('idd_code');
					$cctld = $request->getPost('cctld');
					$currency_id = $request->getPost('currency');
					$lang_id = $request->getPost('language');
					$status = $request->getPost('status');
				}else{
					$country_alpha2_code = $country->country_alpha2_code;
					$country_alpha3_code = $country->country_alpha3_code;
					$country_numeric_code = $country->country_numeric_code;
					$country_name = $country->country_name;
					$country_capital = $country->country_capital;
					$country_demonym = $country->country_demonym;
					$country_area = $country->country_area;
					$idd_code = $country->idd_code;
					$cctld = $country->cctld;
					$currency_id = $country->currency_id;
					$lang_id = $country->lang_id;
					$status = $country->country_status;
				} ?>
				<!-- begin panel-body -->
				<div class="panel-body">
					<form action="<?php echo base_url('country/edit/'.$id) ?>" method="post">
						<?php
						$error1 = $validation->getError('country_alpha2_code');
						$error2 = $validation->getError('country_alpha3_code');
						$error3 = $validation->getError('country_numeric_code');
						?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Code</label>
							<div class="col-md-3">
								<input type="text" class="form-control <?php if($error1){ echo 'is-invalid'; } ?>" name="country_alpha2_code" placeholder="Alpha-2" value="<?= $country_alpha2_code; ?>" />
								<?php if($error1){ echo '<div class="invalid-feedback">'.$error1.'</div>'; } ?>
								<small class="f-s-12 text-grey-darker">Two-character country code based on ISO 3166 (e.g., <strong>ID</strong>).</small>
							</div>
							<div class="col-md-3">
								<input type="text" class="form-control <?php if($error2){ echo 'is-invalid'; } ?>" name="country_alpha3_code" placeholder="Alpha-3" value="<?= $country_alpha3_code; ?>" />
								<?php if($error2){ echo '<div class="invalid-feedback">'.$error2.'</div>'; } ?>
								<small class="f-s-12 text-grey-darker">Three-character country code based on ISO 3166 (e.g., <strong>IDN</strong>).</small>
							</div>
							<div class="col-md-3">
								<input type="text" class="form-control <?php if($error3){ echo 'is-invalid'; } ?>" name="country_numeric_code" placeholder="Numeric" value="<?= $country_numeric_code; ?>" />
								<?php if($error3){ echo '<div class="invalid-feedback">'.$error3.'</div>'; } ?>
								<small class="f-s-12 text-grey-darker">Three-character country numeric code based on ISO 3166 (e.g., <strong>360</strong>).</small>
							</div>
						</div>
						<?php $error = $validation->getError('country_name'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Name</label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="country_name" value="<?= $country_name; ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
								<small class="f-s-12 text-grey-darker">Country name based on ISO 3166 (e.g., <strong>Indonesia</strong>).</small>
							</div>
						</div>
						<?php $error = $validation->getError('country_capital'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Capital</label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="country_capital" value="<?= $country_capital; ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
								<small class="f-s-12 text-grey-darker">Capital of the country (e.g., <strong>Jakarta</strong>).</small>
							</div>
						</div>
						<?php $error = $validation->getError('country_demonym'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Demonym</label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="country_demonym" value="<?= $country_demonym; ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
								<small class="f-s-12 text-grey-darker">Demonym of the country (e.g., <strong>Indonesians</strong>).</small>
							</div>
						</div>
						<?php $error = $validation->getError('country_area'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Total Area</label>
							<div class="col-md-9">
								<div class="input-group">
									<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="country_area" value="<?= $country_area; ?>" />
									<div class="input-group-append"><span class="input-group-text">km<sup>2</sup></span></div>
									<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
								</div>
								<small class="f-s-12 text-grey-darker">Total area in km<sup>2</sup> (e.g., <strong>1904569</strong>).</small>
							</div>
						</div>
						<?php $error = $validation->getError('idd_code'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">IDD Code</label>
							<div class="col-md-9">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text">+</span></div>
									<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="idd_code" value="<?= $idd_code; ?>" />
									<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
								</div>
								<small class="f-s-12 text-grey-darker">The IDD prefix to call the city from another country (e.g., <strong>62</strong>).</small>
							</div>
						</div>
						<?php $error = $validation->getError('cctld'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">ccTLD</label>
							<div class="col-md-9">
								<div class="input-group">
									<div class="input-group-prepend"><span class="input-group-text">.</span></div>
									<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="cctld" value="<?= $cctld; ?>" />
									<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
								</div>
								<small class="f-s-12 text-grey-darker">Country-Code Top-Level Domain (e.g., <strong>id</strong>).</small>
							</div>
						</div>
						<?php $error = $validation->getError('currency'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Currency</label>
							<div class="col-md-9">
								<select class="default-select2 form-control <?php if($error){ echo 'is-invalid'; } ?>" name="currency" data-placeholder="Select a currency">
								<?php if(@$currency) : ?>
									<option></option>
								<?php foreach ($currency as $curr) : ?>
									<option value="<?= $curr->currency_id ?>" <?php if($currency_id == $curr->currency_id){echo 'selected';} ?>><?= $curr->currency_code.' - '.$curr->currency_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
								<small class="f-s-12 text-grey-darker">Currency of the country.</small>
							</div>
						</div>
						<?php $error = $validation->getError('language'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Language</label>
							<div class="col-md-9">
								<select class="default-select2 form-control <?php if($error){ echo 'is-invalid'; } ?>" name="language" data-placeholder="Select a language">
								<?php if(@$language) : ?>
									<option></option>
								<?php foreach ($language as $lang) : ?>
									<option value="<?= $lang->lang_id ?>" <?php if($lang_id == $lang->lang_id){echo 'selected';} ?>><?= $lang->lang_code.' - '.$lang->lang_name ?></option>
								<?php
									endforeach;
								endif;
								?>
								</select>
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
								<small class="f-s-12 text-grey-darker">Language of the country.</small>
							</div>
						</div>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Status <span class="text-danger">*</span></label>
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