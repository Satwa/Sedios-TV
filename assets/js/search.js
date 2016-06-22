$("#res").keyup(function(){
	var search = $(this).val();
	var data   = "motclef=" + search;

	if(search.length > 3){
		$.ajax({
			type: "GET",
			url : "/View/search/get.php",
			data: data,	
			success: function(server_response){
				$("#results").html(server_response).show();
			}
		});
	}
});