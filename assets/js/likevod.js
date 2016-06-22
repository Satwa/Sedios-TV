var nbLike    = parseInt($("#nblike").text());
var nbDislike = parseInt($("#nbdis").text());
var hasVoted  = false;

//onClick
$(".like").click(function(){
	if(!hasVoted){
		nbLike += 1;
		$("#nblike").text(nbLike);

		$.ajax({
			type: 'POST',
			url: '/api.php?action=voteVOD',
			data: {
				pseudo: pseudo,
                type: 'like',
                vod: vod
			},
			success: function(server_response){

			}
		});

		hasVoted = true;
	}
});
$(".dislike").click(function(){
	if(!hasVoted){
		nbDislike += 1;
		$("#nbdis").text(nbDislike);

		$.ajax({
			type: 'POST',
			url: '/api.php?action=voteVOD',
			data: {
				pseudo: pseudo,
                type: 'dislike',
                vod: vod
			},
			success: function(server_response){

			}
		});

		hasVoted = true;
	}
});