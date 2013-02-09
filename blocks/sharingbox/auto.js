$(function () {
	$('#type').change(function() {
		 $("#user-selector2").hide();
		 $('#user-selector' + $(this).find('option:selected').val()).show();
	});
});