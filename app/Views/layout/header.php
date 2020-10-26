<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?= @$setting->getSettingById(1)->setting_value.' - '.@$title; ?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="<?= @$setting->getSettingById(2)->setting_value; ?>" name="description" />
	<meta content="<?= @$setting->getSettingById(3)->setting_value; ?>" name="keywords" />
	<meta content="<?= @$setting->getSettingById(4)->setting_value; ?>" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link type="image/png" sizes="16x16" href="<?php echo base_url('assets/img/logo/'.@$setting->getSettingById(7)->setting_value); ?>" rel="icon">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/default/app.min.css'); ?>" rel="stylesheet" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="<?php echo base_url('assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/flag-icon-css/css/flag-icon.min.css'); ?>" rel="stylesheet" />

	<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/@danielfarrell/bootstrap-combobox/css/bootstrap-combobox.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/bootstrap-daterangepicker/daterangepicker.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/select2/dist/css/select2.min.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-fontawesome.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-glyphicons.css'); ?>" rel="stylesheet" />

	<link href="<?php echo base_url('assets/plugins/x-editable-bs4/dist/bootstrap4-editable/css/bootstrap-editable.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/x-editable-bs4/dist/inputs-ext/address/address.css'); ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/plugins/x-editable-bs4/dist/inputs-ext/typeaheadjs/lib/typeahead.js-bootstrap.css'); ?>" rel="stylesheet" />

	<link href="<?php echo base_url('assets/plugins/jstree/dist/themes/default/style.min.css'); ?>" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL STYLE ================== -->
