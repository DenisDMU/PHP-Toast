<?php
// Vos fonctions (token, traitement des fichiers etc...)
//Création et gestion token

function setToto()
{
    if($_COOKIE['token'] != 1)
    {
        $char = 'abcedfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tok = '';
        for ($i=1; $i < 25; $i++) { 
            $tok.= $char[rand(0,strlen($char)-1)];
        }
        $_SESSION['token'] = $tok;
        setcookie('token',1,(time()+900));
        return $tok; 
        
    }else
    {
        return $_SESSION['token'];
    }
}

function getToto()
{
    if($_GET['token'] == $_SESSION['token'])
    {
        return true;
    }else
    {
        return false;
    }
}

//Fonction pour renomer les fichiers
function renommeFichier(){
    if(is_uploaded_file($_FILES['files[]']['tmp_name']))
            {
                $extension = ['.JPG','.jpg','.PNG','.png','.GIF','.gif','.PDF','.pdf'];
                $scan = strrchr($_FILES['files[]']['name'],'.');
                if(in_array($scan,$extension))
                {
                    $newname = time().$_FILES['files[]']['name'];
                }
            }
            return $newname;
    }

?>