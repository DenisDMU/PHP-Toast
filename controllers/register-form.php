<?php
// Page inaccessible si la personne est connecté
session_start();
//On a besoin de la db
require('../inc/db.php');
require_once '../smarties.php';
//Importe (enfin use) de PHPMailer
require_once '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Si déja connecté, on le renvoi à la page index
if($_SESSION['connect'] == 1)
{
    exit;
}


//Gestion formulaire
    //Requete SQL pour vérif si email déjà présent dans la base de données
    $sql = $dbh ->prepare('SELECT * FROM utilisateurs WHERE email = :email LIMIT 1 ');
    $sql -> bindValue(':email',$_POST['username'],PDO::PARAM_STR);
    $sql->execute();

    //Si rowCount == 1 ça veut dire qu'email déjà présent donc on exit
    if($sql->rowCount()==1){
        echo 'Vous êtes déjà inscrit';
    }else
    {  //si rowCount == 0 ca veut dire que l'email n'est pas connu et qu'on doit donc accepter la demande d'inscription
     if($sql->rowCount()==0)
        {
            if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['confirm-password']))
            {
                if($_POST['password'] == $_POST['confirm-password'])
                {
                    //Hash du password
                    $mdp = password_hash($_POST['password'],PASSWORD_DEFAULT);
                    //Preparation sql
                    $sql = 'INSERT INTO utilisateurs SET
                            email = :email,
                            password = :password';
                    $req = $dbh->prepare($sql);
                    $req->bindParam(':email',$_POST['username'],PDO::PARAM_STR);
                    $req ->bindParam(':password',$mdp,PDO::PARAM_STR);

                if($req->execute())
                {
                    $mail = new PHPMailer(true);
                    try{
                        //Settings du serveur
                        $mail ->isSMTP();
                        $mail->Host = 'dwwm2324.fr';
                        $mail->SMTPAuth   = true; 
                        $mail->Username = 'contact@dwwm2324.fr';
                        $mail->Password = 'm%]E5p2%o]yc';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   
                        $mail->Port = 465;
                        //Adresse d'envoi ainsi que adresse du destinataire
                        $mail ->setFrom('contact@dwwm2324.fr','Contact');
                        $adressmail = $_POST['username'];
                        $mail ->addAddress($adressmail);
                        //Corps du mail
                        $mail->Subject = 'Bienvenue chez nous';
                        $mail->Body = 'En vous remerciant de votre inscription chez nous';

                        //Envoi du mail
                        $mail -> send();
                        echo 'Un mail vous a été envoyé.';
                    }catch(Exception $e)
                    {
                        echo "Echec dans l'envoi du mail. Erreur de type: {$mail->ErrorInfo}";
                    }
                   
                }else
                {
                    echo 'Vous n\'avez pas réussi l\'étape de l\'inscription.';
                }
                }
            }
        }
    }

$smarty ->display('../template/register.tpl')

?>