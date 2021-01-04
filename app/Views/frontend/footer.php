	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo base_url('assets/js/app.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/theme/default.min.js'); ?>"></script>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<!-- ================== END BASE JS ================== -->
	<script type="text/javascript">
		$(document).ready(function() {
			$(".default-select2").select2();
			
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
						$("#zip_code").val(response.zip_code);
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
						$("#zip_code").val(response.zip_code);
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
						$("#zip_code").val(response.zip_code);
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
						$("#zip_code").val(response.zip_code);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
					}
				});
			});

			$("#sub_district").change(function() {
				$.ajax({
					type : 'POST',
					url : '<?php echo base_url("user/get_zip_code"); ?>',
					data :  { sub_district : $("#sub_district").val() },
					dataType: "json",
					beforeSend: function(e) {
						if(e && e.overrideMimeType) {
							e.overrideMimeType("application/json;charset=UTF-8");
						}
					},
					success : function(response) {
						$("#zip_code").val(response.zip_code);
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