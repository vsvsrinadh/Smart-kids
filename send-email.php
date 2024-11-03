<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hima2005sri@gmail.com';
        $mail->Password   = 'qihkklbetvsouemz'; // Use a secure method to handle sensitive information
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('hima2005sri@gmail.com', 'Hima');
        $mail->addAddress('hima2005sri@gmail.com', 'Sri'); // Change recipient as needed

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New enquiry';
        $mail->Body    = "<h3>This is the HTML message body <b>in bold!</h3></b>
                          <h4>Name: $name</h4>
                          <h4>Email: $email</h4> 
                          <h4>Subject: $subject</h4> 
                          <h4>Message: $message</h4>";

        if ($mail->send()) {
            $_SESSION['status'] = "Thank you! Your message has been sent.";
            header("Location: contact.php"); // Redirect to index page or any other page
            exit();
        } else {
            $_SESSION['status'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            header("Location: contact.php");
            exit();
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    exit();
}