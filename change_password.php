<?php

require 'db.php';  

if (isset($_POST['current_password'], $_POST['new_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

    session_start();  
    $userId = $_SESSION['user_id'];  

    $query = "SELECT password FROM users WHERE id = $userId";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $user = mysqli_fetch_assoc($result);
        
        if ($user && password_verify($currentPassword, $user['password'])) {
                        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateQuery = "UPDATE users SET password = '$newPasswordHash' WHERE id = $userId";
            if (mysqli_query($conn, $updateQuery)) {
                echo "Password updated successfully!";
            } else {
                echo "Error updating password!";
            }
        } else {
            echo "Current password is incorrect!";
        }

        mysqli_free_result($result);
    } else {
        echo "Error fetching user data!";
    }
} else {
    echo "Required parameters are missing!";
}

mysqli_close($conn);
?>
