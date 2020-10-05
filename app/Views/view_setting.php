		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item active"><?= $title ?></li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><?= $title ?> <small>App Setting</small></h1>
			<!-- end page-header -->
			
			<!-- begin panel -->
			<div class="panel panel-inverse panel-with-tabs" data-sortable-id="ui-unlimited-tabs-1">
				<!-- begin panel-heading -->
				<div class="panel-heading p-0">
					<!-- begin nav-tabs -->
					<div class="tab-overflow">
						<ul class="nav nav-tabs nav-tabs-inverse">
							<li class="nav-item prev-button"><a href="javascript:;" data-click="prev-tab" class="nav-link text-primary"><i class="fa fa-arrow-left"></i></a></li>
							<li class="nav-item"><a href="#nav-tab-1" data-toggle="tab" class="nav-link active"><i class="fa fa-desktop"></i>&nbsp;&nbsp;General</a></li>
							<li class="nav-item"><a href="#nav-tab-2" data-toggle="tab" class="nav-link"><i class="fa fa-image"></i>&nbsp;&nbsp;Image</a></li>
							<li class="nav-item"><a href="#nav-tab-3" data-toggle="tab" class="nav-link"><i class="fa fa-cog"></i>&nbsp;&nbsp;Config</a></li>
							<li class="nav-item"><a href="#nav-tab-4" data-toggle="tab" class="nav-link"><i class="fa fa-envelope"></i>&nbsp;&nbsp;Mail</a></li>
							<li class="nav-item next-button"><a href="javascript:;" data-click="next-tab" class="nav-link text-primary"><i class="fa fa-arrow-right"></i></a></li>
						</ul>
					</div>
					<!-- end nav-tabs -->
					<div class="panel-heading-btn mr-2 ml-2 d-flex">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-secondary" data-click="panel-expand"><i class="fa fa-expand"></i></a>
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
				<!-- begin tab-content -->
				<div class="panel-body tab-content">
					<!-- begin tab-pane -->
					<div class="tab-pane fade active show" id="nav-tab-1">
						<table class="table table-condensed table-bordered">
						<?php if(@$setting->getSettingByGroup('general')):
							foreach ($setting->getSettingByGroup('general') as $general):
						?>
							<tr>
								<td class="bg-light" width="25%"><?= $general->setting_name ?></td>
								<td><a href="javascript:;" class="required-editable" data-type="<?= $general->setting_type ?>" data-pk="<?= $general->setting_id ?>" data-url="<?php echo base_url('setting/edit'); ?>" data-placement="right" data-placeholder="Required" data-title="Enter <?= $general->setting_name ?>"><?= $general->setting_value ?></a></td>
							</tr>
						<?php endforeach;
						endif; ?>
						</table>
					</div>
					<!-- end tab-pane -->
					<!-- begin tab-pane -->
					<div class="tab-pane fade" id="nav-tab-2">
						<table class="table table-condensed table-bordered">
						<?php if(@$setting->getSettingByGroup('image')):
							foreach ($setting->getSettingByGroup('image') as $image):
						?>
							<tr>
								<td class="bg-light" width="25%"><?= $image->setting_name ?></td>
								<td>
								<?php if(@$image->setting_value){ ?>
									<img src="<?php echo base_url('assets/img/logo/'.$image->setting_value); ?>" class="img-rounded m-r-5 m-b-5" style="max-height: 100px;" />
								<?php } ?>
									<form action="<?php echo base_url('setting/upload_image') ?>" method="post" enctype="multipart/form-data">
										<div class="input-group">
											<input type="hidden" name="pk" value="<?= $image->setting_id ?>" />
											<input type="<?= $image->setting_type ?>" name="image" class="form-control rounded-corner" />
											<span class="input-group-btn p-l-10">
											<button class="btn btn-primary f-s-12 rounded-corner" type="submit"><i class="fa fa-upload"></i> Upload</button>
											</span>
										</div>
									</form>
								</td>
							</tr>
						<?php endforeach;
						endif; ?>
						</table>
					</div>
					<!-- end tab-pane -->
					<!-- begin tab-pane -->
					<div class="tab-pane fade" id="nav-tab-3">
						<table class="table table-condensed table-bordered">
						<?php if(@$setting->getSettingByGroup('config')):
							foreach ($setting->getSettingByGroup('config') as $config):
						?>
							<tr>
								<td class="bg-light" width="25%"><?= $config->setting_name ?></td>
								<td><a href="javascript:;" class="selected-editable" data-type="<?= $config->setting_type ?>" data-pk="<?= $config->setting_id ?>" data-url="<?php echo base_url('setting/edit'); ?>" data-value="<?= $config->setting_value ?>" data-placement="right" data-placeholder="Required" data-title="Enter <?= $config->setting_name ?>"><?= $config->setting_value ?></a></td>
							</tr>
						<?php endforeach;
						endif; ?>
						</table>
					</div>
					<!-- end tab-pane -->
					<!-- begin tab-pane -->
					<div class="tab-pane fade" id="nav-tab-4">
						<table class="table table-condensed table-bordered">
						<?php if(@$setting->getSettingByGroup('mail')):
							foreach ($setting->getSettingByGroup('mail') as $mail):
								if($mail->setting_option == 'mail_protocol'){
									$setting_option = 'id="mail_protocol"';
								}elseif($mail->setting_option == 'mail_crypto'){
									$setting_option = 'id="mail_crypto"';
								}else{
									$setting_option = 'class="required-editable"';
								}
						?>
							<tr>
								<td class="bg-light" width="25%"><?= $mail->setting_name ?></td>
								<td><a href="javascript:;" <?= $setting_option ?> data-type="<?= $mail->setting_type ?>" data-pk="<?= $mail->setting_id ?>" data-url="<?php echo base_url('setting/edit'); ?>" data-value="<?= $mail->setting_value ?>" data-placement="right" data-placeholder="Required" data-title="Enter <?= $mail->setting_name ?>"><?= $mail->setting_value ?></a></td>
							</tr>
						<?php endforeach;
						endif; ?>
						</table>
					</div>
					<!-- end tab-pane -->
				</div>
				<!-- end tab-content -->
			</div>
			<!-- end panel -->
		</div>
		<!-- end #content -->