</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show">
		<span class="spinner"></span>
	</div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<div id="header" class="header navbar-default">
			<!-- begin navbar-header -->
			<div class="navbar-header">
				<a href="<?php echo base_url(); ?>" class="navbar-brand">
				<?php 
					$navbar_logo = $setting->getSettingById(8)->setting_value;
					if(@$navbar_logo){ ?>
					<img src="<?php echo base_url('assets/img/logo/'.$navbar_logo); ?>" class="img-rounded height-50" />&nbsp;
				<?php }else{ ?>
					<span class="navbar-logo"></span>
				<?php } ?>
					<?= @$setting->getSettingById(5)->setting_value; ?>
				</a>
				<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<!-- end navbar-header --><!-- begin header-nav -->
			<ul class="navbar-nav navbar-right">
				<li class="navbar-form">
					<form action="" method="POST" name="search">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Enter keyword" />
							<button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
						</div>
					</form>
				</li>
				<li class="dropdown">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle f-s-14">
						<i class="fa fa-bell"></i>
					<?php if (@$total_notif) { ?>
						<span class="label"><?= $total_notif ?></span>
					<?php } ?>
					</a>
					<div class="dropdown-menu media-list dropdown-menu-right">
						<div class="dropdown-header">NOTIFICATIONS</div>
					<?php
						if(@$notification) {
							foreach ($notification as $notif) {
								$time_diff = (time() - strtotime($notif->notif_date));
								$second = $time_diff;
								$minute = round($time_diff / 60 );
								$hour = round($time_diff / 3600 );
								$day = round($time_diff / 86400 );
								$week = round($time_diff / 604800 );
								$month = round($time_diff / 2419200 );

								if ($second < 60) {
									$notif_date = 'Just now';
								} else if ($minute < 60) {
									if($minute == 1){
										$notif_date = 'One minutes ago';
									}else{
										$notif_date = $minute.' minutes ago';
									}
								} else if ($hour < 24) {
									if($hour == 1){
										$notif_date = 'An hour ago';
									}else{
										$notif_date = $hour.' hours ago';
									}
								} else if ($day < 7) {
									if($day == 1){
										$notif_date = 'Yesterday';
									}else{
										$notif_date = $day.' days ago';
									}
								} else if ($week < 4) {
									if($week == 1){
										$notif_date = 'A week ago';
									}else{
										$notif_date = $week.' weeks ago';
									}
								} else if ($month < 12) {
									if($month == 1){
										$notif_date = 'A month ago';
									}else{
										$notif_date = $month.' months ago';
									}
								} else {
									$notif_date = date('F d, Y H:i', strtotime($notif->notif_date));
								}
					?>
						<a href="<?php echo base_url('notification/detail/'.$notif->notif_id); ?>" class="dropdown-item media">
							<div class="media-left">
								<i class="<?= $notif->notif_class; ?>"></i>
							</div>
							<div class="media-body">
								<h6 class="media-heading"><?= $notif->notif_title; ?></h6>
								<p><?= $notif->notif_desc; ?></p>
								<div class="text-muted f-s-10"><?= $notif_date ?></div>
							</div>
						</a>
					<?php
							}
						}
					?>
						<div class="dropdown-footer text-center">
							<a href="<?php echo base_url('notification'); ?>">View more</a>
						</div>
					</div>
				</li>
				<li class="dropdown navbar-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<?php
						if(@session('user_photo')){
							$user_photo = session('user_id').'/'.session('user_photo');
						}else{
							$user_photo = 'user-0.png';
						}
					?>
						<img src="<?php echo base_url('assets/img/user/'.$user_photo); ?>" alt="" /> 
						<span class="d-none d-md-inline"><?= session('full_name'); ?></span> <b class="caret"></b>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
					<?php
						$profile_menu = $setting->getMenu('MG2000002');
						if(@$profile_menu) {
							foreach ($profile_menu as $pmenu) {
								$pmenu_level = $setting->getLevelByRole(session('level_id'), $pmenu->menu_id);
								if(@$pmenu_level->read == 1){
					?>
						<a href="<?php echo base_url($pmenu->menu_url); ?>" class="dropdown-item"><i class="<?= $pmenu->menu_class; ?>"></i> <?= $pmenu->menu_name; ?></a>
					<?php
								}
							}
						}
					?>
						<div class="dropdown-divider"></div>
						<a href="<?php echo base_url('login/logout'); ?>" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Sign Out</a>
					</div>
				</li>
			</ul>
			<!-- end header-nav -->
		</div>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<div id="sidebar" class="sidebar">
			<!-- begin sidebar scrollbar -->
			<div data-scrollbar="true" data-height="100%">
				<!-- begin sidebar user -->
				<ul class="nav">
					<li class="nav-profile">
						<a href="javascript:;" data-toggle="nav-profile">
							<div class="cover with-shadow"></div>
							<div class="image">
								<img src="<?php echo base_url('assets/img/user/'.$user_photo); ?>" alt="" />
							</div>
							<div class="info">
								<b class="caret pull-right"></b><?= session('full_name'); ?>
								<small><?= session('level_name'); ?></small>
							</div>
						</a>
					</li>
					<li>
						<ul class="nav nav-profile">
						<?php
							if(@$profile_menu) {
								foreach ($profile_menu as $pmenu) {
									$pmenu_level = $setting->getLevelByRole(session('level_id'), $pmenu->menu_id);
									if(@$pmenu_level->read == 1){
						?>
							<li><a href="<?php echo base_url($pmenu->menu_url); ?>"><i class="<?= $pmenu->menu_class; ?>"></i> <?= $pmenu->menu_name; ?></a></li>
						<?php
									}
								}
							}
						?>
						</ul>
					</li>
				</ul>
				<!-- end sidebar user -->
				<!-- begin sidebar nav -->
				<ul class="nav"><li class="nav-header">Navigation</li>
				<?php
					if(@$segment->getSegment(2)){
						$check_menu_uri = $setting->getMenuByUrl($segment->getSegment(1).'/'.$segment->getSegment(2));
						if(@$check_menu_uri){
							$menu_uri = $segment->getSegment(1).'/'.$segment->getSegment(2);
						}else{
							$menu_uri = $segment->getSegment(1);
						}
					}elseif(@$segment->getSegment(1)){
						$menu_uri = $segment->getSegment(1);
					}else{
						$menu_uri = 'home';
					}

					$sidebar_menu = $setting->getMenu('MG2000001');
					if(@$sidebar_menu) {
						foreach ($sidebar_menu as $sbmenu) {
							$menu_level_1 = $setting->getMenu('MG2000001', $sbmenu->menu_id);
							$menu_child_1 = count((array)$menu_level_1);
							if($menu_child_1 > 0) {
								$check_sidebar_menu = $setting->getMenuByUrl($menu_uri);
								$check_menu_parent_1 = $setting->getMenuParent(@$check_sidebar_menu->mparent_id);
								$check_menu_parent_2 = $setting->getMenuParent(@$check_menu_parent_1->mparent_id);
								if(@$check_sidebar_menu->mparent_id == $sbmenu->menu_id){
									$sidebar_menu_active = 'active';
								}elseif(@$check_menu_parent_2->mparent_id == $sbmenu->menu_id){
									$sidebar_menu_active = 'active';
								}elseif(@$check_menu_parent_1->mparent_id == $sbmenu->menu_id){
									$sidebar_menu_active = 'active';
								}else{
									$sidebar_menu_active = '';
								}
				?>
					<li class="has-sub <?= $sidebar_menu_active; ?>">
						<a href="javascript:;">
							<b class="caret"></b>
							<i class="<?= $sbmenu->menu_class; ?>"></i>
							<span><?= $sbmenu->menu_name; ?></span>
						</a>
						<ul class="sub-menu">
						<?php
							foreach ($menu_level_1 as $mnlvl1){
								$menu_level_2 = $setting->getMenu('MG2000001', $mnlvl1->menu_id);
								$menu_child_2 = count((array)$menu_level_2);
								if($menu_child_2 > 0) {
									$check_menu_level_1 = $setting->getMenuByUrl($menu_uri);
									$check_menu_parent_3 = $setting->getMenuParent(@$check_menu_level_1->mparent_id);
									if(@$check_menu_level_1->mparent_id == $mnlvl1->menu_id){
										$menu_level_1_active = 'active';
									}elseif(@$check_menu_parent_3->mparent_id == $mnlvl1->menu_id){
										$menu_level_1_active = 'active';
									}else{
										$menu_level_1_active = '';
									}
						?>
							<li class="has-sub <?= $menu_level_1_active; ?>">
								<a href="javascript:;">
									<b class="caret"></b>
									<?= $mnlvl1->menu_name; ?>
								</a>
								<ul class="sub-menu">
								<?php
									foreach ($menu_level_2 as $mnlvl2){
										$menu_level_3 = $setting->getMenu('MG2000001', $mnlvl2->menu_id);
										$menu_child_3 = count((array)$menu_level_3);
										if($menu_child_3 > 0) {
											$check_menu_level_2 = $setting->getMenuByUrl($menu_uri);
											if(@$check_menu_level_2->mparent_id == $mnlvl2->menu_id){
												$menu_level_2_active = 'active';
											}else{
												$menu_level_2_active = '';
											}
								?>
									<li class="has-sub <?= $menu_level_2_active; ?>">
										<a href="javascript:;">
											<b class="caret"></b>
											<?= $mnlvl2->menu_name; ?>
										</a>
										<ul class="sub-menu">
										<?php foreach ($menu_level_3 as $mnlvl3){
											$mnlvl3_level = $setting->getLevelByRole(session('level_id'), $mnlvl3->menu_id);
											if(@$mnlvl3_level->read == 1){
												if($menu_uri == $mnlvl3->menu_url){
													$menu_level_3_active = 'active';
												}else{
													$menu_level_3_active = '';
												}
										?>
											<li class="<?= $menu_level_3_active; ?>"><a href="<?php echo base_url($mnlvl3->menu_url); ?>"><?= $mnlvl3->menu_name; ?></a></li>
										<?php
											}
										}
										?>
										</ul>
									</li>
									<?php } else {
										$mnlvl2_level = $setting->getLevelByRole(session('level_id'), $mnlvl2->menu_id);
										if(@$mnlvl2_level->read == 1){
											if($menu_uri == $mnlvl2->menu_url){
												$menu_level_2_active = 'active';
											}else{
												$menu_level_2_active = '';
											}
									?>
									<li class="<?= $menu_level_2_active; ?>"><a href="<?php echo base_url($mnlvl2->menu_url); ?>"><?= $mnlvl2->menu_name; ?></a></li>
								<?php
											}
										}
									}
								?>
								</ul>
							</li>
							<?php } else {
								$mnlvl1_level = $setting->getLevelByRole(session('level_id'), $mnlvl1->menu_id);
								if(@$mnlvl1_level->read == 1){
									if($menu_uri == $mnlvl1->menu_url){
										$menu_level_1_active = 'active';
									}else{
										$menu_level_1_active = '';
									}
							?>
							<li class="<?= $menu_level_1_active; ?>"><a href="<?php echo base_url($mnlvl1->menu_url); ?>"><?= $mnlvl1->menu_name; ?></a></li>
						<?php
									}
								}
							}
						?>
						</ul>
					</li>
					<?php } else {
						$sbmenu_level = $setting->getLevelByRole(session('level_id'), $sbmenu->menu_id);
						if(@$sbmenu_level->read == 1){
							if($menu_uri == $sbmenu->menu_url){
								$sidebar_menu_active = 'active';
							}else{
								$sidebar_menu_active = '';
							}
					?>
					<li class="<?= $sidebar_menu_active; ?>">
						<a href="<?php echo base_url($sbmenu->menu_url); ?>">
							<i class="<?= $sbmenu->menu_class; ?>"></i> 
							<span><?= $sbmenu->menu_name; ?></span>
						</a>
					</li>
				<?php
								}
							}
						}
					}
				?>
					<!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
					<!-- end sidebar minify button -->
				</ul>
				<!-- end sidebar nav -->
			</div>
			<!-- end sidebar scrollbar -->
		</div>
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
		