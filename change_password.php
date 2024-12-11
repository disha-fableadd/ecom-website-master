<?php

require 'db.php';  

if (isset($_POST['current_password'], $_POST['new_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

    session_start();  
    $userId = $_SESSION['user_id'];  

    $query = "SELECT password FROM users WHERE id = $userId";
    $result = mysqli_query($conn, $query);
    
    $response = [];  // Initialize response array

    if ($result) {
        $user = mysqli_fetch_assoc($result);
        
        if ($user && password_verify($currentPassword, $user['password'])) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateQuery = "UPDATE users SET password = '$newPasswordHash' WHERE id = $userId";
            if (mysqli_query($conn, $updateQuery)) {
                $response['status'] = 'success';
                $response['message'] = 'Password updated successfully!';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error updating password!';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Current password is incorrect!';
        }

        mysqli_free_result($result);
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error fetching user data!';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Required parameters are missing!';
}

mysqli_close($conn);

// Return the response as a JSON object
echo json_encode($response);
?>
