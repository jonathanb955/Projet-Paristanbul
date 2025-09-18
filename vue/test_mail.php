<?php
require __DIR__.'/mailerConfig.php';      // <- même dossier
require __DIR__.'/../vendor/autoload.php';// <- vendor est au-dessus


$mail = new PHPMailer\PHPMailer\PHPMailer(true);
try {
    if (USE_SMTP) {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port       = SMTP_PORT;
    }
    $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
    $mail->addAddress('ton.adresse.de.test@exemple.com');
    $mail->isHTML(true);
    $mail->Subject = 'Test SMTP OK';
    $mail->Body    = 'Hello, SMTP fonctionne ✅';
    $mail->send();
    echo 'OK';
} catch (Throwable $e) {
    echo 'Erreur: '.$e->getMessage();
}
