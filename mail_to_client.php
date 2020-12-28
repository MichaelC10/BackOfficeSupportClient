<?php
	require("classes/PHPMailer-master/src/PHPMailer.php");
	require("classes/PHPMailer-master/src/SMTP.php");
	require("classes/PHPMailer-master/src/Exception.php");
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	// Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);

	try{
		// Server settings
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->isSMTP();
		$mail->Host = 'mail.infomaniak.com';
		$mail->SMTPAuth = true;
		$mail->Username = 'example@gmail.com';
		$mail->Password = 'KoPmsQG6Bnhq5';
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port = 587;
		$mail->CharSet = "UTF-8";

		// Destinataires
		$mail->setFrom('someone@gmail.com');
		$mail->addAddress($_POST['email']);

		// Content
		$mail->isHTML(true);
		$mail->Subject = $_POST['sujet'];
		$mail->Body = $_POST['message'];

		$mail->send();

		echo 'Un message a été envoyé';

	}catch(Exception $e){
		echo "Le message n'a pas pu être envoyé. Erreur de messagerie: {$mail->ErrorInfo}";
	}

?>
