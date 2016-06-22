var displayed = 0;
$("#key").click(function(e){
	e.preventDefault();
	if(displayed === 0){
		displayed = 1;
		$(this).text("Cacher la clé de stream");
		$("#display").html(streamkey);
	}else{
		displayed = 0;
		$(this).text("Afficher la clé de stream");
		$("#display").html("");
	}
});