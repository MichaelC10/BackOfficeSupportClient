<?php
	require("sql.php");
	$sql = new sql("localhost:8889", "formulaire_contact", "root", "root");
	require("fonctions.php");

	// TRACE est une constante qui permet d'afficher ou non les erreurs SQL
	// true : affiche les erreurs | false : n'affiche pas les erreurs
	define('TRACE', false);
?>