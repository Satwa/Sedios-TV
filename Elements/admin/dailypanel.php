<?php $d = Database::query("SELECT * FROM sedios_live_channel WHERE id = ".$c->id); ?>
<h2>
	<i class="fa fa-signal" id="icon-live" title="Vous n'êtes pas en live"></i>
	<i class="fa fa-circle" id="icon-record" title="Vous n'êtes pas en enregistrement"></i>
</h2>

<h4>Enregistrement</h4><br>
<div id="mid">
	<a class="record_btn" id="bstart">
		<i class="fa fa-circle"></i> <br> Démarrer l'enregistrement
	</a>
	<a class="record_btn" id="bstop">
	    <i class="fa fa-stop"></i>   <br> Arrêter l'enregistrement
	</a>
</div>
<div style="clear:both;"></div>
<br><br>
<h4>Publicité</h4>

<select class="form-control" id="spub">
    <option value="1">1 publicité (35s)</option>
    <option value="2">2 publicités (1m10s)</option>
    <option value="3">3 publicités (1m45s)</option>
    <option value="4">4 publicités (2m20s)</option>
    <option value="5">5 publicités (2m55s)</option>
    <option value="6">6 publicités (3m30s)</option>
    <option value="7">7 publicités (4m5s)</option>
    <option value="8">8 publicités (4m40s)</option>
    <option value="9">9 publicités (5m15s)</option>
    <option value="10">10 publicités (5m50s)</option>
</select>

<button class="btn btn-primary disabled" id="bpub">Lancer</button>
<br><br>

<h4>Statut</h4>

<div id="statusappend">

</div>

<iframe id="playerdl" frameborder="0" src="//www.dailymotion.com/embed/video/<?= $d->playerid; ?>?autoPlay=1&autoMute=1&forcedQuality=hq" allowfullscreen></iframe>
<?php require_once "Controller/LiveController.php"; $_SESSION['tv'] = $d->chatid; ?>
<iframe id="chatdl" frameborder="0" height="500px" style="vertical-align:middle;" src="/chat/index.php"></iframe>

<?php 
	//defining php req var to js
	echo "<script>";
		echo "var plateforme_id =".$d->id;
	echo "</script>";
?>

