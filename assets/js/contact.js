$('#email').blur(function(){
    var email = $(this).val();

	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/igm;
	if(re.test(email)){
	    $('#submit').show();
	    $('#i').html(" ");
	}else{
		$('#submit').hide();
	    $('#i').html("<div id='warning'>Veillez à entrer une adresse mail correct !</div>");
	}
});