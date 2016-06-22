<?php
    try{
        system("mysqldump --host= --user= --password= dbase > db.sql");
        echo "Etape #1 effectu&eacute;e !<br>";
    }catch(Exception $e){
        echo "Impossible de se connecter<br>";
        echo $e;
        die();
    }

    $ftp = ftp_connect('', 21, 5);
    ftp_login($ftp, '', '');
    ftp_pasv($ftp, true);

    if(ftp_put($ftp, 'db/db-'.time().'.sql', 'db.sql', FTP_ASCII)){
        echo "Sauvegarde envoy&eacute;e !";
    }else{echo "Erreur lors du traitement du fichier";}
?>