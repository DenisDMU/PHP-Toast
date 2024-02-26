<?php
// Page inaccessible si la personne est connecté
session_start();
require('../inc/db.php');
require_once('../inc/functions.php');
require_once('../smarties.php');

if($_SESSION['connect'] == 1)
{
    exit;
}



    if(!empty($_POST['username']) && !empty($_POST['password']))
    {
        $sql = $dbh->prepare('SELECT * FROM utilisateurs WHERE email = :email LIMIT 1');
        $sql -> bindValue(':email',$_POST['username'],PDO::PARAM_STR);
        $sql -> execute();
        
        if($sql->rowCount() == 0)
        {
            echo 'Vous n\'êtes pas encore connu dans notre BDD';
        }else
        {
            if($sql->rowCount()==1)
            {
                $result = $sql -> fetch(PDO::FETCH_ASSOC);
                if(password_verify($_POST['password'],$result['password']))
                {
                setcookie('id',$result['id'],(time()+900));
                setcookie('passwd',$result['password'],(time()+900));

                $cocokie = ['id'=> $result['id'],'passwd' =>$result['password']];
                setcookie('user',serialize($cocokie),(time()+900));

                $token = setToto();
                
                }else
                {
                echo 'Erreur dans le mot de passe. Veuillez le saisir à nouveau.';
                }

            }
        }

        


    }




$smarty ->display('../template/login.tpl')
?>