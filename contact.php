
<?php


/*
	********************************************************************************************
	CONFIGURATION
	********************************************************************************************
*/
// destinataire est votre adresse mail. Pour envoyer à plusieurs à la fois, séparez-les par une virgule
$destinataire = 'matthieu_waroux59@hotmail.com';
 
// copie ? (envoie une copie au visiteur)
$copie = 'oui';
 
// Action du formulaire (si votre page a des paramètres dans l'URL)
// si cette page est index.php?page=contact alors mettez index.php?page=contact
// sinon, laissez vide
$form_action = '';
 
// Messages de confirmation du mail
$message_envoye = "Votre message nous est bien parvenu !";
$message_non_envoye = "L'envoi du mail a échoué, veuillez réessayer SVP.";
 
// Message d'erreur du formulaire
$message_formulaire_invalide = "Vérifiez que tous les champs soient bien remplis et que l'email soit sans erreur.";
 
/*
	********************************************************************************************
	FIN DE LA CONFIGURATION
	********************************************************************************************
*/

 
/*
 * cette fonction sert à nettoyer et enregistrer un texte
 */
function Rec($text)
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
$nom     = (isset($_POST['nom']))     ? Rec($_POST['nom'])     : '';
$email   = (isset($_POST['email']))   ? Rec($_POST['email'])   : '';
$objet   = (isset($_POST['objet']))   ? Rec($_POST['objet'])   : '';
$message = (isset($_POST['message'])) ? Rec($_POST['message']) : '';
 
// On va vérifier les variables et l'email ...
$email = (IsEmail($email)) ? $email : ''; // soit l'email est vide si erroné, soit il vaut l'email entré
$err_formulaire = false; // sert pour remplir le formulaire en cas d'erreur si besoin
 
if (isset($_POST['envoi']))
{session_start();
    if(isset($_POST['captcha'])){
        if($_POST['captcha']==$_SESSION['code']){
            echo "Code correct";
        
	if (($nom != '') && ($email != '') && ($objet != '') && ($message != '') )
	{
		// les 4 variables sont remplies, on génère puis envoie le mail
		$headers  = 'From:'.$nom.' <'.$email.'>' . "\r\n";
		//$headers .= 'Reply-To: '.$email. "\r\n" ;
		//$headers .= 'X-Mailer:PHP/'.phpversion();
 
		// envoyer une copie au visiteur ?
		if ($copie == 'oui')
		{
			$cible = $destinataire.';'.$email;
		}
		else
		{
			$cible = $destinataire;
		};
 
		// Remplacement de certains caractères spéciaux
		$message = str_replace("&#039;","'",$message);
		$message = str_replace("&#8217;","'",$message);
		$message = str_replace("&quot;",'"',$message);
		$message = str_replace('&lt;br&gt;','',$message);
		$message = str_replace('&lt;br /&gt;','',$message);
		$message = str_replace("&lt;","&lt;",$message);
		$message = str_replace("&gt;","&gt;",$message);
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
			echo '<p>'.$message_envoye.'</p>';
		}
		else
		{
			echo '<p>'.$message_non_envoye.'</p>';
		};
	}
	else
	{
		// une des 3 variables (ou plus) est vide ...
		echo '<p>'.$message_formulaire_invalide.'</p>';
		$err_formulaire = true;
    }
} else {
    echo "Code incorrect";
};
}; // fin du if (!isset($_POST['envoi']))



 
?>


<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Contact</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="asset/css/bootstrap2.min.css" />
    <script src="asset/js/bootstrap.min.js"></script>
    <link rel="icon" href="asset/img/contact.png"/>
    <link rel="stylesheet" href="asset/css/style.css"/>
    
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
<br><br><br><br><br>
<div class="row" >
<div class=" col-xs-2 col-md-4"></div>
<div class="col-xs-8 col-md-4">
<form  id="contact" method="post" action="traitement_formulaire.php">
    <center>
        <fieldset>
            <legend >Vos coordonnées :</legend>
            <br>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" aria-describedby="emailHelp" placeholder="Entrer votre nom ici" tabindex="1" name="nom">               
            </div>
            <br>           
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Entrer votre e-mail ici" tabindex="2" name="email">
                <small id="emailHelp" class="form-text text-muted">Une copie vous sera envoyer à ce mail</small>
            </div>           
        </fieldset>
        <br>
        <fieldset>
            <legend>Votre message :</legend>
            <br>
            <div class="form-group">
                <label for="objet" style="text-align:center">Objet du mail</label>
                <input type="text" class="form-control" id="objet" aria-describedby="emailHelp" placeholder="Entrer votre objet de mail ici" tabindex="3" name="objet">               
            </div>
            <br>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" id="message" rows="5" tabindex="4" name="message" placeholder="Entrer votre message ici"></textarea>
            </div>                      
        </fieldset>
        <br>
       <fieldset> <form action="contact.php" method="post">
           <img src="image.php" onclick="this.src='image.php?' + Math.random();" alt="captcha" style="cursor:pointer;">
            <input type="text" name="captcha"/>               
            </form>
        </fieldset>
     <br><br>
        
        <p class="bs-component"> <button class="btn btn-info" type="submit" name="envoi">Envoyer</button></p>
        </center>
    </form>

</div>
    
    <div class="col-xs-2 col-md-4"></div></div>

    <link rel="stylesheet" href="../_assets/css/custom.min.css">
   


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    
</body>
</html>