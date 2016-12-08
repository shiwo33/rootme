<?php

set_time_limit(0); // On met la durée maximale du script à l'infini.

$socket = fsockopen('irc.root-me.org','6667'); // On ouvre la connexion au serveur en tant que pointeur de fichier.

// Vérification de la bonne connexion :
if(!$socket)
{
	// Si on n'a pas réussi, on affiche un message d'erreur et on quitte.
	echo 'Impossible de se connecter';
	exit;
}

// On renseigne l'USER : ici, je mets un peu n'importe quoi, vu que le serveur ne prend en compte que le premier argument (mais qu'il a besoin de 4 arguments).
fputs($socket,"USER Woshi_bot woshi woshi woshi\r\n");
// On donne le NICK :
fputs($socket,"NICK Woshi_bot\r\n");

$continuer = 1; // On initialise une variable permettant de savoir si l'on doit continuer la boucle.

while($continuer) // Boucle principale.
{

	$donnees = fgets($socket, 1024); // Le 1024 permet de limiter la quantité de caractères à recevoir du serveur.
	$retour = explode(':',$donnees); // On sépare les différentes données.
	// On regarde si c'est un PING, et, le cas échéant, on envoie notre PONG.
	if(rtrim($retour[0]) == 'PING')
	{
		fputs($socket,'PONG :'.$retour[1]);
		$continuer = 0;
	}
	 if($donnees) // Si le serveur a envoyé des données, on les affiche.
		echo $donnees;
}

fputs($socket,"JOIN #root-me_challenge\r\n"); // On rejoint le canal 

// Boucle principale du programme :
while(1)
{
	$donnees = fgets($socket,1024); // On lit les données du serveur.
	if($donnees) // Si le serveur nous a envoyé quelque chose.
	{
		echo $donnees;
		$commande = explode(' ',$donnees);
		$message = explode(':',$donnees);
		if($commande[0] == 'PING') // Si c'est un PING, on renvoie un PONG.
		{
			fputs($socket,"PONG ".$commande[1]."\r\n");
		}
	
		if($commande[1] == 'PRIVMSG') // Si c'est un message.
		{
		    if(trim($message[2]) == '!run') // commande pour faire la requete à candy
			{
				fputs($socket,"PRIVMSG candy !ep1 \r\n"); 
				$rep = fgets($socket,256);
				$var = explode(" / ",$rep);
				$vart = explode(":", $var[0]);

				
				$var1 = (int) $vart[2];
				$var2 = (int) $var[1];
				//debug	
				fputs($socket, "PRIVMSG woshi2 var1 " .$var1. "\r\n");
				fputs($socket, "PRIVMSG woshi2 var2 " .$var2. "\r\n");
				$result = sqrt($var1);
				$result = $result * $var2;
				$result = round($result,2);
				fputs($socket, "PRIVMSG candy !ep1 -rep " .$result. "\r\n");
				fputs($socket, "PRIVMSG woshi2 !ep1 -rep " .$result. "\r\n");
				//sleep(20);
				$rf = fgets($socket,256);
				fputs($socket, "PRIVMSG woshi2 !ep1 -rep " .$rf. "\r\n");
			}elseif(trim($message[2][1]) == 'q'){
				fputs($socket,"QUIT \r\n");
				//fputs($socket, "PRIVMSG woshi2 QUIT send\r\n");
			}else {
				fputs($socket, "PRIVMSG woshi2" .$message[2]. "\r\n");

			}
		}
	}
	usleep(100); // On fait « dormir » le programme afin d'économiser l'utilisation du processeur.
}

?>

