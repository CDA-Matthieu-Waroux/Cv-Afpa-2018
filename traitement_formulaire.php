<?php
session_start();
/*
	********************************************************************************************
	CONFIGURATION
	********************************************************************************************
*/
$boolActif=true;
// destinataire est votre adresse mail. Pour envoyer à plusieurs à la fois, séparez-les par une virgule
$destinataire = 'matthieu_waroux59@hotmail.com';

// copie ? (envoie une copie au visiteur)
$copie = 'oui'; // 'oui' ou 'non'

// Messages de confirmation du mail
$message_envoye = "L'email a bien été envoyé !";
$message_non_envoye = "L'envoi du mail a échoué, veuillez réessayer s'il vous plaît!.";

// Messages d'erreur du formulaire
$message_erreur_formulaire = "Vous devez d'abord envoyer le formulaire.";
$message_formulaire_invalide = "Vérifiez que tous les champs soient bien remplis et que l'email soit sans erreur.";
$message_erreur_captcha ="Captcha non valide , Veuillez recommencez";

/*
	********************************************************************************************
	FIN DE LA CONFIGURATION
	********************************************************************************************
*/

// on teste si le formulaire a été soumis
if (!isset($_POST['envoi']))
{
    // formulaire non envoyé
    echo '<p>'.$message_erreur_formulaire.'</p>'."\n";
}
else
{
    /*
     * cette fonction sert à nettoyer et enregistrer un texte
     */
    function Enregistrement($text)
    {
        $text = htmlspecialchars(trim($text), ENT_QUOTES);
        if (1 === get_magic_quotes_gpc())
        {
            $text = stripslashes($text);
        }

        $text = nl2br($text);
        return $text;
    };

    /*
     * Cette fonction sert à vérifier la syntaxe d'un email
     */
    function IsEmail($email)
    {
        $value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
        return (($value === 0) || ($value === false)) ? false : true;
    }

    // formulaire envoyé, on récupère tous les champs.
    $nom     = (isset($_POST['nom']))     ? Enregistrement($_POST['nom'])     : '';
    $email   = (isset($_POST['email']))   ? Enregistrement($_POST['email'])   : '';
    $objet   = (isset($_POST['objet']))   ? Enregistrement($_POST['objet'])   : '';
    $message = (isset($_POST['message'])) ? Enregistrement($_POST['message']) : '';

    // On va vérifier les variables et l'email ...
    $email = (IsEmail($email)) ? $email : ''; // soit l'email est vide si erroné, soit il vaut l'email entré
    
    if(isset($_POST['captcha']))
    {
      if($_POST['captcha']==$_SESSION['code'])
      {
        if (($nom != '') && ($email != '') && ($objet != '') && ($message != ''))
        {
          $boolActif=true;
            // les 4 variables sont remplies, on génère puis envoie le mail
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'From:'.$nom.' <'.$email.'>' . "\r\n" .
            'Reply-To:'.$email. "\r\n" .
            'Content-Type: text/plain; charset="utf-8"; DelSp="Yes"; format=flowed '."\r\n" .
            'Content-Disposition: inline'. "\r\n" .
            'Content-Transfer-Encoding: 7bit'." \r\n" .
            'X-Mailer:PHP/'.phpversion();
      
            // envoyer une copie au visiteur ?
          if ($copie == 'oui')
          {
            $cible = $destinataire.';'.$email;
          }
          else
          {
            $cible = $destinataire;
          }
        } 
        else 
        {
          echo '<!DOCTYPE html>
                  <html>
                    <head>
                      <meta charset="utf-8" />
                      <meta http-equiv="X-UA-Compatible" content="IE=edge">
                      <title>Message non envoyé</title>
                      <meta name="viewport" content="width=device-width, initial-scale=1">
                      <link rel="stylesheet" type="text/css" media="screen" href="asset/css/bootstrap2.min.css" />
                      <script src="asset/js/bootstrap.min.js"></script>
                      <link rel = "icon" href="asset/img/messageNonEnvoye.png"/>
                      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
                      
                  </head>
                  <body>
                   <header>
                   <nav class="navbar navbar-expand-lg  fixed-top navbar-dark bg-dark">
                   <a class="navbar-brand" href="asset/others/CV Matthieu WAROUX - Recherche_Stage_DL.pdf">Télécharger CV</a>
                   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                     <span class="navbar-toggler-icon"></span>
                   </button>
                 
                   <div class="collapse navbar-collapse" id="navbarColor02">
                     <ul class="navbar-nav mr-auto">
                       
                       <li class="nav-item active">
                           <a class="nav-link" href="index.html">Cv</a>
                         </li>
                       
                       <li class="nav-item active">
                         <a class="nav-link" href="projets.html">Projets Réalisés</a>
                       </li>
                       
                       <li class="nav-item active">
                         <a class="nav-link" href="contact.html">Contact</a>
                       </li>
                       
                     </ul>    
                   </div>
           </nav>
                   </header>
                  <br><br><br><br><br><br>
                   <center>
                  <p class="darkinou">'.$message_formulaire_invalide.'
                  <br><a href="contact.html">Retour au formulaire</a></p>
                  </body>
                  </html>'
                  ;
                  $boolActif=false;
        };
        
      }
      else
        {
          echo '<!DOCTYPE html>
          <html>
          <head>
              <meta charset="utf-8" />
              <meta http-equiv="X-UA-Compatible" content="IE=edge">
              <title>Message non envoyé</title>
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <link rel="stylesheet" type="text/css" media="screen" href="asset/css/bootstrap2.min.css" />
              <script src="asset/js/bootstrap.min.js"></script>
              <link rel = "icon" href="asset/img/messageNonEnvoye.png"/>
              <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
              
          </head>
          <body>
            <header>
            <nav class="navbar navbar-expand-lg  fixed-top navbar-dark bg-dark">
            <a class="navbar-brand" href="asset/others/CV Matthieu WAROUX - Recherche_Stage_DL.pdf">Télécharger CV</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          
            <div class="collapse navbar-collapse" id="navbarColor02">
              <ul class="navbar-nav mr-auto">
                
                <li class="nav-item active">
                    <a class="nav-link" href="index.html">Cv</a>
                  </li>
                
                <li class="nav-item active">
                  <a class="nav-link" href="projets.html">Projets Réalisés</a>
                </li>
                
                <li class="nav-item active">
                  <a class="nav-link" href="contact.html">Contact</a>
                </li>
                
              </ul>    
            </div>
    </nav>
           </header>
          <br><br><br><br><br><br><br><br><br>
         <center>
         <p class="darkinou"> '.$message_erreur_captcha.'</p> <br> <a href="contact.html">Retour au formulaire</a>';
         $boolActif=true;

        } ;
    

        // Remplacement de certains caractères spéciaux
        $message = str_replace("&#039;","'",$message);
        $message = str_replace("&#8217;","'",$message);
        $message = str_replace("&quot;",'"',$message);
        $message = str_replace('<br>','',$message);
        $message = str_replace('<br />','',$message);
        $message = str_replace("&lt;","<",$message);
        $message = str_replace("&gt;",">",$message);
        $message = str_replace("&amp;","&",$message);

        // Envoi du mail
        $num_emails = 0;
        $tmp = explode(';', $cible);
        foreach($tmp as $email_destinataire)
        {
            if (mail($email_destinataire, $objet, $message, $headers))
                $num_emails++;
        }

        if ((($copie == 'oui') && ($num_emails == 2)) || (($copie == 'non') && ($num_emails == 1)))
        {
            echo '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <title>Message Envoyé</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" type="text/css" media="screen" href="asset/css/bootstrap2.min.css" />
                <script src="asset/js/bootstrap.min.js"></script>
                <link rel="icon" href="asset/img/messageEnvoye.png"/>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
                
            </head>
            <body>
            <header>
            <nav class="navbar navbar-expand-lg  fixed-top navbar-dark bg-dark">
            <a class="navbar-brand" href="asset/others/CV Matthieu WAROUX - Recherche_Stage_DL.pdf">Télécharger CV</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          
            <div class="collapse navbar-collapse" id="navbarColor02">
              <ul class="navbar-nav mr-auto">
                
                <li class="nav-item active">
                    <a class="nav-link" href="index.html">Cv</a>
                  </li>
                
                <li class="nav-item active">
                  <a class="nav-link" href="projets.html">Projets Réalisés</a>
                </li>
                
                <li class="nav-item active">
                  <a class="nav-link" href="contact.html">Contact</a>
                </li>
                
              </ul>    
            </div>
    </nav>
            </header>
            <br><br><br><br><br><br><br>
           <center> <div class="darkinou">'.$message_envoye.'<br> <a href="index.html">Retour au CV</a></div></center></body>';
        }
        else if ($boolActif==false)
        {
            echo '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <title>Message non envoyé</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" type="text/css" media="screen" href="asset/css/bootstrap2.min.css" />
                <script src="asset/js/bootstrap.min.js"></script>
                <link rel = "icon" href="asset/img/messageNonEnvoye.png"/>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
                
            </head>
            <body>
            <header>
            <nav class="navbar navbar-expand-lg  fixed-top navbar-dark bg-dark">
            <a class="navbar-brand" href="asset/others/CV Matthieu WAROUX - Recherche_Stage_DL.pdf">Télécharger CV</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          
            <div class="collapse navbar-collapse" id="navbarColor02">
              <ul class="navbar-nav mr-auto">
                
                <li class="nav-item active">
                    <a class="nav-link" href="index.html">Cv</a>
                  </li>
                
                <li class="nav-item active">
                  <a class="nav-link" href="projets.html">Projets Réalisés</a>
                </li>
                
                <li class="nav-item active">
                  <a class="nav-link" href="contact.html">Contact</a>
                </li>
                
              </ul>    
            </div>
    </nav>
            </header>
            <br><br><br><br><br><br><br><br><br>
           <center>
           <p><p class="darkinou">'.$message_non_envoye.'<br> <a href="contact.html">Retour au formulaire</a></p>';
        };
    }
    else
    {
        // une des 3 variables (ou plus) est vide ...
        echo '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Message non envoyé</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" type="text/css" media="screen" href="asset/css/bootstrap2.min.css" />
            <script src="asset/js/bootstrap.min.js"></script>
            <link rel = "icon" href="asset/img/messageNonEnvoye.png"/>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
            
        </head>
        <body>
        <header>
        <nav class="navbar navbar-expand-lg  fixed-top navbar-dark bg-dark">
        <a class="navbar-brand" href="asset/others/CV Matthieu WAROUX - Recherche_Stage_DL.pdf">Télécharger CV</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarColor02">
          <ul class="navbar-nav mr-auto">
            
            <li class="nav-item active">
                <a class="nav-link" href="index.html">Cv</a>
              </li>
            
            <li class="nav-item active">
              <a class="nav-link" href="projets.html">Projets Réalisés</a>
            </li>
            
            <li class="nav-item active">
              <a class="nav-link" href="contact.html">Contact</a>
            </li>
            
          </ul>    
        </div>
</nav>
        </header>
        <br><br><br><br><br><br>
       <center>
       <p class="darkinou">'.$message_formulaire_invalide.' <a href="contact.html">Retour au formulaire</a></p>'."\n";
    };
}; // fin du if (!isset($_POST['envoi']))
?>