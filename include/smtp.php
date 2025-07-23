<?php
const rootDir = '/home/multistream6/domains/dashboard.westbridgetrust.com/public_html/';
require rootDir . 'include/vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;

// MESSAGE & EMAIL CONFIGURATION FOR SCRIPT
class message{
    private $conn;
    public function send_mail($email, $message, $subject){

        $mail = new PHPMailer();
        //SMTP Settings
        //$mail->isSMTP();
        $mail->isMail();
        $mail->Host = "mail.westbridgetrust.com"; // Change Email Host
        $mail->SMTPAuth = true;
        $mail->Username = "info@westbridgetrust.com"; // Change Email Address
        $mail->Password = '+Westbridgetrust123'; // Change Email Password
        $mail->Port = 587; //587
        $mail->SMTPSecure = "ssl"; //tls

        //Email Settings
        $mail->isHTML(true);
        $mail->setFrom('info@westbridgetrust.com','Western National Bank of America'); // Change
        $mail->addAddress($email);
        $mail->AddReplyTo("info@westbridgetrust.com", "Western National Bank of America"); // Change
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $mail->Send();


    }

}
