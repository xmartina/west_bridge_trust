<?php
if (!defined('INCLUDE_PATH')) {
    define('INCLUDE_PATH', __DIR__ . '/');
}

// MESSAGE & EMAIL CONFIGURATION FOR SCRIPT
class message{
    private $conn;
    
    public function send_mail($email, $message, $subject){
        // Ensure required constants are defined
        if (!defined('WEB_EMAIL')) {
            define('WEB_EMAIL', 'noreply@example.com');
        }
        if (!defined('WEB_TITLE')) {
            define('WEB_TITLE', 'Website');
        }
        
        // Use PHP's native mail() function
        // Set email headers for HTML content
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        // Additional headers
        $headers .= 'From: ' . WEB_TITLE . ' <' . WEB_EMAIL . '>' . "\r\n";
        $headers .= 'Reply-To: ' . WEB_EMAIL . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        
        // Send email using native PHP mail()
        $result = mail($email, $subject, $message, $headers);
        
        if (!$result) {
            error_log("Failed to send email to: $email with subject: $subject");
            return false;
        }
        
        return true;
    }

}
