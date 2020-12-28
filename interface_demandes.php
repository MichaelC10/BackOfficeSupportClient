<?php
	require("init.php");

	$demandes = $sql->REQ("SELECT ticket, Clients.Nom, sujet, dateDemande, demande.id, urgent FROM demande, Contact, Clients WHERE demande.id_Contact = Contact.id AND Contact.id_Clients = Clients.id AND terminee = 0 ORDER BY urgent DESC, dateDemande ASC");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Interface de gestion des demandes</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;500&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/style_interface_demandes.css">
	<link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
</head>
<body>
	<h1>Liste des demandes clients</h1>
	<table>
		<tr>
			<th class="statut"></th>
			<th>N°Ticket</th>
			<th>Nom du Client</th>
			<th>Sujet</th>
			<th>Date</th>
			<th>Actions</th>
		</tr>
		<?php
		if($demandes == NULL)
		{ ?>
			<tr>
			<td>Aucune demande actuellement</td>
			</tr>
		<?php
		}
		else
		{
			foreach ($demandes as $ligne => $champs)
			{ 	
		?>
				<tr>
					<td class="statut">
						<?php
							if ($champs['urgent'] == 1)
							{
								echo '<i class="fa fa-circle fa-2x" id="pastilleRouge"></i>' ;
							}
							else
							{
								$dateDemande = new DateTime($champs['dateDemande']);
								//var_dump($dateDemande);

								date_default_timezone_set('Europe/Paris');
								// quand on instancie la classe DateTime et que l'on ne précise pas de date, ça met par défaut la date courante (curdate)
								$dateCurrent = new DateTime();

								$dateDiff = $dateDemande->diff($dateCurrent);

								if($dateDiff->d >= 7)
								{
									echo '<i class="fa fa-exclamation-triangle fa-2x"></i>' ;
									echo '<i class="fa fa-circle fa-2x" id="pastilleOrange"></i>' ;
								}
								else
								{
									echo '<i class="fa fa-circle fa-2x" id="pastilleOrange"></i>' ;
								}
							}
						?>
					</td>
					<td> <?php echo $champs['ticket']; ?> </td>
					<td> <?php echo $champs['Nom']; ?> </td>
					<td> <?php echo $champs['sujet']; ?> </td>
					<td> <?php echo PrettyDate($champs['dateDemande']); ?> </td>
					<td>
						<a href="demander_infos_client.php?idDemande=<?php echo $champs['id'];?>"><button>+ d'infos</button></a>
						<a href="demande_finish.php?idDemande=<?php echo $champs['id'];?>"><button>terminer</button></a>
					</td>
				</tr>
			<?php
			}
		}
		?>
	</table>
</body>
</html>

