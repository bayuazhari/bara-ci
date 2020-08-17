		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url('population') ?>"><?= $title ?></a></li>
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
				$id = $population->population_id;
				if(@$request->getPost()){
					$country_id = $request->getPost('country');
					$population_source = $request->getPost('population_source');
					$population_year = $request->getPost('population_year');
					$total_population = $request->getPost('total_population');
				}else{
					$country_id = $population->country_id;
					$population_source = $population->population_source;
					$population_year = $population->population_year;
					$total_population = $population->total_population;
				} ?>
				<!-- begin panel-body -->
				<div class="panel-body">
					<form action="<?php echo base_url('population/edit/'.$id) ?>" method="post">
						<?php $error = $validation->getError('population_source'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Data Source<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Population data collection source (e.g., Central Bureau of Statistics)."></i></span></label>
							<div class="col-md-9">
								<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="population_source" value="<?= $population_source; ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('population_year'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Year<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Census year."></i></span></label>
							<div class="col-md-9">
								<input type="text" class="form-control datepicker-year <?php if($error){ echo 'is-invalid'; } ?>" onkeydown="return false;" name="population_year" value="<?= $population_year; ?>" />
								<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
							</div>
						</div>
						<?php $error = $validation->getError('country'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Country<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Country of the population."></i></span></label>
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
						<?php $error = $validation->getError('total_population'); ?>
						<div class="form-group row m-b-15">
							<label class="col-form-label col-md-2 text-lg-right">Total<span class="text-grey-darker ml-2"><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Total population (e.g., 266794980)."></i></span></label>
							<div class="col-md-9">
								<div class="input-group">
									<input type="text" class="form-control <?php if($error){ echo 'is-invalid'; } ?>" name="total_population" value="<?= $total_population; ?>" />
									<div class="input-group-append"><span class="input-group-text">People</span></div>
									<?php if($error){ echo '<div class="invalid-feedback">'.$error.'</div>'; } ?>
								</div>
							</div>
						</div>
						<div class="form-group row m-b-0">
							<div class="col-md-12 col-sm-12 text-center">
								<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>&nbsp;&nbsp;
								<a href="<?php echo base_url('population') ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Back</a>
							</div>
						</div>
					</form>
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
		</div>
		<!-- end #content -->