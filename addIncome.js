$( function() {
		$.datepicker.setDefaults( $.datepicker.regional[ "pl" ] );
		$( "#date" ).datepicker({
			
			dateFormat: "yy-mm-dd"
		});
} );

//$("#theForm").ajaxForm({url: 'ajaxAddIncome.php', type: 'post'})