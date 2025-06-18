<?php 
  session_start();
  if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
  }
  include_once "../php/config.php";
?>

<!DOCTYPE html>
<html>
<head>
  <title>Create Group</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
  <style>
    .group-image-preview {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 10px;
    }
    .wrapper {
      max-width: 500px;
      margin: 0 auto;
      padding: 20px;
    }
    .field {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
    }
    input[type="text"], input[type="file"] {
      width: 100%;
      padding: 8px;
    }
    input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      padding: 10px 15px;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <h2>Create a New Group</h2>
    <form action="../php/create-group.php" method="POST" enctype="multipart/form-data">
      <div class="field">
        <label>Group Name:</label>
        <input type="text" name="group_name" required>
      </div>

      <div class="field">
        <label>Group Image:</label>
        <img src="../php/images/" class="group-image-preview" id="imagePreview">
        <input type="file" name="group_image" id="groupImage" accept="image/*">
      </div>

      <div class="field">
        <label>Select Members:</label><br>
        <?php
          $user_id = $_SESSION['unique_id'];
          $query = mysqli_query($conn, "SELECT * FROM users WHERE unique_id != $user_id");
          while($row = mysqli_fetch_assoc($query)){
            echo '<input type="checkbox" name="members[]" value="'.$row['unique_id'].'"> '.$row['fname'].' '.$row['lname'].'<br>';
          }
        ?>
      </div>

      <div class="field">
        <input type="submit" value="Create Group">
      </div>
    </form>
  </div>

  <script>
    // Preview image before upload
    document.getElementById('groupImage').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          document.getElementById('imagePreview').src = event.target.result;
        }
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>