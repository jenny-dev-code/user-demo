<?php 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 

require '../src/Exception.php'; 
require '../src/PHPMailer.php'; 
require '../src/SMTP.php'; 

function sendResetMail($email, $resetLink) 
{ 
    $mail = new PHPMailer(true); 
    try { 
        $mail->isSMTP(); 
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true; 
        $mail->Username = 'amitjadav5912@gmail.com'; 
        $mail->Password = 'ptcr tzei dfkj mojv'; 
        $mail->SMTPSecure = 'tls'; 
        $mail->Port = 587; 

        $mail->setFrom( 'abc@gmail.com', 'Intruder Safety'); 
        $mail->addAddress($email);

        $mail->Subject = 'Reset Password';  
        $mail->Body = "Click below link:\n\n" . $resetLink; 
        $mail->send(); 
        
        return true; 
    } 
    catch (Exception $e) 
    { 
        die("Mailer Error: " . $mail->ErrorInfo);
    } 
}