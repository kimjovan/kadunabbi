<?php
// Include PHPMailer autoloader
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure the PHPMailer files are in the correct directory

// Replace contact@example.com with your real receiving email address
$receiving_email_address = 'jovankim22@gmail.com'; // Your Gmail address

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate and sanitize POST data
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
    $date = isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '';
    $time = isset($_POST['time']) ? htmlspecialchars($_POST['time']) : '';
    $people = isset($_POST['people']) ? intval($_POST['people']) : '';
    $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';

    // Check if all required fields are filled
    if (!$name || !$email || !$phone || !$date || !$time || !$people || !$message) {
        die('All fields are required.');
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // Set up SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-gmail-address@gmail.com'; // Your Gmail address
        $mail->Password = 'your-gmail-app-password'; // Your Gmail app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Set the email sender and recipient
        $mail->setFrom($email, $name); // User's email and name
        $mail->addAddress($receiving_email_address); // Recipient's email (your Gmail)

        // Set email content
        $mail->isHTML(true);
        $mail->Subject = "New table booking request from the website";
        
        // Build the HTML email body
        $mail->Body = "
            <h3>New Booking Request</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>Date:</strong> $date</p>
            <p><strong>Time:</strong> $time</p>
            <p><strong>Number of People:</strong> $people</p>
            <p><strong>Message:</strong> $message</p>
        ";

        // Send the email
        if ($mail->send()) {
            echo "Booking request sent successfully!";
        } else {
            echo "Failed to send the email.";
        }
    } catch (Exception $e) {
        echo "Error: " . $mail->ErrorInfo;
    }
} else {
    die('Invalid request method.');
}
?>
