var STATUS_SERVER_CLOSED = -1;
var STATUS_READING = 0;
var STATUS_CONNECTION = 1;
var STATUS_CONNECTED = 3;

var status = STATUS_READING;

var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
var user = {};
var process_login = false;

if(channel_default == undefined){
var channel_default = "Live";
}

var msgtpl = $('#msgtpl').html();
var msgadm = $('#msgadm').html();
$('#msgtpl').remove();
$('#msgadm').remove();

var socket = io.connect('http://92.222.162.141:1337');

function showFormError(string){
    $('#errorInfos').fadeOut();
    $('#errorInfos').fadeIn(100);
    $('#errorInfos').html(string);
}
function showFormSuccess(string){
    $('#successInfos').fadeOut();
    $('#successInfos').fadeIn(100);
    $('#successInfos').html(string);
}

function closeFormError(){
    $('#errorInfos').fadeOut(100);
    $('#errorInfos').html("");
}

function closeFormSuccess(){
    $('#successInfos').fadeOut(100);
    $('#successInfos').html("");
}

function showMessage(message){
    if(message.color != "#CCC" && message.color != "#FFFFFF" && message.color != "#41B7FE" && message.color != "#EAFFA8"){
        $('#messages').append('<div class="message" id="msgadmin-' + message.id + '" style="background-color:' + message.color + ';">' + Mustache.render(msgadm,message) + '</div>');
    }else{
        $('#messages').append('<div class="message" id="msg' + message.id + '" style="background-color:' + message.color + ';">' + Mustache.render(msgtpl,message) + '</div>');
    }
    $('#messages').animate({scrollTop : $('#messages').prop('scrollHeight')}, 200);
    setTimeout(function(){$('#messages').animate({scrollTop : $('#messages').prop('scrollHeight')}, 100);},400);

}

//s'abonner au channel
socket.emit('subscribe',channel_default);

socket.emit('requestConnection');

socket.on('acceptedConnection',function(){
    $("#login").fadeOut(100); 
    $('.closeForm').fadeIn(100);

    if(info_start != undefined){
        socket.emit('login', {
            pseudo : info_start["pseudo"],
            password : info_start["password"],
            channel : info_start["channel"]
        });
    }
});

socket.on('showErrorOnLogin',function(msg){
    showFormError(msg.message);
    $("#imgload").fadeOut(200);
    process_login = false;
});

socket.on('showSuccessOnLogin',function(msg){
    showFormSuccess(msg.message);
});

socket.on('logged',function(userlog){
    status = STATUS_CONNECTED;
    user = userlog;
    closeFormError();
    $("#imgload").fadeOut(200);
    process_login = false;
    $("#login").fadeOut(200);
    $("#connexion").fadeOut(200,function(){
        $("#deconnexion").fadeIn(200);
        $("#channel").fadeIn(200);
        $("#settings").fadeIn(200);
    });
    $("#avatarInfo img").attr("src",user.avatar);
    $("#pseudoInfo").html(user.pseudo);
    $("#avatarInfo").fadeIn(200);
    $("#pseudoInfo").fadeIn(200);
    $("#message").prop("disabled",false);
    $("#message").prop("placeholder","");
    $("#message").focus();
});


socket.on('loadchannel',function(channels){
    $("#channeljs").html("");
    channels_array = JSON.parse(channels);
    for(var k in channels_array){
        $("#channeljs").html($("#channeljs").html() + '<a href="" onclick="joinchannel(\'' + channels_array[k]["name"] + '\');return false;"><div class="bloc-channel-specific">#' + channels_array[k]["name"] + '</div></a>');
    }
});

socket.on('forcejoin',function(channel){
    joinchannel(channel);
});

function joinchannel(channel){
    socket.emit('subscribe',channel);
    $("#bloc-channel").fadeOut(200);
}

$("#channel").click(function(){
    $("#bloc-channel").fadeIn(200);
    socket.emit('getchannels');
});

$("#settings").click(function(){
    $("#bloc-settings").fadeIn(200);
    socket.emit('getuser');
});

socket.on('getuser',function(user){
    $("#settings-pseudo").val(user.pseudo);
    $("#settings-mail").val(user.mail);
    $("#settings-password").val();
    $("#settings-mail").keydown();
});

socket.on('update_settings_complete',function(user){
    $("#bloc-settings").fadeOut(200);
    $("#avatarInfo img").attr("src",user.avatar);
});

$("#settings-mail").keydown(function(event){
    if(event.which == 13){
     event.preventDefault();
    }
    setTimeout(function(){
    var hash = CryptoJS.MD5($('#settings-mail').val());
    hash = String(hash);
    $("#settings-avatar").attr("src","https://gravatar.com/avatar/" + hash + "?s=80&d=identicon");
    },10);
});


$('#form').submit(function(event){
    event.preventDefault();
    if($('#message').val() != ""){
        socket.emit('newmsg', {message: $('#message').val()});
        $('#message').val('');
        if(!(user.rang > 0)){
            $("#message").prop('disabled', true);
            setTimeout(function() {
                $("input").prop('disabled', false);
                $('#message').focus();
            }, 5000);
        }else{
            $('#message').focus();
        }
    }
});

socket.on('newmsg',function(message){
    showMessage(message);
});

socket.on('send_disconnect',function(){
    socket.emit('forceDisconnect');
    console.log("déconnexion");
    $("#bloc-settings").html('<div id="settingsform"><h1 id="titleForm">Erreur</h1><div id="errorInfos" style="display:block;">Vous avez été déconnecté...</div></div>');
    $("#bloc-settings").fadeIn(200);
    setTimeout(iamatimer,5000);
    function iamatimer(){
        window.location.reload();
    }
});

$(".closeForm").click(function(){
   $("#login").fadeOut(200); 
   $("#bloc-channel").fadeOut(200); 
   $("#bloc-settings").fadeOut(200);
});

$("#deconnexion").click(function(){
    socket.emit('disconnect');
});

$("#connexion").click(function(){
    closeFormError();
    closeFormSuccess();
    $("#zPseudo").fadeIn(0);
    $("#zMail").fadeOut(0);
    $("#mail").prop("required",false);
    $("#zPassword").fadeIn(0);
    $("#zInput").fadeIn(0);
    $("#zInput").val("Connexion");
    $("#zInput").fadeIn(0);
    $("#titleForm").html("Connexion");
    $("#infoForm").html("Pour vous connecter sur le chat, entrez votre pseudo et votre mot de passe");
    $("#login").fadeIn(200);
    $("#pseudo").focus();
    status = STATUS_CONNECTION;
});

$("#deconnexion").click(function(){
    socket.emit("deconnexion");
    window.location.reload();
});

$('#loginform').submit(function(event){
    event.preventDefault();
    if(process_login == false){
        if(status == STATUS_CONNECTION){
            $('#mail').val("");
            var hash = CryptoJS.SHA1($('#password').val());
            hash = String(hash);
            $("#imgload").fadeIn(10);
            process_login = true;
            socket.emit('login',{
                pseudo : $('#pseudo').val(),
                password : hash,
                channel : channel_default,
            });
        }
    }
});