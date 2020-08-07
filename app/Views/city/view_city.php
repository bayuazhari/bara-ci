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
						<a href="<?php echo base_url('city/add/'); ?>" class="btn btn-xs btn-circle btn-primary"><i class="fa fa-plus"></i> Add New</a>
						<a href="<?php echo base_url('city/bulk_upload/'); ?>" class="btn btn-xs btn-circle btn-success"><i class="fa fa-upload"></i> Bulk Upload</a>
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
						<table id="data-table-default" class="table table-striped table-bordered table-td-valign-middle">
							<thead class="text-center">
								<tr>
									<th width="1%" rowspan="2">#</th>
									<th class="text-nowrap" rowspan="2">Code</th>
									<th class="text-nowrap" rowspan="2">Name</th>
									<th class="text-nowrap" colspan="2">Capital</th>
									<th class="text-nowrap" rowspan="2">State</th>
									<th class="text-nowrap" rowspan="2">Country</th>
									<th class="text-nowrap" rowspan="2">Status</th>
									<th class="text-nowrap" rowspan="2" data-orderable="false">Action</th>
								</tr>
								<tr>
									<th class="text-nowrap">Code</th>
									<th class="text-nowrap">Name</th>
								</tr>
							</thead>
							<tbody>
							<?php
								if(@$city) :
									$no=0;
									foreach ($city as $row) :
										$no++;
							?>
								<tr>
									<td width="1%" class="f-s-600 text-inverse"><?= $no ?></td>
									<td><?= $row->city_code ?></td>
									<td><?= $row->city_name ?></td>
									<td><?= $row->capital_city_code ?></td>
									<td><?= $row->capital_city_name ?></td>
									<td><?= $row->state_name ?></td>
									<td><?= $row->country_name ?></td>
									<?php
										if($row->city_status == 1){
											echo '<td class="text-success">Active</td>';
										}elseif($row->city_status == 0){
											echo '<td class="text-danger">Inactive</td>';
										}else{
											echo '<td></td>';
										}
									?>
									<td class="text-center">
									<?php if(@$checkLevel->update == 1 OR @$checkLevel->delete == 1){ ?>
										<div class="btn-group">
											<a href="#" data-toggle="dropdown" class="btn btn-info btn-xs dropdown-toggle">Actions <b class="caret"></b></a>
											<div class="dropdown-menu dropdown-menu-right">
											<?php if(@$checkLevel->update == 1){ ?>
												<a href="<?php echo base_url('city/edit/'.$row->city_id); ?>" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>
											<?php } if(@$checkLevel->delete == 1){ ?>
												<a href="javascript:;" class="dropdown-item <?php //if(@$model->getCityRelatedTable('city', $row->city_id)){ echo 'disabled'; } ?>"  data-toggle="modal" data-target="#modal-delete" data-href="<?php echo base_url('city/delete/'.$row->city_id) ?>"><i class="fa fa-trash-alt"></i> Delete</a>
											<?php } ?>
											</div>
										</div>
										<?php }else{ echo 'No action'; } ?>
									</td>
								</tr>
							<?php
									endforeach;
								endif;
							?>
							</tbody>
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