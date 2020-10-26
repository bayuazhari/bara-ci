/*
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Version: 4.6.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin/admin/
*/

var handleDataTableServerSide = function() {
	"use strict";
    
	if ($('#data-table-server-side').length !== 0) {
		var protocol = $(location).attr('protocol');
		var host = $(location).attr('host');
		var url = $(location).attr('href').split('/');
		var value = url[3].split('?');

		$.getJSON(protocol + '//' + host + '/' + value[0] + '/getColumns',function(column){
			$('#data-table-server-side').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					type: "POST",
					url: protocol + '//' + host + '/' + value[0] + '/getData',
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