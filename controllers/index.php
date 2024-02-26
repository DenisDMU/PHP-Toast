<?php
session_start();
// Page accessible uniquement aux personnes connectées
require_once('../autoload.php');
require_once('../inc/db.php');
require_once('../inc/functions.php');
require_once('../smarties.php');

if(!$_COOKIE['id'] && !$_COOKIE['passwd'])
{
    echo 'Accès refusé, n\'essaye pas de nous arnaquer';
    exit;
}else
{
    $token = setToto();
}


    if($_COOKIE['id'] && $_COOKIE['passwd'])
    {
        $cocokie = unserialize($_COOKIE['user']);
        $sql = $dbh->prepare('SELECT * FROM utilisateurs WHERE id = :id AND password = :password');
        $sql->bindValue(':id',$_COOKIE['id'],PDO::PARAM_INT);
        $sql->bindValue(':password',$_COOKIE['passwd'],PDO::PARAM_STR);
        $sql->execute();
    
        if($sql->rowCount() == 1)
        {
            $result = $sql->fetch(PDO::FETCH_ASSOC);
            $_SESSION['connect'] = 1 ;
            
            //Je boucle sur ma requete SQL pour placer les fichiers dans un tableau et pouvoir les afficher dans l'index avec
            //le smarty assign 
            while ($fichiers = $dbh->query('SELECT * FROM fichiers') -> fetchAll(PDO::FETCH_ASSOC))
            {
                $retour[] = $fichiers;
            }
    
        }else

        {
            echo 'Vous n\'êtes pas dans nos tables de données, que faites vous ici ?';
        }
    }





//verif des cookies







$smarty->assign('fichiers',$retour);



$smarty->display('../template/index.tpl');
?>