		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb float-xl-right">
				<li class="breadcrumb-item"><a href="javascript:;"><?= $breadcrumb ?></a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url('menu') ?>"><?= $title ?></a></li>
				<li class="breadcrumb-item active">Tree</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><?= $title ?> <small>Tree</small></h1>
			<!-- end page-header -->
			<!-- begin panel -->
			<div class="panel panel-inverse">
				<!-- begin panel-heading -->
				<div class="panel-heading">
					<h4 class="panel-title"><?= $title ?> Tree</h4>
					<div class="panel-heading-btn">
					<?php 
						if(@$menu_group) :
							foreach ($menu_group as $mgroup) :
								if($mgroup->mgroup_id != $request->getGet('group')):
					?>
						<a href="<?php echo base_url('menu/view_tree?group='.$mgroup->mgroup_id); ?>" class="btn btn-xs btn-circle btn-success"><?= $mgroup->mgroup_name ?></a>
					<?php
								endif;
							endforeach;
						endif;
					?>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					</div>
				</div>
				<!-- end panel-heading -->
				<!-- begin panel-body -->
				<div class="panel-body">
					<div id="jstree-menu"></div>
					<div class="col-md-12 col-sm-12 text-center">
						<a href="<?php echo base_url('menu') ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Back</a>
					</div>
				</div>
				<!-- end panel-body -->
			</div>
			<!-- end panel -->
		</div>
		<!-- end #content -->