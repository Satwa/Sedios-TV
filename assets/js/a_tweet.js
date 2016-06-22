$("#tweet").submit(function(e){
	e.preventDefault();
	alert("Publication en cours...");

	var msg = $('#msg').val();
	var pseudo = $('#pseudo').val();

	$.ajax({
		type: "GET",
		url : "/View/admin/tweet.php",
		data: {pseudo: pseudo,msg: msg},
		success: function(server_response){
			$('#msg').val("");	
			alert(server_response);
		}
	});
});