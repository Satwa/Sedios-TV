<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0">
        <title>Chat - Maouw</title>
        <link rel="stylesheet" href="css/style.css">

    </head>
    <body>
        <div id="infos">
            <div id="avatarInfo" style="display:none;">
                <img src="">
            </div>
            <div id="pseudoInfo" style="display:none;">
            </div>
        </div>
        <div id="messages">
          <div class="message" id="msgadm" style="display:none;">
            <span id="msg{{id}}">
            <img id="imgAvatar" src="{{{user.avatar}}}" style="height:20px;">
            <div class="info">
              <strong>{{user.pseudo}}</strong>&nbsp;:&nbsp;
              {{{message}}}
              <span class="date">{{message.id}}</span>
            </div>
            
            </span>
          </div>
          <div class="message" id="msgtpl" style="display:none;">
            <span id="msg{{id}}">
            <img id="imgAvatar" src="{{{user.avatar}}}" style="height:20px;">
            <div class="info">
              <strong>{{user.pseudo}}</strong>&nbsp;:&nbsp;
              {{message}}
              <span class="date">{{message.id}}</span>
            </div>
            
            </span>
          </div>
        </div>
        <div id="bloc-channel" style="display:none;">
            <div class="closeForm" style="display:none;"></div>
            <div id="channelform">
            <h1>Choix du channel</h1>
                <div id="channeljs"></div>
            </div>
        </div>
            
        <div id="login">
            <div class="closeForm" style="display:none;"></div>
          <form action="" id="loginform">
            <h1 id="titleForm">Chargement...</h1><br/>
            <div id="errorInfos" style="display:none;">Erreur</div>
            <div id="successInfos" style="display:none;">Success</div>
            <p id="infoForm">Connexion au serveur en cours...</p>
            <span id="zPseudo" style="display:none"><input type="text" name="login" id="pseudo" placeholder="Pseudo" required="required"><br/><br/></span>
              <span id="zMail" style="display:none"><input type="mail" name="mail" id="mail" placeholder="E-mail" required="required"><br/><br/></span>
                <span id="zPassword" style="display:none"><input type="password" name="password" id="password" required="required" placeholder="Mot de passe"><br/><br/></span>
            <input type="submit" value="Connexion" id="zInput" style="display:none"><br/><br/>
              <div id="imgload" style="display:none"><img src="css/loading.gif" alt="Chargement..."></div>
          </form>
        </div>

        <form action="" id="form">
          <input type="text" id="message" class="text" placeholder="Connectez-vous pour pouvoir poster un message" disabled="disabled" autocomplete="off"/>
        </form>

    <?php
        if(!isset($_SESSION['tv'])){
            $chan = "WaitingRoom";   
        }
        if(isset($_SESSION['Auth']['user'])){
            $pseudo = $_SESSION['Auth']['user']['name'];
            $pass   = $_SESSION['Auth']['user']['pass'];
            $chan   = $_SESSION['tv'];
            echo("<script>var channel_default = '$chan'; var info_start = {}; info_start['pseudo'] = '$pseudo'; info_start['password'] = '$pass'; info_start['channel'] = '$chan';</script>");
        }
    ?>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="js/mustache.js"></script>
    <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/md5.js"></script>
    <script src="https://cdn.socket.io/socket.io-1.2.1.js"></script>
    <script src="js/client.js"></script>
  </body>
</html>