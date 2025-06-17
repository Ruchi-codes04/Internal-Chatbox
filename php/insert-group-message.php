<?php
error_log("Starting file upload processing");
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "./config.php";
    
    $group_id = mysqli_real_escape_string($conn, $_POST['group_id']);
    $sender_id = mysqli_real_escape_string($conn, $_POST['sender_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

 // File upload handling
    $file_path = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (in_array($_FILES['file']['type'], $allowed_types) && 
        $_FILES['file']['size'] <= $max_size) {
        
        $upload_dir = '..php/uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $file_path = $upload_dir . uniqid() . '.' . $file_ext;
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            error_log("File uploaded successfully: " . $file_path);
        } else {
            error_log("File upload failed");
            $file_path = '';
        }
    } else {
        error_log("Invalid file type or size too large");
        $file_path = '';
    }
}

    if (!empty($message) || !empty($file_path)) {
        $sql = mysqli_query($conn, "INSERT INTO group_messages (group_id, sender_id, message, file_path)
                                  VALUES ({$group_id}, {$sender_id}, '{$message}', '{$file_path}')") 
                                  or die(mysqli_error($conn));
    }
} else {
    header("Location: ../login.php");
}
?>