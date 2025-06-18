<?php
session_start();
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
}
include_once "../php/config.php";
?>
<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="users">
      <header>
        <?php
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = '{$_SESSION['unique_id']}'");
        if (mysqli_num_rows($sql) > 0) {
            $row = mysqli_fetch_assoc($sql);
        }
        ?>
        <div class="content">
            <img src="../php/images/<?php echo $row['img']; ?>" alt="">
            <div class="details">
                <span><?php echo $row['fname'] . " " . $row['lname']; ?></span>
                <p><?php echo $row['status']; ?></p>
            </div>
        </div>
        <a href="users.php" class="back-link">← Back to Chat</a>
      </header>

      <div class="contact-list">
        <h3>All Contacts</h3>
        <?php
        $current_id = $_SESSION['unique_id'];
        $query = mysqli_query($conn, "SELECT * FROM users WHERE unique_id != '{$current_id}'");

        if (mysqli_num_rows($query) > 0) {
            while ($user = mysqli_fetch_assoc($query)) {
                echo '
                <a href="chat.php?user_id=' . $user['unique_id'] . '" class="contact-item">
                    <img src="../php/images/' . $user['img'] . '" alt="">
                    <div class="contact-details">
                        <span>' . htmlspecialchars($user['fname'] . " " . $user['lname']) . '</span>
                        <p>' . htmlspecialchars($user['status']) . '</p>
                    </div>
                </a>';
            }
        } else {
            echo "<p>No users found.</p>";
        }
        ?>
      </div>
    </section>
  </div>

  <style>
    .contact-list {
        padding: 20px;
    }
    .contact-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px;
        border-bottom: 1px solid #ccc;
        text-decoration: none;
        color: black;
    }
    .contact-item:hover {
        background-color: #f1f1f1;
    }
    .contact-item img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
    .contact-details span {
        font-weight: bold;
    }
  </style>
</body>
</html>
