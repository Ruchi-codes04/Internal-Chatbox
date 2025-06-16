<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "./config.php";

    $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);
    $created_by = $_SESSION['unique_id'];
    $members = $_POST['members'];

    if (!empty($group_name) && !empty($members)) {
        $insertGroup = mysqli_query($conn, "INSERT INTO groups (group_name, created_by) VALUES ('$group_name', '$created_by')");
        if ($insertGroup) {
            $group_id = mysqli_insert_id($conn);

            // Insert creator as admin
            mysqli_query($conn, "INSERT INTO group_members (group_id, unique_id, is_admin) VALUES ($group_id, '$created_by', true)");

            // Insert other members
            foreach ($members as $member_id) {
                mysqli_query($conn, "INSERT INTO group_members (group_id, unique_id) VALUES ($group_id, '$member_id')");
            }

            header("Location: ../public/group-chat.php?group_id=$group_id");
            exit();
        } else {
            echo "Failed to create group: " . mysqli_error($conn);
        }
    } else {
        echo "Group name and members are required.";
    }
} else {
    header("location: ../login.php");
}
?>