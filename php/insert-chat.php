<?php
session_start();

if (!isset($_SESSION['unique_id'])) {
    header("Location: ../login.php");
    exit;
}

include_once "./config.php";

$outgoing_id = mysqli_real_escape_string($conn, $_POST['outgoing_id']); // Sender
$incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']); // Receiver
$message = mysqli_real_escape_string($conn, $_POST['message']);

if (!empty($message)) {
    // Check if the receiver has approved the sender
    $checkRequest = mysqli_query($conn, "SELECT * FROM message_requests 
        WHERE sender_id = {$outgoing_id} AND receiver_id = {$incoming_id} AND status = 'accepted'");

    if (mysqli_num_rows($checkRequest) === 0) {
        //  Request is not accepted yet

        // Check if a request already exists
        $existingRequest = mysqli_query($conn, "SELECT * FROM message_requests 
            WHERE sender_id = {$outgoing_id} AND receiver_id = {$incoming_id}");

        if (mysqli_num_rows($existingRequest) === 0) {
            // Insert new request
            mysqli_query($conn, "INSERT INTO message_requests (sender_id, receiver_id, status) 
                VALUES ({$outgoing_id}, {$incoming_id}, 'pending')");
        }

        // Deny message sending
        echo "Message not allowed. Waiting for receiver approval.";
        exit;
    }

    //  Sender is approved — insert the message
    $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
        VALUES ({$incoming_id}, {$outgoing_id}, '{$message}')") or die(mysqli_error($conn));
}
?>
