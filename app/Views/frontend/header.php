<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?= $web_title.' - '.@$title; ?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="<?= $meta_desc; ?>" name="description" />
	<meta content="<?= @$setting->getSettingById(3)->setting_value; ?>" name="keywords" />
	<meta content="<?= @$setting->getSettingById(4)->setting_value; ?>" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link type="image/png" sizes="16x16" href="<?php echo base_url('assets/img/logo/'.@$setting->getSettingById(7)->setting_value); ?>" rel="icon">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/default/app.min.css'); ?>" rel="stylesheet" />

	<link href="<?php echo base_url('assets/plugins/bootstrap-social/bootstrap-social.css'); ?>" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<!-- ================== END BASE CSS STYLE ================== -->
</head>
<body class="pace-top">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show">
		<span class="spinner"></span>
	</div>
	<!-- end #page-loader -->