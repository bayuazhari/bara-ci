		<!-- begin #footer -->
		<div id="footer" class="footer">
			<?= @$setting->getSettingById(6)->setting_value; ?>
		</div>
		<!-- end #footer -->
		<!-- begin theme-panel -->
		<div class="theme-panel theme-panel-lg">
			<a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-cog"></i></a>
			<div class="theme-panel-content">
				<h5>App Settings</h5><ul class="theme-list clearfix">
					<li><a href="javascript:;" class="bg-red" data-theme="red" data-theme-file="<?php echo base_url('assets/css/default/theme/red.min.css'); ?>" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Red">&nbsp;</a></li>
					<li><a href="javascript:;" class="bg-pink" data-theme="pink" data-theme-file="<?php echo base_url('assets/css/default/theme/pink.min.css'); ?>" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Pink">&nbsp;</a></li>
					<li><a href="javascript:;" class="bg-orange" data-theme="orange" data-theme-file="<?php echo base_url('assets/css/default/theme/orange.min.css'); ?>" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Orange">&nbsp;</a></li>
					<li><a href="javascript:;" class="bg-yellow" data-theme="yellow" data-theme-file="<?php echo base_url('assets/css/default/theme/yellow.min.css'); ?>" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Yellow">&nbsp;</a></li>
					<li><a href="javascript:;" class="bg-lime" data-theme="lime" data-theme-file="<?php echo base_url('assets/css/default/theme/lime.min.css'); ?>" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Lime">&nbsp;</a></li>
					<li><a href="javascript:;" class="bg-green" data-theme="green" data-theme-file="<?php echo base_url('assets/css/default/theme/green.min.css'); ?>" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Green">&nbsp;</a></li>
					<li class="active"><a href="javascript:;" class="bg-teal" data-theme="default" data-theme-file="" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Default">&nbsp;</a></li>
					<li><a href="javascript:;" class="bg-aqua" data-theme="aqua" data-theme-file="<?php echo base_url('assets/css/default/theme/aqua.min.css'); ?>" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Aqua">&nbsp;</a></li>
					<li><a href="javascript:;" class="bg-blue" data-theme="blue" data-theme-file="<?php echo base_url('assets/css/default/theme/blue.min.css'); ?>" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Blue">&nbsp;</a></li>
					<li><a href="javascript:;" class="bg-purple" data-theme="purple" data-theme-file="<?php echo base_url('assets/css/default/theme/purple.min.css'); ?>" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Purple">&nbsp;</a></li>
					<li><a href="javascript:;" class="bg-indigo" data-theme="indigo" data-theme-file="<?php echo base_url('assets/css/default/theme/indigo.min.css'); ?>" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Indigo">&nbsp;</a></li>
					<li><a href="javascript:;" class="bg-black" data-theme="black" data-theme-file="<?php echo base_url('assets/css/default/theme/black.min.css'); ?>" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Black">&nbsp;</a></li>
				</ul>
				<div class="divider"></div>
				<div class="row m-t-10">
					<div class="col-6 control-label text-inverse f-w-600">Header Fixed</div>
					<div class="col-6 d-flex">
						<div class="custom-control custom-switch ml-auto">
							<input type="checkbox" class="custom-control-input" name="header-fixed" id="headerFixed" value="1" checked />
							<label class="custom-control-label" for="headerFixed">&nbsp;</label>
						</div>
					</div>
				</div>
				<div class="row m-t-10">
					<div class="col-6 control-label text-inverse f-w-600">Header Inverse</div>
					<div class="col-6 d-flex">
						<div class="custom-control custom-switch ml-auto">
							<input type="checkbox" class="custom-control-input" name="header-inverse" id="headerInverse" value="1" />
							<label class="custom-control-label" for="headerInverse">&nbsp;</label>
						</div>
					</div>
				</div>
				<div class="row m-t-10">
					<div class="col-6 control-label text-inverse f-w-600">Sidebar Fixed</div>
					<div class="col-6 d-flex">
						<div class="custom-control custom-switch ml-auto">
							<input type="checkbox" class="custom-control-input" name="sidebar-fixed" id="sidebarFixed" value="1" checked />
							<label class="custom-control-label" for="sidebarFixed">&nbsp;</label>
						</div>
					</div>
				</div>
				<div class="row m-t-10">
					<div class="col-6 control-label text-inverse f-w-600">Sidebar Grid</div>
					<div class="col-6 d-flex">
						<div class="custom-control custom-switch ml-auto">
							<input type="checkbox" class="custom-control-input" name="sidebar-grid" id="sidebarGrid" value="1" />
							<label class="custom-control-label" for="sidebarGrid">&nbsp;</label>
						</div>
					</div>
				</div>
				<div class="row m-t-10">
					<div class="col-md-6 control-label text-inverse f-w-600">Sidebar Gradient</div>
					<div class="col-md-6 d-flex">
						<div class="custom-control custom-switch ml-auto">
							<input type="checkbox" class="custom-control-input" name="sidebar-gradient" id="sidebarGradient" value="1" />
							<label class="custom-control-label" for="sidebarGradient">&nbsp;</label>
						</div>
					</div>
				</div>
				<div class="divider"></div>
				<div class="row m-t-10">
					<div class="col-md-12">
						<a href="https://seantheme.com/color-admin/documentation/" class="btn btn-inverse btn-block btn-rounded" target="_blank"><b>Documentation</b></a>
						<a href="javascript:;" class="btn btn-default btn-block btn-rounded" data-click="reset-local-storage"><b>Reset Local Storage</b></a>
					</div>
				</div>
			</div>
		</div>
		<!-- end theme-panel -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo base_url('assets/js/app.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/theme/default.min.js'); ?>"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?php echo base_url('assets/plugins/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/datatables.net-buttons/js/buttons.flash.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/datatables.net-buttons/js/buttons.print.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/pdfmake/build/pdfmake.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/pdfmake/build/vfs_fonts.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/jszip/dist/jszip.min.js'); ?>"></script>

	<script src="<?php echo base_url('assets/plugins/jquery-migrate/dist/jquery-migrate.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/moment/min/moment.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/jquery.maskedinput/src/jquery.maskedinput.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/pwstrength-bootstrap/dist/pwstrength-bootstrap.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/@danielfarrell/bootstrap-combobox/js/bootstrap-combobox.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/bootstrap-daterangepicker/daterangepicker.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/select2/dist/js/select2.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/bootstrap-show-password/dist/bootstrap-show-password.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.js'); ?>"></script>
	
	<script src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/bootstrap4-editable/js/bootstrap-editable.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/inputs-ext/address/address.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/inputs-ext/typeaheadjs/lib/typeahead.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/inputs-ext/typeaheadjs/typeaheadjs.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/x-editable-bs4/dist/inputs-ext/wysihtml5/wysihtml5.js'); ?>"></script>
	<script src="<?php echo base_url('assets/plugins/jquery-mockjax/dist/jquery.mockjax.min.js'); ?>"></script>

	<script src="<?php echo base_url('assets/plugins/jstree/dist/jstree.min.js'); ?>"></script>

	<script src="<?php echo base_url('assets/js/demo/table-manage-server-side.demo.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/demo/table-manage-buttons.demo.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/demo/form-plugins.demo.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/demo/form-editable.demo.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/demo/ui-tree.demo.js'); ?>"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script type="text/javascript">
		$(document).ready(function() {
			$('#modal-detail').on('show.bs.modal', function(e) {
				var id = $(e.relatedTarget).data('id');

				$.ajax({
					type : 'POST',
					url : $(e.relatedTarget).data('href'),
					data :  'id='+id,
					success : function(data){
						$('.detail-data').html(data);
					}
				});
			});

			$('#modal-confirm').on('show.bs.modal', function(e) {
				$('#header-info').html($(e.relatedTarget).data('header'));
				$('#body-info').html($(e.relatedTarget).data('body'));
				$(this).find('.btn-confirm').attr('href', $(e.relatedTarget).data('href'));
			});
			
			$('#modal-delete').on('show.bs.modal', function(e) {
				$(this).find('.btn-delete').attr('href', $(e.relatedTarget).data('href'));
			});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#time_zone_loading").hide();
			$("#country").change(function() {
				$("#time_zone_loading").show();
				$("#time_zone").next(".select2-container").hide();
				$.ajax({
					type : 'POST',
					url : '<?php echo base_url("state/get_time_zone"); ?>',
					data :  { country : $("#country").val() },
					dataType: "json",
					beforeSend: function(e) {
						if(e && e.overrideMimeType) {
							e.overrideMimeType("application/json;charset=UTF-8");
						}
					},
					success : function(response) {
						$("#time_zone_loading").hide();
						$("#time_zone").next(".select2-container").show();
						$("#time_zone").html(response.time_zone_list);
						$("#iso-prefix-code").html(response.country_code);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
					}
				});
			});

			$("#state_loading").hide();
			$("#country").change(function() {
				$("#state_loading").show();
				$("#state").next(".select2-container").hide();
				$.ajax({
					type : 'POST',
					url : '<?php echo base_url("city/get_state"); ?>',
					data :  { country : $("#country").val() },
					dataType: "json",
					beforeSend: function(e) {
						if(e && e.overrideMimeType) {
							e.overrideMimeType("application/json;charset=UTF-8");
						}
					},
					success : function(response) {
						$("#state_loading").hide();
						$("#state").next(".select2-container").show();
						$("#state").html(response.state_list);
						$("#city").html(response.city_list);
						$("#district").html(response.district_list);
						$("#sub_district").html(response.sub_district_list);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
					}
				});
			});

			$("#city_loading").hide();
			$("#state").change(function() {
				$("#city_loading").show();
				$("#city").next(".select2-container").hide();
				$.ajax({
					type : 'POST',
					url : '<?php echo base_url("district/get_city"); ?>',
					data :  { state : $("#state").val() },
					dataType: "json",
					beforeSend: function(e) {
						if(e && e.overrideMimeType) {
							e.overrideMimeType("application/json;charset=UTF-8");
						}
					},
					success : function(response) {
						$("#city_loading").hide();
						$("#city").next(".select2-container").show();
						$("#city").html(response.city_list);
						$("#district").html(response.district_list);
						$("#sub_district").html(response.sub_district_list);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
					}
				});
			});

			$("#district_loading").hide();
			$("#city").change(function() {
				$("#district_loading").show();
				$("#district").next(".select2-container").hide();
				$.ajax({
					type : 'POST',
					url : '<?php echo base_url("sub_district/get_district"); ?>',
					data :  { city : $("#city").val() },
					dataType: "json",
					beforeSend: function(e) {
						if(e && e.overrideMimeType) {
							e.overrideMimeType("application/json;charset=UTF-8");
						}
					},
					success : function(response) {
						$("#district_loading").hide();
						$("#district").next(".select2-container").show();
						$("#district").html(response.district_list);
						$("#sub_district").html(response.sub_district_list);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
					}
				});
			});

			$("#sub_district_loading").hide();
			$("#district").change(function() {
				$("#sub_district_loading").show();
				$("#sub_district").next(".select2-container").hide();
				$.ajax({
					type : 'POST',
					url : '<?php echo base_url("user/get_sub_district"); ?>',
					data :  { district : $("#district").val() },
					dataType: "json",
					beforeSend: function(e) {
						if(e && e.overrideMimeType) {
							e.overrideMimeType("application/json;charset=UTF-8");
						}
					},
					success : function(response) {
						$("#sub_district_loading").hide();
						$("#sub_district").next(".select2-container").show();
						$("#sub_district").html(response.sub_district_list);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
					}
				});
			});
		});
	</script>
</body>
</html>