<script>
	//MAIN SCRIPT

	$(document).ready(function(){
		var isOnline = false;
		var isRecord = false;
		var secure_key = "54908a168a06b";

		function callappend(type,title,text){
			$("#statusappend").html('<div id='+ type +'>' + title + ' : ' + text +'</div>' + $("#statusappend").html());
		}

		function clearnotif(){
			$("#statusappend").html('');
		}
		
		function getdate(timestamp){
			var date = new Date(timestamp * 1000);
			var hours = date.getHours();
			var minutes = "0" + date.getMinutes();
			var secondes = "0" + date.getSeconds();
			var timef = hours + ':' + minutes.substr(minutes.length - 2) + ':' + secondes.substr(secondes.length - 2);
			return timef;
		}
		
		$("#bstart").click(function(){
			if(confirm("Voulez-vous vraiment démarrer l'enregistrement ?") == true){
			    //On désactive les deux boutons par précaution, en attente du refresh
	            $("#bstart").addClass("disabled");
	            $("#bstop").addClass("disabled");
	        	$.ajax({
			        type: "GET",
		            url: "/api.php?action=live-get&plateforme="+ plateforme_id +"&status=started&key=" + secure_key, 
			        data: "",
			        success: function(msg){
		        		refresh();
			        }
	        	});
			}
		});
		
		$("#bstop").click(function(){
			if(confirm("Voulez-vous vraiment arrêter l'enregistrement ?") == true){
			    //On désactive les deux boutons par précaution, en attente du refresh
	            $("#bstart").addClass("disabled");
	            $("#bstop").addClass("disabled");
	        	$.ajax({
			        type: "GET",
		            url: "/api.php?action=live-get&plateforme="+ plateforme_id +"&status=stopped&key=" + secure_key, 
			        data: "",
			        success: function(msg){
		        		refresh();
			        }
	        	});
			}
		});

		$("#bpub").click(function(){
	        $("#bpub").addClass("disabled");
	    	$.ajax({
		        type: "GET",
	            url: "/api.php?action=live-get&plateforme="+plateforme_id+"&status=live_launch_ad_break&data_ad=" + $("#spub").val() + "&key="+secure_key, 
		        data: "",
		        success: function(msg){
	        		refresh();
		        }
	    	});
		});

		flashicons();

	    function flashicons(){
	        clearTimeout(flashicons);
	        refreshTimeout = setTimeout(flashicons,500);
	        
	        if($("#icon-live").css("color") == "rgb(255, 0, 0)"){
	        	$("#icon-live").css("color","rgb(100, 100, 100)");
	        }else if($("#icon-live").css("color") == "rgb(100, 100, 100)"){
	        	$("#icon-live").css("color","rgb(255, 0, 0)");
	        }
	        
	        if($("#icon-record").css("color") == "rgb(255, 0, 0)"){
	        	$("#icon-record").css("color","rgb(100, 100, 100)");	
	        }else if($("#icon-record").css("color") == "rgb(100, 100, 100)"){
	        	$("#icon-record").css("color","rgb(255, 0, 0)");
	        }
	    }

	    refresh();

	    function refresh(){
	    	clearTimeout(refresh);
        	refreshTimeout = setTimeout(refresh,10000);
	        
	         $.ajax({
		        type: "GET",
	            url: "/api.php?action=live-get&plateforme="+plateforme_id+"&status=get", 
		        data: "",
		        success: function(msg){
		        	console.log(msg);
	                var data = JSON.parse(msg);
	                clearnotif();

	                if(data.onair == false){
	                	if(isOnline != false){
	                		isOnline = false;
	                		isRecord = false;

		                	//Changement couleur icône
		                	$("#icon-live").css("color","");
		                	$("#icon-live").prop("title","Vous n'êtes pas en live");
		                	$("#icon-record").css("color","");
		                	$("#icon-record").prop("title","Vous n'êtes pas en enregistrement");
	                	}

	                	//Envoi notif
	                	callappend("error","Live déconnecté","Attention : votre live est déconnecté !");

	                	$("#bstart").addClass("disabled");
	                	$("#bstop").addClass("disabled");
	                	$("#bpub").addClass("disabled");
	                }else if(data.onair == true){
	                	if(isOnline != true){
	                		isOnline = true;
		                	//Changement couleur icône
		                	$("#icon-live").css("color","red");
		                	$("#icon-live").prop("title","Vous êtes en live");
	                	}

	                	//Envoi notif
	                	callappend("success","Live démarré","Votre live est en ligne. Tout a l'air bon !");

	                	//Statut de l'enregistrement
	                	if(data.record_status == "stopped"){
	                		$("#bstart").removeClass("disabled");
	                		callappend("warning","Enregistrement","Vous pouvez maintenant lancer l'enregistrement.");

	                		if(isRecord != false){
		                		isRecord = false;
				                	$("#icon-record").css("color","");
				                	$("#icon-record").prop("title","Vous n'êtes pas en enregistrement");
	                		}
	                	}else if(data.record_status == "started"){
	                		$("#bstop").removeClass("disabled");
	                		callappend("warning","Enregistrement","Un enregistrement est déjà en cours.");
	                		
	                		if(isRecord != true){
		                		isRecord = true;
			                	$("#icon-record").css("color","red");
			                	$("#icon-record").prop("title","Vous êtes en enregistrement");
	                		}
	                	}

	                	//Statut de la pub
	                	if(data.live_ad_break_end_time != null){
	                		//Pub en cours
	                		//On désactive le bouton lancer l'ad
	                		$("#bpub").addClass("disabled");
	                		callappend("warning","Publicité","La publicité est en cours de diffusion. Fin de la diffusion prévue à " + getdate(data.live_ad_break_end_time) + ".");
	                	}else{
	                		//Pub off
	                		//On active le bouton lancer l'ad
	                		$("#bpub").removeClass("disabled");
	                	callappend("success","Audience","Vous avez actuellement " + data.audience + " viewers.");
	                }
	           }
	       }
	        });
	    }
	});
</script>