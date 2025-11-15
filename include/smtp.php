<?php
if (!defined('INCLUDE_PATH')) {
    define('INCLUDE_PATH', __DIR__ . '/');
}
require INCLUDE_PATH . 'vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;

// MESSAGE & EMAIL CONFIGURATION FOR SCRIPT
class message{
    private $conn;
    public function send_mail($email, $message, $subject){

        $mail = new PHPMailer();
        //SMTP Settings
        $mail->isSMTP();
        $mail->Host = "mail.westbridgetrust.com"; // Change Email Host
        $mail->SMTPAuth = true;
        $mail->Username = "info@westbridgetrust.com"; // Change Email Address
        $mail->Password = '+Westbridgetrust123'; // Change Email Password
        $mail->Port = 587; //587
        $mail->SMTPSecure = "tls"; //tls
        $mail->SMTPDebug = 0; // Disable debug output

        //Email Settings
        $mail->isHTML(true);
        $mail->setFrom('info@westbridgetrust.com','Westbridge Trust'); // Change
        $mail->addAddress($email);
        $mail->AddReplyTo("info@westbridgetrust.com','Westbridge Trust"); // Change
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $mail->Send();


    }

}
