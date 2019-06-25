var today = new Date();

$( document ).ready(function() {
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = today.getFullYear();

	var todayString = yyyy + '-' + mm + '-' + dd;
	

	$('#date').attr("value", todayString);
});
