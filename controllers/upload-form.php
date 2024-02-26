<?php
session_start();
require('../inc/db.php');
require_once('../smarties.php');
require_once('../inc/functions.php');

if(!$_COOKIE['id'] && !$_COOKIE['passwd'])
{
    echo 'Accès refusé, n\'essaye pas de nous arnaquer';
    exit;
}
else
{
    $token = setToto();
}


    if(is_uploaded_file($_FILES['files[]']['tmp_name']) && $_COOKIE['id'])
    {
        //recupération de la fonction du fichier pour le renommer et ainsi éviter les doublons ou les conflits en terme de droit
        $newname = renommeFichier();
        $sql = 'INSERT INTO fichiers SET
                id = :id_utilisateur,
                files[] = :fichier';
        $cokie = unserialize($_COOKIE['user']);
        $req = $dbh->prepare($sql);
        $req -> bindParam(':fichier',$newname,PDO::PARAM_STR);
        $req -> bindParam(':id_utilisateur',$_COOKIE['id'],PDO::PARAM_STR);

        if($req->execute())
        {
            echo 'Fichier bien téléchargé';
        }else
        {
            echo 'Erreur lors du téléchargement du fichier.';
        }
    }



$smarty -> display ('../template/upload.tpl');

?>