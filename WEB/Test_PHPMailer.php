<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . "../../vendor/autoload.php";

$plf_mail = new PHPMailer();
$plf_mail->From = "Christian.lurkin@hotmail.com";
$plf_mail->FromName = "Christian Lurkin PLF";
$plf_mail->addAddress("christian.lurkin@gmail.com");
$plf_mail->addReplyTo("Christian.lurkin@hotmail.com");
$plf_mail->isHTML(true);
$plf_mail->Subject = "PLF logging";
$plf_mail->Body = "<i>Mail body in html</i>";
$plf_mail->AltBody = "Mail body in Text.";

if ( !$plf_mail->send()) {
    echo "Mailer Error: " . $plf_mail->ErrorInfo;
} else {
    echo "message successfully sent.";
}


