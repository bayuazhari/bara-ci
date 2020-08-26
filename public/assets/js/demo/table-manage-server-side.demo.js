/*
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Version: 4.6.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin/admin/
*/

var handleDataTableServerSide = function() {
	"use strict";
    
	if ($('#data-table-server-side').length !== 0) {
		$.getJSON($(location).attr('href')+'/getColumns',function(column){
			$('#data-table-server-side').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					type: "POST",
					url: $(location).attr('href')+'/getData',
					data: { '<?php echo csrf_token(); ?>': '<?php echo csrf_hash(); ?>' },
					dataType: "json"
				},
				columns: column
			});
		});
	}
};

var TableManageServerSide = function () {
	"use strict";
	return {
		//main function
		init: function () {
			handleDataTableServerSide();
		}
	};
}();

$(document).ready(function() {
	TableManageServerSide.init();
});