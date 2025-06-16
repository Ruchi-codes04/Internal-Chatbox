<?php
session_start();
if(isset($_SESSION['unique_id'])){
    include_once "./config.php";
    $outgoing_id = mysqli_real_escape_string($conn, $_POST['outgoing_id']);
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";

   $sql = "SELECT messages.*, users.img FROM messages 
        LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
        WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
        OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id})
        ORDER BY msg_id ASC";

    $query = mysqli_query($conn, $sql);
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
             $time = date("g:ia", strtotime($row['created_at'])); // 12-hour format with am/pm

            if($row['outgoing_msg_id'] === $outgoing_id){//if true, user is sender
                     $output .= '<div class="chat outgoing">
                                <div class="details">
                                <p>'. $row['msg'] .'</p>
                                <span class="time">'. date("g:i a", strtotime($row['created_at'])) .'</span>
                                </div>
                                </div>';
            }else{ // user is receiver
                    $output .= '<div class="chat incoming">
                                <img src="../php/images/'. $row['img'] .'" alt="" />
                                <div class="details">
                                <p>'. $row['msg'] .'</p>
                                <span class="time">'. date("g:i a", strtotime($row['created_at'])) .'</span>
                                </div>
                                </div>';
            }
        }
        echo  $output;
        
    }        


    
}else{
    header("../login.php");
}
?>
<!-- previous code -->

