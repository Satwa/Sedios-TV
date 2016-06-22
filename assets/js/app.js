$("#header__icon").click(function(){
	if($("#menu").is(":hidden")){
		$("#menu").slideDown("fast");
	}else{
		$("#menu").slideUp("fast");
	}
});

$("#delete").click(function(e){
	var url = $(this).attr('href');

	e.preventDefault();

	var v = confirm("Voulez-vous réellement supprimer ceci ?");

	if(v === true){
		window.location = url;
	}else if(v == false){
		return;
	}
});