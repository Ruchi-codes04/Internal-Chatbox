<?php
// admin-functions.php
function checkAdminAuth() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['admin_unique_id'])) {
        header("Location: ../public/admin-login.php");
        exit;
    }
}

function sanitizeInput($conn, $data) {
    return $conn->real_escape_string(trim($data));
}

function handleFileUpload($file, $targetDir, $allowedTypes = ['jpg', 'jpeg', 'png']) {
    if ($file['error'] !== UPLOAD_ERR_OK) return false;
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes)) return false;
    
    $newName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $targetPath = $targetDir . $newName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $newName;
    }
    return false;
}
?>