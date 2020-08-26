		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item active"><?= $title ?></li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><?= $title ?> <small>All Data</small></h1>
			<!-- end page-header -->
			<!-- begin panel -->
			<div class="panel panel-inverse">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title"><?= $title ?> Data</h4>
					<div class="panel-heading-btn">
					<?php if(@$checkLevel->create == 1){ ?>
						<a href="<?php echo base_url('sub_district/add/'); ?>" class="btn btn-xs btn-circle btn-primary"><i class="fa fa-plus"></i> Add New</a>
						<a href="<?php echo base_url('sub_district/bulk_upload/'); ?>" class="btn btn-xs btn-circle btn-success"><i class="fa fa-upload"></i> Bulk Upload</a>
					<?php } ?>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
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
				<!-- begin panel-body -->
				<div class="panel-body">
					<div class="table-responsive">
						<table id="data-table-server-side" class="table table-striped table-bordered table-td-valign-middle">
							<thead class="text-center">
								<tr>
									<th width="1%">#</th>
									<th class="text-nowrap">Code</th>
									<th class="text-nowrap">Name</th>
									<th class="text-nowrap">District</th>
									<th class="text-nowrap">City</th>
									<th class="text-nowrap">State</th>
									<th class="text-nowrap">Status</th>
									<th class="text-nowrap" data-orderable="false">Action</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
				<!-- end panel-body -->
				<!-- #modal-delete -->
				<div class="modal fade" id="modal-delete">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">Delete <?= $title ?></h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							</div>
							<div class="modal-body">
								<p>Are you sure? You will not be able to recover this imaginary file!</p>
							</div>
							<div class="modal-footer">
								<a class="btn btn-white" data-dismiss="modal">Cancel</a>
								<a class="btn btn-danger btn-delete">Delete</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end panel -->
		</div>
		<!-- end #content -->