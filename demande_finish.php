<?php
	require("init.php");
?>

<?php
	$data = $sql->REQ("SELECT ticket, dateDemande, demande.tel, sujet, message, urgent, Contact.nom, Contact.prenom, email, Clients.Nom FROM demande, Contact, Clients WHERE demande.id_Contact = Contact.id AND Contact.id_Clients = Clients.id AND demande.id = ".$_GET["idDemande"]);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Mettre fin à la demande client</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;500&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/style_back_office_formulaires.css">
	<link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
</head>
<body>
	<h1>Demande terminée</h1>
	<div id="wrap_content">
		<div>
			<h2>Récap des infos de la demande</h2>
			<?php
				$urgent = $data[0]['urgent'];
				
				if ($urgent == 1)
				{
					echo '<i class="fa fa-circle fa-3x" id="pastilleRouge"></i>' ;
					echo '<div class="statutLabel">URGENT</div>';
				}
				else
				{
					$dateDemande = new DateTime($data[0]['dateDemande']);
					//var_dump($dateDemande);

					date_default_timezone_set('Europe/Paris');
					// quand on instancie la classe DateTime et que l'on ne précise pas de date, ça met par défaut la date courante (curdate)
					$dateCurrent = new DateTime();

					$dateDiff = $dateDemande->diff($dateCurrent);

					if($dateDiff->d >= 7)
					{
						echo '<i class="fa fa-exclamation-triangle fa-3x"></i>' ;
						echo '<i class="fa fa-circle fa-3x" id="pastilleOrange"></i>' ;
						echo '<div class="statutLabel">Plus d\'une semaine</div>';
					}
					else
					{
						echo '<i class="fa fa-circle fa-3x" id="pastilleOrange"></i>' ;
						echo '<div class="statutLabel">Non urgent</div>';
					}
				}
			?>
			<table>
				<tr>
					<td>N° Ticket</td>
					<td><?php echo $data[0]['ticket']; ?></td>
				</tr>
				<tr>
					<td>Nom client</td>
					<td><?php echo $data[0]['Nom']; ?></td>
				</tr>
				<tr>
					<td>Date</td>
					<td><?php echo PrettyDate($data[0]['dateDemande']); ?></td>
				</tr>
				<tr>
					<td>Téléphone</td>
					<td><?php echo $data[0]['tel']; ?></td>
				</tr>
				<tr>
					<td>Nom contact</td>
					<td><?php echo $data[0]['nom']; ?></td>
				</tr>
				<tr>
					<td>Prénom contact</td>
					<td><?php echo $data[0]['prenom']; ?></td>
				</tr>
				<tr>
					<td>Sujet</td>
					<td><?php echo $data[0]['sujet']; ?></td>
				</tr>
				<tr>
					<td>Message</td>
					<td><?php echo $data[0]['message']; ?></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><?php echo $data[0]['email']; ?></td>
				</tr>
				<tr>
					<td>Urgent</td>
					<td>
						<?php if ($data[0]['urgent'] == 1)
							  {
								echo "oui<br>";
							  }
							  else
							  {
								echo "non<br>";
							  }
						 ?>
					</td>
				</tr>
				<tr>
					<td>Chemin(s) fichier(s)</td>
					<td>
						<?php
							// Affichage des éventuels fichiers joints
							$cheminFichiers = $sql->REQ("SELECT chemin FROM fichier WHERE id_demande = ".$_GET['idDemande']);
							if (!isset($cheminFichiers))
							{
								echo "Aucun fichier joint";
							}
							else
							{
								foreach ($cheminFichiers as $ligne => $fichier)
								{
									echo $fichier['chemin']."<br>";
								}
							}
						?>
					</td>
				</tr>
			</table>
		</div>
		<div>
			<h2>Envoyer un mail au client</h2>
			<form method="POST" action="">
				<p>
					<input type="email" name="email" value="<?php echo $data[0]['email']; ?>" required>
				</p>
				<p>
					<input type="text" name="sujet" value="<?php echo $data[0]['sujet'].' - Ticket n°'.$data[0]['ticket']; ?>" required>
				</p>
				<p>
					<?php $date = new DateTime($data[0]['dateDemande']); ?>
					<textarea name="message" rows="10" required>Bonjour,
nous vous informons que votre demande <?php echo $data[0]['ticket'];?> du <?php echo $date->format('d/m/Y');?> ayant pour objet "<?php echo $data[0]['sujet'];?>" a bien été traitée par notre support.
Nous espérons avoir satisfait pleinement votre demande !
Passez une excellente journée.

Cordialement,
Le support</textarea>
				</p>
				<input type="submit" name="submit" value="Envoyer le mail">
			</form>
		</div>
	</div>
</body>
</html>

<?php
	// Validation du formulaire
	if (isset($_POST['submit']))
	{
		// Insert du mail sur la table message_to_client
		date_default_timezone_set('Europe/Paris');
		$date = date("Y-m-d H:i:s");

		$dataInsert = array(
			"email_mtc" => $_POST['email'],
			"sujet_mtc" => $_POST['sujet'],
			"message_mtc" => $_POST['message'],
			"date_mtc" => $date,
			"id_demande_mtc" => $_GET['idDemande']
		);

		$sql->InsertSimple($dataInsert, "message_to_client");

		// Update du champ terminee de la demande en question (table demande) -> je passe terminee à 1
		$tab = array(
			"terminee" => 1
		);

		$sql->UpdateSimple($tab, "demande", "id = ".$_GET['idDemande']);

		//envoie du mail
		require("mail_to_client.php");
	}

?>

